<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
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

        // Create order
        $order = Order::create([
            'order_number' => 'ORD-' . Str::upper(Str::random(10)),
            'user_id' => auth()->id(),
            'total_amount' => $cartItems->sum(function ($item) {
                return $item->product->price * $item->quantity;
            }),
            'shipping_address' => $request->shipping_address,
            'notes' => $request->notes,
            'payment_method' => 'midtrans'
        ]);

        // Create order items
        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->product->price
            ]);
        }

        // Prepare Midtrans parameters
        $params = [
            'transaction_details' => [
                'order_id' => $order->order_number,
                'gross_amount' => $order->total_amount,
            ],
            'customer_details' => [
                'first_name' => auth()->user()->name,
                'email' => auth()->user()->email,
            ]
        ];

        try {
            $paymentUrl = Snap::createTransaction($params)->redirect_url;
            
            // Clear cart
            Cart::where('user_id', auth()->id())->delete();
            
            return redirect($paymentUrl);
            
        } catch (\Exception $e) {
            return back()->with('error', 'Payment gateway error: ' . $e->getMessage());
        }
    }

    public function callback(Request $request)
    {
        $serverKey = config('services.midtrans.server_key');
        $hashed = hash('sha512', 
            $request->order_id . 
            $request->status_code . 
            $request->gross_amount . 
            $serverKey
        );

        if ($hashed != $request->signature_key) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $order = Order::where('order_number', $request->order_id)->firstOrFail();
        
        if ($order) {
            $order->update(['status' => $this->mapStatus($request->transaction_status)]);
            
            Payment::create([
                'order_id' => $order->id,
                'transaction_id' => $request->transaction_id,
                'amount' => $request->gross_amount,
                'payment_type' => $request->payment_type,
                'status' => $request->transaction_status,
                'response_data' => json_encode($request->all())
            ]);
        }

        return response()->json(['message' => 'Callback processed']);
    }

    private function mapStatus($status)
    {
        $map = [
            'settlement' => 'processing',
            'capture' => 'processing',
            'pending' => 'pending',
            'deny' => 'cancelled',
            'cancel' => 'cancelled',
            'expire' => 'cancelled',
            'failure' => 'cancelled'
        ];

        return $map[$status] ?? 'pending';
    }
}
