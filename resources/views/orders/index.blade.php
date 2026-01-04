{{-- resources/views/orders/index.blade.php --}}
@extends('layouts.app')

@section('title', 'My Orders - Matcha Store')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold matcha-text mb-2">My Orders</h1>
        <p class="text-gray-600">Track and manage your matcha purchases</p>
    </div>

    <!-- Order Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-xl shadow p-4">
            <div class="flex items-center">
                <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center mr-4">
                    <i class="fas fa-shopping-bag text-blue-500 text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Total Orders</p>
                    <p class="text-2xl font-bold">{{ $orders->total() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow p-4">
            <div class="flex items-center">
                <div class="w-12 h-12 rounded-full bg-yellow-100 flex items-center justify-center mr-4">
                    <i class="fas fa-clock text-yellow-500 text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Pending</p>
                    <p class="text-2xl font-bold">{{ $pendingCount }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow p-4">
            <div class="flex items-center">
                <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center mr-4">
                    <i class="fas fa-check-circle text-green-500 text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Completed</p>
                    <p class="text-2xl font-bold">{{ $completedCount }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow p-4">
            <div class="flex items-center">
                <div class="w-12 h-12 rounded-full bg-purple-100 flex items-center justify-center mr-4">
                    <i class="fas fa-coins text-purple-500 text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Total Spent</p>
                    <p class="text-2xl font-bold">Rp {{ number_format($totalSpent, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow p-4 mb-6">
        <div class="flex flex-wrap gap-4 items-center">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-gray-700 text-sm font-medium mb-1">Filter by Status</label>
                <select id="statusFilter" class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-matcha-green">
                    <option value="">All Orders</option>
                    <option value="pending">Pending</option>
                    <option value="processing">Processing</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
            
            <div class="flex-1 min-w-[200px]">
                <label class="block text-gray-700 text-sm font-medium mb-1">Date Range</label>
                <select id="dateFilter" class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-matcha-green">
                    <option value="">All Time</option>
                    <option value="today">Today</option>
                    <option value="week">This Week</option>
                    <option value="month">This Month</option>
                    <option value="year">This Year</option>
                </select>
            </div>
            
            <div class="flex-1 min-w-[200px]">
                <label class="block text-gray-700 text-sm font-medium mb-1">Sort By</label>
                <select id="sortFilter" class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-matcha-green">
                    <option value="newest">Newest First</option>
                    <option value="oldest">Oldest First</option>
                    <option value="price_high">Highest Amount</option>
                    <option value="price_low">Lowest Amount</option>
                </select>
            </div>
            
            <div class="self-end">
                <button onclick="applyFilters()" class="btn-matcha px-6 py-2 rounded-lg">
                    Apply Filters
                </button>
                <button onclick="resetFilters()" class="ml-2 border border-gray-300 px-4 py-2 rounded-lg hover:bg-gray-50">
                    Reset
                </button>
            </div>
        </div>
    </div>

    <!-- Orders List -->
    @if($orders->count() > 0)
    <div class="bg-white rounded-xl shadow overflow-hidden mb-8">
    <div class="px-6 py-4 border-b">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-xl font-semibold">Your Orders</h2>
                <p class="text-gray-500 text-sm">{{ $orders->total() }} total orders</p>
            </div>
            <div class="flex items-center space-x-4">
                @if($pendingCount > 0)
                <button onclick="checkPendingOrders()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-sync mr-2"></i>Check Pending Payments ({{ $pendingCount }})
                </button>
                @endif
            </div>
        </div>
    </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="py-3 px-6 text-left text-gray-600 font-medium">Order #</th>
                        <th class="py-3 px-6 text-left text-gray-600 font-medium">Date</th>
                        <th class="py-3 px-6 text-left text-gray-600 font-medium">Items</th>
                        <th class="py-3 px-6 text-left text-gray-600 font-medium">Amount</th>
                        <th class="py-3 px-6 text-left text-gray-600 font-medium">Status</th>
                        <th class="py-3 px-6 text-left text-gray-600 font-medium">Payment</th>
                        <th class="py-3 px-6 text-left text-gray-600 font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($orders as $order)
                    <tr class="hover:bg-gray-50 transition" data-order-id="{{ $order->id }}" data-order-status="{{ $order->status }}">
                        <!-- Order Number -->
                        <td class="py-4 px-6">
                            <div class="font-mono font-semibold text-gray-900">
                                {{ $order->order_number }}
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ $order->created_at->format('h:i A') }}
                            </div>
                        </td>

                        <!-- Date -->
                        <td class="py-4 px-6">
                            <div class="text-gray-900">
                                {{ $order->created_at->format('d M Y') }}
                            </div>
                        </td>

                        <!-- Items -->
                        <td class="py-4 px-6">
                            <div class="flex items-center">
                                <div class="text-gray-900 font-medium">
                                    {{ $order->items_count }} items
                                </div>
                                @if($order->items_count > 1)
                                <div class="ml-2 relative">
                                    <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-xs text-gray-600">
                                        +{{ $order->items_count - 1 }}
                                    </div>
                                </div>
                                @endif
                            </div>
                        </td>

                        <!-- Amount -->
                        <td class="py-4 px-6">
                            <div class="font-bold text-lg matcha-text">
                                Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ $order->payment_method }}
                            </div>
                        </td>

                        <!-- Status -->
                        <td class="py-4 px-6">
                            @php
                                $statusColors = [
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'processing' => 'bg-blue-100 text-blue-800',
                                    'completed' => 'bg-green-100 text-green-800',
                                    'cancelled' => 'bg-red-100 text-red-800'
                                ];
                            @endphp
                            <span class="px-3 py-1 rounded-full text-sm font-medium {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($order->status) }}
                            </span>
                            @if($order->status == 'completed')
                            <div class="text-xs text-gray-500 mt-1">
                                Delivered on {{ optional($order->updated_at)->format('d M') }}
                            </div>
                            @endif
                        </td>

                        <!-- Payment -->
                        <td class="py-4 px-6">
                            <div class="flex items-center">
                                @if($order->payment_status == 'paid')
                                    <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                    <span class="text-green-700">Paid</span>
                                @elseif($order->payment_status == 'pending')
                                    <i class="fas fa-clock text-yellow-500 mr-2"></i>
                                    <span class="text-yellow-700">Pending</span>
                                @else
                                    <i class="fas fa-times-circle text-red-500 mr-2"></i>
                                    <span class="text-red-700">Failed</span>
                                @endif
                            </div>
                        </td>

                        <!-- Actions -->
                        <td class="py-4 px-6">
                        <div class="flex flex-col space-y-2">
                            <!-- Continue Payment Button (if pending) -->
                            @if($order->canContinuePayment())
                                <a href="{{ $order->getPaymentUrl() }}" 
                                target="_blank"
                                class="px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition text-sm text-center font-medium">
                                    <i class="fas fa-credit-card mr-1"></i> Continue Payment
                                </a>
                                
                                @if($order->isPaymentExpired())
                                    <span class="text-xs text-red-600 text-center">
                                        <i class="fas fa-exclamation-circle"></i> Payment link expired
                                    </span>
                                @endif
                            @endif
                            
                            <!-- View Order Button -->
                            <a href="{{ route('orders.show', $order->id) }}" 
                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-800 transition text-sm text-center">
                                <i class="fas fa-eye mr-1"></i> View Details
                            </a>
                            
                            <!-- Cancel Button (only for pending) -->
                            @if($order->status == 'pending')
                            <form action="{{ route('orders.cancel', $order->id) }}" method="POST" class="inline">
                                @csrf
                                @method('PUT')
                                <button type="submit" 
                                        onclick="return confirm('Are you sure you want to cancel this order?')"
                                        class="w-full px-4 py-2 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition text-sm">
                                    <i class="fas fa-times mr-1"></i> Cancel
                                </button>
                            </form>
                            @endif
                            
                            <!-- Reorder Button (only for completed) -->
                            @if($order->status == 'completed')
                            <button onclick="reorder({{ $order->id }})" 
                                    class="px-4 py-2 bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200 transition text-sm">
                                <i class="fas fa-redo mr-1"></i> Reorder
                            </button>
                            @endif
                        </div>
                    </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t">
            {{ $orders->links() }}
        </div>
    </div>

    <!-- Order Status Guide -->
    <div class="bg-gray-50 rounded-xl p-6 mb-8">
        <h3 class="font-semibold text-lg mb-4">Order Status Guide</h3>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="flex items-center">
                <div class="w-8 h-8 rounded-full bg-yellow-100 flex items-center justify-center mr-3">
                    <i class="fas fa-clock text-yellow-500"></i>
                </div>
                <div>
                    <h4 class="font-medium">Pending</h4>
                    <p class="text-sm text-gray-600">Order placed, awaiting processing</p>
                </div>
            </div>
            <div class="flex items-center">
                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                    <i class="fas fa-cog text-blue-500"></i>
                </div>
                <div>
                    <h4 class="font-medium">Processing</h4>
                    <p class="text-sm text-gray-600">Preparing your order for shipment</p>
                </div>
            </div>
            <div class="flex items-center">
                <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center mr-3">
                    <i class="fas fa-check-circle text-green-500"></i>
                </div>
                <div>
                    <h4 class="font-medium">Completed</h4>
                    <p class="text-sm text-gray-600">Order delivered successfully</p>
                </div>
            </div>
            <div class="flex items-center">
                <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center mr-3">
                    <i class="fas fa-times-circle text-red-500"></i>
                </div>
                <div>
                    <h4 class="font-medium">Cancelled</h4>
                    <p class="text-sm text-gray-600">Order was cancelled</p>
                </div>
            </div>
        </div>
    </div>

    @else
    <!-- Empty Orders State -->
    <div class="text-center py-16 bg-white rounded-xl shadow">
        <div class="w-24 h-24 mx-auto mb-6 rounded-full bg-gray-100 flex items-center justify-center">
            <i class="fas fa-shopping-bag text-4xl text-gray-300"></i>
        </div>
        <h3 class="text-2xl font-semibold text-gray-700 mb-3">No orders yet</h3>
        <p class="text-gray-500 mb-8 max-w-md mx-auto">
            Start your matcha journey by placing your first order. Explore our premium matcha collection and experience authentic Japanese flavors.
        </p>
        <div class="space-x-4">
            <a href="{{ route('products.index') }}" class="btn-matcha px-6 py-3 rounded-lg text-lg">
                <i class="fas fa-store mr-2"></i>Shop Now
            </a>
            <a href="{{ route('home') }}" class="px-6 py-3 border border-matcha-green text-matcha-green rounded-lg hover:bg-green-50 transition">
                <i class="fas fa-home mr-2"></i>Go to Home
            </a>
        </div>
        
        <!-- Featured Products Teaser -->
        {{-- Atau ganti dengan yang lebih sederhana: --}}
        @if(isset($featuredProducts) && count($featuredProducts) > 0)
        <!-- Featured Products Teaser -->
        <div class="mt-12">
            <h4 class="text-lg font-semibold mb-6">Popular Matcha Products</h4>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 max-w-2xl mx-auto">
                @foreach($featuredProducts as $product)
                <a href="{{ route('products.show', $product->id) }}" class="bg-white border rounded-lg p-4 hover:shadow-md transition">
                    <div class="h-16 bg-gray-100 rounded-lg mb-2 flex items-center justify-center">
                        <i class="fas fa-leaf text-2xl text-green-300"></i>
                    </div>
                    <h5 class="font-medium text-sm truncate">{{ $product->name }}</h5>
                    <p class="text-matcha-green font-semibold text-sm">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                </a>
                @endforeach
            </div>
        </div>
        @endif
    </div>
    @endif

    <!-- Recent Activity -->
    @if($featuredProducts && $featuredProducts->isNotEmpty())
    <div class="mt-12">
        <h2 class="text-2xl font-bold matcha-text mb-6">Recent Activity</h2>
        <div class="bg-white rounded-xl shadow p-6">
            <div class="space-y-4">
                @foreach($recentActivity as $activity)
                <div class="flex items-start">
                    <div class="w-8 h-8 rounded-full {{ $activity['color'] }} flex items-center justify-center mr-4 flex-shrink-0">
                        <i class="{{ $activity['icon'] }} text-white text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-gray-900">{{ $activity['message'] }}</p>
                        <p class="text-sm text-gray-500">{{ $activity['time'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script>
        function checkPendingOrders() {
            if (confirm('Check payment status for all pending orders?')) {
                // Show loading
                const button = event.target;
                const originalText = button.innerHTML;
                button.disabled = true;
                button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Checking...';
                
                // Collect pending order IDs
                const pendingOrders = [];
                document.querySelectorAll('[data-order-status="pending"]').forEach(el => {
                    pendingOrders.push(el.dataset.orderId);
                });
                
                // Check each order
                let completed = 0;
                pendingOrders.forEach(orderId => {
                    fetch(`/orders/${orderId}/check-status`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        completed++;
                        if (completed === pendingOrders.length) {
                            // Reload page when all checks complete
                            location.reload();
                        }
                    })
                    .catch(error => {
                        completed++;
                        if (completed === pendingOrders.length) {
                            location.reload();
                        }
                    });
                });
            }
        }

    // Apply filters
    function applyFilters() {
        const status = document.getElementById('statusFilter').value;
        const date = document.getElementById('dateFilter').value;
        const sort = document.getElementById('sortFilter').value;
        
        let url = new URL(window.location.href);
        let params = new URLSearchParams(url.search);
        
        if (status) params.set('status', status);
        else params.delete('status');
        
        if (date) params.set('date', date);
        else params.delete('date');
        
        if (sort !== 'newest') params.set('sort', sort);
        else params.delete('sort');
        
        params.delete('page'); // Reset to page 1
        
        window.location.href = `${url.pathname}?${params.toString()}`;
    }
    
    // Reset filters
    function resetFilters() {
        window.location.href = "{{ route('orders.index') }}";
    }

    function showNotification(message, type = 'success') {
        // Remove existing notifications
        const existing = document.querySelector('.custom-notification');
        if (existing) existing.remove();
        
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 px-6 py-4 rounded-lg shadow-xl z-50 custom-notification transform transition-all duration-300 ${
            type === 'success' 
                ? 'bg-green-500 text-white' 
                : 'bg-red-500 text-white'
        }`;
        notification.style.transform = 'translateX(400px)';
        notification.innerHTML = `
            <div class="flex items-center">
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} mr-3 text-xl"></i>
                <div>
                    <p class="font-semibold">${type === 'success' ? 'Success!' : 'Error'}</p>
                    <p class="text-sm">${message}</p>
                </div>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        // Slide in
        setTimeout(() => {
            notification.style.transform = 'translateX(0)';
        }, 100);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            notification.style.transform = 'translateX(400px)';
            setTimeout(() => notification.remove(), 300);
        }, 5000);
    }
    
    // Reorder function
    function reorder(orderId) {
        if (confirm('Add all items from this order to cart?')) {
            // Show loading state
            const button = event.target;
            const originalHTML = button.innerHTML;
            button.disabled = true;
            button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Adding...';
            
            fetch(`/orders/${orderId}/reorder`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message
                    showNotification(data.message, 'success');
                    
                    // Update cart count in navbar if element exists
                    const cartCountElements = document.querySelectorAll('.cart-count, [data-cart-count]');
                    cartCountElements.forEach(element => {
                        element.textContent = data.cart_count;
                        
                        // Add animation
                        element.classList.add('animate-bounce');
                        setTimeout(() => {
                            element.classList.remove('animate-bounce');
                        }, 1000);
                    });
                    
                    // Optionally redirect to cart after a delay
                    if (data.added_count > 0) {
                        setTimeout(() => {
                            if (confirm('Items added to cart. Would you like to view your cart?')) {
                                window.location.href = '/cart';
                            }
                        }, 1500);
                    }
                } else {
                    showNotification('Error adding items to cart', 'error');
                }
                
                // Restore button
                button.disabled = false;
                button.innerHTML = originalHTML;
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Error adding items to cart', 'error');
                
                // Restore button
                button.disabled = false;
                button.innerHTML = originalHTML;
            });
        }
    }

    
    // Initialize filters from URL
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        
        if (urlParams.has('status')) {
            document.getElementById('statusFilter').value = urlParams.get('status');
        }
        
        if (urlParams.has('date')) {
            document.getElementById('dateFilter').value = urlParams.get('date');
        }
        
        if (urlParams.has('sort')) {
            document.getElementById('sortFilter').value = urlParams.get('sort');
        }
    });
</script>
@endpush
@endsection