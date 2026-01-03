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
        // Di OrderController.php, sebelum return view:
        if (!isset($featuredProducts) || !is_object($featuredProducts)) {
            $featuredProducts = collect(); // Empty collection
        }
        return view('orders.show', compact('order'));
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
    
    public function reorder($id)
    {
        $user = Auth::user();
        $order = Order::with('items.product')
                     ->where('user_id', $user->id)
                     ->findOrFail($id);
        
        $addedCount = 0;
        
        foreach ($order->items as $item) {
            // Check if product is still available
            if ($item->product->stock > 0) {
                // Check if product already in cart
                $existingCart = $user->carts()
                    ->where('product_id', $item->product_id)
                    ->first();
                
                if ($existingCart) {
                    // Update quantity
                    $existingCart->increment('quantity', $item->quantity);
                } else {
                    // Add to cart
                    $user->carts()->create([
                        'product_id' => $item->product_id,
                        'quantity' => $item->quantity
                    ]);
                }
                
                $addedCount++;
            }
        }
        
        $cartCount = $user->carts()->count();
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "{$addedCount} items added to cart",
                'cart_count' => $cartCount
            ]);
        }
        
        return redirect()->route('cart.index')->with('success', "{$addedCount} items added to cart");
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
}