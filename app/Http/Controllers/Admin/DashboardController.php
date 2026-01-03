<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Feedback;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'total_products' => Product::count(),
            'low_stock' => Product::where('stock', '<', 10)->count(),
            'total_customers' => User::where('role', 'customer')->count(),
            'new_feedbacks' => Feedback::where('status', 'new')->count(),
            'total_revenue' => Order::where('status', 'completed')->sum('total_amount'),
        ];

        $recentOrders = Order::with('user')->latest()->take(5)->get();
        $recentFeedbacks = Feedback::latest()->take(5)->get();
        $recentProducts = Product::latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentOrders', 'recentFeedbacks', 'recentProducts'));
    }

    public function orders()
    {
        $orders = Order::with(['user', 'items.product'])->latest()->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }

    public function updateOrderStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $order->status = $request->status;
        $order->save();

        return redirect()->back()->with('success', 'Order status updated successfully.');
    }

    public function feedbacks()
    {
        $feedbacks = Feedback::with('user')->latest()->paginate(10);
        return view('admin.feedbacks.index', compact('feedbacks'));
    }

    public function updateFeedbackStatus(Request $request, $id)
    {
        $feedback = Feedback::findOrFail($id);
        $feedback->status = $request->status;
        $feedback->save();

        return redirect()->back()->with('success', 'Feedback status updated successfully.');
    }

    public function feedbackDetails($id)
    {
        $feedback = Feedback::with('user')->findOrFail($id);
        return view('admin.feedbacks.show', compact('feedback'));
    }
}