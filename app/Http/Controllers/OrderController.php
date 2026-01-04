<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
{
    $user = Auth::user();
    
    $query = $user->orders()->withCount('items');
    
    // Filter by status
    if ($request->has('status')) {
        $query->where('status', $request->status);
    }
    
    // Filter by date
    if ($request->has('date')) {
        switch ($request->date) {
            case 'today':
                $query->whereDate('created_at', today());
                break;
            case 'week':
                $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                break;
            case 'month':
                $query->whereMonth('created_at', now()->month);
                break;
            case 'year':
                $query->whereYear('created_at', now()->year);
                break;
        }
    }
    
    // Sort orders
    switch ($request->get('sort', 'newest')) {
        case 'oldest':
            $query->oldest();
            break;
        case 'price_high':
            $query->orderBy('total_amount', 'desc');
            break;
        case 'price_low':
            $query->orderBy('total_amount', 'asc');
            break;
        default:
            $query->latest();
    }
    
    $orders = $query->paginate(10)->withQueryString();
    
    // Calculate stats
    $pendingCount = $user->orders()->where('status', 'pending')->count();
    $completedCount = $user->orders()->where('status', 'completed')->count();
    $totalSpent = $user->orders()->where('status', 'completed')->sum('total_amount');
    
    // PERBAIKAN: Gunakan get() bukan langsung collection
    $featuredProducts = Product::where('is_featured', true)->limit(4)->get();
    
    // Recent activity
    $recentActivity = $this->getRecentActivity($user);
    // Di OrderController.php, sebelum return view:
    if (!isset($featuredProducts) || !is_object($featuredProducts)) {
        $featuredProducts = collect(); // Empty collection
    }
    
    return view('orders.index', compact(
        'orders',
        'pendingCount',
        'completedCount',
        'totalSpent',
        'featuredProducts',
        'recentActivity'
    ));
}
    
    public function show($id)
    {
        $order = Order::with(['items.product', 'payment'])
                    ->where('user_id', Auth::id())
                    ->findOrFail($id);
        
        // Ensure featuredProducts is set
        $featuredProducts = collect();
        
        return view('orders.show', compact('order', 'featuredProducts'));
    }
    
    public function cancel(Request $request, $id)
    {
        $order = Order::where('user_id', Auth::id())
                     ->where('status', 'pending')
                     ->findOrFail($id);
        
        $order->status = 'cancelled';
        $order->save();
        
        // Return stock if needed
        foreach ($order->items as $item) {
            $item->product->increment('stock', $item->quantity);
        }
        
        return redirect()->route('orders.index')->with('success', 'Order cancelled successfully');
    }
    
    public function reorder(Request $request, $id)
    {
        $user = Auth::user();
        $order = Order::with('items.product')
                    ->where('user_id', $user->id)
                    ->findOrFail($id);
        
        $addedCount = 0;
        $outOfStock = [];
        
        foreach ($order->items as $item) {
            // Check if product is still available
            if ($item->product->stock > 0) {
                // Calculate quantity to add (limited by available stock)
                $quantityToAdd = min($item->quantity, $item->product->stock);
                
                // Check if product already in cart
                $existingCart = $user->carts()
                    ->where('product_id', $item->product_id)
                    ->first();
                
                if ($existingCart) {
                    // Update quantity (but don't exceed stock)
                    $newQuantity = $existingCart->quantity + $quantityToAdd;
                    $maxQuantity = min($newQuantity, $item->product->stock);
                    $existingCart->update(['quantity' => $maxQuantity]);
                } else {
                    // Add to cart
                    $user->carts()->create([
                        'product_id' => $item->product_id,
                        'quantity' => $quantityToAdd
                    ]);
                }
                
                $addedCount++;
            } else {
                // Track out of stock items
                $outOfStock[] = $item->product->name;
            }
        }
        
        $cartCount = $user->carts()->count();
        
        // Prepare success message
        $message = "{$addedCount} items added to cart";
        if (!empty($outOfStock)) {
            $message .= ". However, the following items are out of stock: " . implode(', ', $outOfStock);
        }
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'cart_count' => $cartCount,
                'added_count' => $addedCount,
                'out_of_stock' => $outOfStock
            ]);
        }
        
        return redirect()->route('cart.index')->with('success', $message);
    }
    
    private function getRecentActivity($user)
    {
        $activities = [];
        
        // Recent orders
        $recentOrders = $user->orders()->latest()->limit(3)->get();
        foreach ($recentOrders as $order) {
            $activities[] = [
                'icon' => 'fas fa-shopping-bag',
                'color' => 'bg-blue-500',
                'message' => "Order #{$order->order_number} placed - Rp " . number_format($order->total_amount, 0, ',', '.'),
                'time' => $order->created_at->diffForHumans()
            ];
        }
        
        // Order status updates
        $statusUpdates = $user->orders()
            ->whereIn('status', ['processing', 'completed'])
            ->where('updated_at', '>', now()->subDays(7))
            ->latest()
            ->limit(2)
            ->get();
        
        foreach ($statusUpdates as $order) {
            $icon = $order->status == 'completed' ? 'fas fa-check-circle' : 'fas fa-cog';
            $color = $order->status == 'completed' ? 'bg-green-500' : 'bg-yellow-500';
            $activities[] = [
                'icon' => $icon,
                'color' => $color,
                'message' => "Order #{$order->order_number} marked as " . ucfirst($order->status),
                'time' => $order->updated_at->diffForHumans()
            ];
        }
        
        // Sort by time
        usort($activities, function($a, $b) {
            return strtotime($b['time']) - strtotime($a['time']);
        });
        
        return array_slice($activities, 0, 5);
    }

    public function checkStatus($id)
    {
        $order = Order::where('user_id', Auth::id())
                    ->findOrFail($id);
        
        if ($order->payment_status != 'pending') {
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Payment already processed',
                    'status' => $order->status,
                    'payment_status' => $order->payment_status
                ]);
            }
            return redirect()->back()->with('info', 'Payment has already been processed.');
        }
        
        try {
            // Check status with Midtrans
            \Midtrans\Config::$serverKey = config('services.midtrans.server_key');
            \Midtrans\Config::$isProduction = config('services.midtrans.is_production');
            
            $status = \Midtrans\Transaction::status($order->order_number);
            
            \Log::info('Manual Status Check for Order: ' . $order->order_number, (array) $status);
            
            // Update order based on status
            $transactionStatus = $status->transaction_status;
            $fraudStatus = $status->fraud_status ?? null;

            if ($transactionStatus == 'capture') {
                if ($fraudStatus == 'accept') {
                    $order->status = 'processing';
                    $order->payment_status = 'paid';
                    $message = 'Payment confirmed! Your order is now being processed.';
                }
            } else if ($transactionStatus == 'settlement') {
                $order->status = 'processing';
                $order->payment_status = 'paid';
                $message = 'Payment confirmed! Your order is now being processed.';
            } else if ($transactionStatus == 'pending') {
                $message = 'Payment is still pending. Please complete your payment.';
            } else if (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
                $order->status = 'cancelled';
                $order->payment_status = 'failed';
                $message = 'Payment failed or was cancelled.';
                
                // Restore stock
                foreach ($order->items as $item) {
                    $item->product->increment('stock', $item->quantity);
                }
            } else {
                $message = 'Payment status: ' . ucfirst($transactionStatus);
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
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'status' => $order->status,
                    'payment_status' => $order->payment_status
                ]);
            }
            
            return redirect()->back()->with('success', $message);
            
        } catch (\Exception $e) {
            \Log::error('Error checking payment status: ' . $e->getMessage());
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unable to check payment status'
                ], 500);
            }
            
            return redirect()->back()->with('error', 'Unable to check payment status. Please try again later.');
        }
    }
}