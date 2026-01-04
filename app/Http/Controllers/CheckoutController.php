<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Midtrans\Snap;
use Midtrans\Config;

class CheckoutController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = config('services.midtrans.is_sanitized');
        Config::$is3ds = config('services.midtrans.is_3ds');
    }

    public function index()
    {
        $cartItems = Cart::where('user_id', auth()->id())
            ->with('product')
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $subtotal = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        $total = $subtotal;

        return view('checkout.index', compact('cartItems', 'subtotal', 'total'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'shipping_address' => 'required|string|max:500',
            'notes' => 'nullable|string|max:500'
        ]);

        $cartItems = Cart::where('user_id', auth()->id())
            ->with('product')
            ->get();

        if ($cartItems->isEmpty()) {
            return back()->with('error', 'Your cart is empty.');
        }

        // Check stock availability
        foreach ($cartItems as $item) {
            if ($item->product->stock < $item->quantity) {
                return back()->with('error', "Insufficient stock for {$item->product->name}");
            }
        }

        $totalAmount = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        // Create order
        $order = Order::create([
            'order_number' => 'ORD-' . strtoupper(Str::random(10)),
            'user_id' => auth()->id(),
            'total_amount' => $totalAmount,
            'shipping_address' => $request->shipping_address,
            'notes' => $request->notes,
            'payment_method' => 'midtrans',
            'status' => 'pending',
            'payment_status' => 'pending'
        ]);

        // Create order items and reduce stock
        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->product->price
            ]);

            // Reduce product stock
            $product = Product::find($item->product_id);
            $product->decrement('stock', $item->quantity);
        }

        // Prepare Midtrans parameters with proper callback URLs
        $params = [
            'transaction_details' => [
                'order_id' => $order->order_number,
                'gross_amount' => (int) $order->total_amount,
            ],
            'customer_details' => [
                'first_name' => auth()->user()->name,
                'email' => auth()->user()->email,
            ],
            'item_details' => $cartItems->map(function ($item) {
                return [
                    'id' => $item->product_id,
                    'price' => (int) $item->product->price,
                    'quantity' => $item->quantity,
                    'name' => $item->product->name,
                ];
            })->toArray(),
            'callbacks' => [
                'finish' => route('checkout.finish'),
                'error' => route('checkout.error'),
                'pending' => route('checkout.finish'),
            ]
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
            $order->update([
                'snap_token' => $snapToken,
                'snap_redirect_url' => "https://app.sandbox.midtrans.com/snap/v2/vtweb/" . $snapToken
            ]);
            
            // Clear cart
            Cart::where('user_id', auth()->id())->delete();
            
            // Redirect to Midtrans payment page
            return redirect()->away("https://app.sandbox.midtrans.com/snap/v2/vtweb/" . $snapToken);
            
        } catch (\Exception $e) {
            // Rollback stock if payment creation fails
            foreach ($cartItems as $item) {
                $product = Product::find($item->product_id);
                $product->increment('stock', $item->quantity);
            }
            
            $order->delete();
            return back()->with('error', 'Payment gateway error: ' . $e->getMessage());
        }
    }

    public function callback(Request $request)
    {
        \Log::info('Midtrans Callback:', $request->all());
        
        $serverKey = config('services.midtrans.server_key');
        $hashed = hash('sha512', 
            $request->order_id . 
            $request->status_code . 
            $request->gross_amount . 
            $serverKey
        );

        if ($hashed != $request->signature_key) {
            \Log::error('Invalid Midtrans signature');
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $order = Order::where('order_number', $request->order_id)->first();
        
        if (!$order) {
            \Log::error('Order not found: ' . $request->order_id);
            return response()->json(['message' => 'Order not found'], 404);
        }
        
        $transactionStatus = $request->transaction_status;
        $fraudStatus = $request->fraud_status ?? null;

        \Log::info("Processing order {$order->order_number}, status: {$transactionStatus}");

        // Update order status based on transaction status
        if ($transactionStatus == 'capture') {
            if ($fraudStatus == 'challenge') {
                $order->status = 'pending';
                $order->payment_status = 'pending';
            } else if ($fraudStatus == 'accept') {
                $order->status = 'processing';
                $order->payment_status = 'paid';
            }
        } else if ($transactionStatus == 'settlement') {
            $order->status = 'processing';
            $order->payment_status = 'paid';
        } else if ($transactionStatus == 'pending') {
            $order->status = 'pending';
            $order->payment_status = 'pending';
        } else if (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
            $order->status = 'cancelled';
            $order->payment_status = 'failed';
            
            // Restore stock
            foreach ($order->items as $item) {
                $item->product->increment('stock', $item->quantity);
            }
        }

        $order->save();
        
        // Create or update payment record
        Payment::updateOrCreate(
            ['transaction_id' => $request->transaction_id],
            [
                'order_id' => $order->id,
                'amount' => $request->gross_amount,
                'payment_type' => $request->payment_type,
                'status' => $transactionStatus,
                'response_data' => json_encode($request->all())
            ]
        );

        \Log::info("Order {$order->order_number} updated successfully");

        return response()->json(['message' => 'Callback processed successfully']);
    }

    // Add these helper methods for payment success/failure pages
    public function success($orderId)
    {
        $order = Order::where('id', $orderId)
                     ->where('user_id', auth()->id())
                     ->firstOrFail();
        
        return view('checkout.success', compact('order'));
    }

    public function pending($orderId)
    {
        $order = Order::where('id', $orderId)
                     ->where('user_id', auth()->id())
                     ->firstOrFail();
        
        return view('checkout.pending', compact('order'));
    }

    public function finish(Request $request)
    {
        // Get order from query parameters
        $orderNumber = $request->query('order_id');
        
        if (!$orderNumber) {
            return redirect()->route('orders.index')->with('error', 'Invalid payment response');
        }
        
        $order = Order::where('order_number', $orderNumber)
                    ->where('user_id', auth()->id())
                    ->first();
        
        if (!$order) {
            return redirect()->route('orders.index')->with('error', 'Order not found');
        }
        
        // **IMPORTANT: Verify payment status with Midtrans API**
        try {
            $status = \Midtrans\Transaction::status($orderNumber);
            
            \Log::info('Midtrans Status Check:', (array) $status);
            
            // Update order based on actual Midtrans status
            $this->updateOrderFromMidtransStatus($order, $status);
            
            // Redirect based on transaction status
            if (in_array($status->transaction_status, ['capture', 'settlement'])) {
                return redirect()->route('checkout.success', $order->id);
            } elseif ($status->transaction_status == 'pending') {
                return redirect()->route('checkout.pending', $order->id);
            } else {
                return redirect()->route('checkout.error');
            }
            
        } catch (\Exception $e) {
            \Log::error('Midtrans Status Check Error: ' . $e->getMessage());
            
            // Fallback to query parameters
            $transactionStatus = $request->query('transaction_status');
            
            if ($transactionStatus == 'settlement' || $transactionStatus == 'capture') {
                return redirect()->route('checkout.success', $order->id);
            } elseif ($transactionStatus == 'pending') {
                return redirect()->route('checkout.pending', $order->id);
            } else {
                return redirect()->route('checkout.error');
            }
        }
    }

    /**
     * Update order status from Midtrans transaction status
     */
    private function updateOrderFromMidtransStatus($order, $status)
    {
        $transactionStatus = $status->transaction_status;
        $fraudStatus = $status->fraud_status ?? null;
        
        \Log::info("Updating order {$order->order_number} with status: {$transactionStatus}");

        // Update order status based on transaction status
        if ($transactionStatus == 'capture') {
            if ($fraudStatus == 'challenge') {
                $order->status = 'pending';
                $order->payment_status = 'pending';
            } else if ($fraudStatus == 'accept') {
                $order->status = 'processing';
                $order->payment_status = 'paid';
            }
        } else if ($transactionStatus == 'settlement') {
            $order->status = 'processing';
            $order->payment_status = 'paid';
        } else if ($transactionStatus == 'pending') {
            $order->status = 'pending';
            $order->payment_status = 'pending';
        } else if (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
            $order->status = 'cancelled';
            $order->payment_status = 'failed';
            
            // Restore stock
            foreach ($order->items as $item) {
                $item->product->increment('stock', $item->quantity);
            }
        }

        $order->save();
        
        // Create or update payment record
        Payment::updateOrCreate(
            ['transaction_id' => $status->transaction_id],
            [
                'order_id' => $order->id,
                'amount' => $status->gross_amount,
                'payment_type' => $status->payment_type,
                'status' => $transactionStatus,
                'response_data' => json_encode($status)
            ]
        );
        
        \Log::info("Order {$order->order_number} updated to status: {$order->status}, payment: {$order->payment_status}");
    }

    public function error()
    {
        return view('checkout.error');
    }
}