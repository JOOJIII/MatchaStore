{{-- resources/views/orders/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Order Details - Matcha Store')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <nav class="flex mb-6" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('home') }}" class="text-gray-600 hover:text-matcha-green">Home</a>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                    <a href="{{ route('orders.index') }}" class="text-gray-600 hover:text-matcha-green">My Orders</a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                    <span class="text-matcha-green font-semibold">Order #{{ $order->order_number }}</span>
                </div>
            </li>
        </ol>
    </nav>
    
    <!-- Order Header -->
     
    <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold mb-2">Order #{{ $order->order_number }}</h1>
                <div class="flex items-center space-x-4">
                    <span class="px-3 py-1 rounded-full text-sm font-medium 
                        @if($order->status == 'pending') bg-yellow-100 text-yellow-800
                        @elseif($order->status == 'processing') bg-blue-100 text-blue-800
                        @elseif($order->status == 'completed') bg-green-100 text-green-800
                        @else bg-red-100 text-red-800 @endif">
                        {{ ucfirst($order->status) }}
                    </span>
                    <span class="text-gray-600">
                        <i class="far fa-calendar mr-1"></i>
                        {{ $order->created_at->format('d M Y, h:i A') }}
                    </span>
                </div>
            </div>
            
            <div class="mt-4 md:mt-0 flex flex-col gap-2">
                <!-- Check Payment Status Button -->
                @if($order->payment_status == 'pending' && $order->status == 'pending')
                <form action="{{ route('orders.check-status', $order->id) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" 
                            class="w-full px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                        <i class="fas fa-sync mr-2"></i>Check Payment Status
                    </button>
                </form>
                @endif
                
                @if($order->status == 'pending')
                <form action="{{ route('orders.cancel', $order->id) }}" method="POST" class="inline">
                    @csrf
                    @method('PUT')
                    <button type="submit" 
                            onclick="return confirm('Are you sure you want to cancel this order?')"
                            class="w-full px-6 py-2 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition font-medium">
                        <i class="fas fa-times mr-2"></i>Cancel Order
                    </button>
                </form>
                @endif
                
                <button onclick="reorder({{ $order->id }})" 
                        class="w-full px-6 py-2 btn-matcha rounded-lg font-medium">
                    <i class="fas fa-redo mr-2"></i>Reorder
                </button>
            </div>
        </div>
    </div>
        
    <!-- PAYMENT PENDING ALERT - ADD THIS SECTION -->
    @if($order->canContinuePayment())
    <div class="bg-gradient-to-r from-orange-500 to-red-500 text-white rounded-xl shadow-lg p-6 mb-6 animate-pulse">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-2xl"></i>
                </div>
            </div>
            <div class="ml-4 flex-1">
                <h3 class="text-xl font-bold mb-2">Payment Required</h3>
                <p class="mb-4">Your order is waiting for payment. Complete your payment to process this order.</p>
                
                @if($order->isPaymentExpired())
                    <div class="bg-red-900 bg-opacity-50 rounded-lg p-3 mb-4">
                        <i class="fas fa-clock mr-2"></i>
                        Payment link has expired (24 hours). Please contact support or create a new order.
                    </div>
                @else
                    <div class="flex flex-col sm:flex-row gap-3">
                        <a href="{{ $order->getPaymentUrl() }}" 
                        target="_blank"
                        class="flex-1 bg-white text-orange-600 px-6 py-3 rounded-lg font-bold text-center hover:bg-gray-100 transition">
                            <i class="fas fa-credit-card mr-2"></i>Continue Payment Now
                        </a>
                        
                        <form action="{{ route('orders.check-status', $order->id) }}" method="POST" class="flex-1">
                            @csrf
                            <button type="submit" 
                                    class="w-full bg-white bg-opacity-20 border-2 border-white px-6 py-3 rounded-lg font-bold hover:bg-opacity-30 transition">
                                <i class="fas fa-sync mr-2"></i>Refresh Status
                            </button>
                        </form>
                    </div>
                    
                    <p class="text-sm mt-3 opacity-90">
                        <i class="fas fa-info-circle mr-1"></i>
                        Payment link expires in {{ $order->created_at->addHours(24)->diffForHumans() }}
                    </p>
                @endif
            </div>
        </div>
    </div>
    @endif

    <!-- Show success message if paid -->
    @if($order->payment_status == 'paid')
    <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-lg">
        <div class="flex items-center">
            <i class="fas fa-check-circle text-green-500 text-xl mr-3"></i>
            <div>
                <p class="font-semibold text-green-800">Payment Confirmed</p>
                <p class="text-green-700 text-sm">Your payment has been received and your order is being processed.</p>
            </div>
        </div>
    </div>
    @endif
        <!-- Order Progress -->
        <div class="mb-8">
            <h3 class="font-semibold text-lg mb-4">Order Progress</h3>
            <div class="flex items-center justify-between relative">
                <!-- Progress Line -->
                <div class="absolute top-1/2 left-0 right-0 h-1 bg-gray-200 -translate-y-1/2 z-0"></div>
                
                <!-- Steps -->
                @php
                    $steps = [
                        'ordered' => ['icon' => 'fa-shopping-cart', 'label' => 'Ordered', 'date' => $order->created_at],
                        'processing' => ['icon' => 'fa-cog', 'label' => 'Processing'],
                        'shipped' => ['icon' => 'fa-shipping-fast', 'label' => 'Shipped'],
                        'delivered' => ['icon' => 'fa-check-circle', 'label' => 'Delivered']
                    ];
                    
                    $statusIndex = array_search($order->status, ['pending', 'processing', 'completed']);
                    $statusIndex = $statusIndex !== false ? $statusIndex : 0;
                @endphp
                
                @foreach($steps as $key => $step)
                <div class="flex flex-col items-center relative z-10">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center mb-2
                        @if($loop->index <= $statusIndex) bg-matcha-green text-white
                        @else bg-gray-200 text-gray-500 @endif">
                        <i class="fas {{ $step['icon'] }}"></i>
                    </div>
                    <span class="text-sm font-medium @if($loop->index <= $statusIndex) text-matcha-green @else text-gray-500 @endif">
                        {{ $step['label'] }}
                    </span>
                    @if(isset($step['date']))
                    <span class="text-xs text-gray-500 mt-1">{{ $step['date']->format('d M') }}</span>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        
        <!-- Order Summary -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Shipping Address -->
            <div class="bg-gray-50 rounded-lg p-4">
                <h4 class="font-semibold mb-3">Shipping Address</h4>
                <div class="space-y-1 text-gray-700">
                    <p>{{ $order->shipping_address }}</p>
                    @if($order->notes)
                    <p class="mt-3">
                        <span class="font-medium">Notes:</span><br>
                        {{ $order->notes }}
                    </p>
                    @endif
                </div>
            </div>
            
            <!-- Payment Information -->
            <div class="bg-gray-50 rounded-lg p-4">
            <h4 class="font-semibold mb-3">Payment Information</h4>

            <div class="space-y-2 text-gray-700">
                <div class="flex justify-between">
                    <span>Payment Method:</span>
                    <span class="font-medium">
                        {{ ucfirst($order->payment_method) }}
                    </span>
                </div>

                <div class="flex justify-between">
                    <span>Payment Status:</span>

                    @if($order->payment_status === 'paid')
                        <span class="font-medium text-green-600">
                            <i class="fas fa-check-circle mr-1"></i>Paid
                        </span>
                    @elseif($order->payment_status === 'pending')
                        <span class="font-medium text-yellow-600">
                            <i class="fas fa-clock mr-1"></i>Pending
                        </span>
                    @else
                        <span class="font-medium text-red-600">
                            <i class="fas fa-times-circle mr-1"></i>Failed
                        </span>
                    @endif
                </div>

                @if(
                    $order->payment_status === 'paid' &&
                    $order->payment &&
                    $order->payment->transaction_id
                )
                <div class="flex justify-between">
                    <span>Transaction ID:</span>
                    <span class="font-mono text-sm">
                        {{ $order->payment->transaction_id }}
                    </span>
                </div>
                @endif
            </div>
            
            <!-- Order Summary -->
            <div class="bg-matcha-green rounded-lg p-4 text-white">
                <h4 class="font-semibold mb-3">Order Summary</h4>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span>Subtotal:</span>
                        <span>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Shipping:</span>
                        <span>FREE</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Tax:</span>
                        <span>Rp 0</span>
                    </div>
                    <div class="border-t border-white border-opacity-30 pt-2 mt-2">
                        <div class="flex justify-between font-bold text-lg">
                            <span>Total:</span>
                            <span>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Information Card -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
        <h2 class="text-xl font-bold mb-4 flex items-center">
            <i class="fas fa-credit-card text-matcha-green mr-2"></i>
            Payment Information
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-gray-50 rounded-lg p-4">
                <p class="text-sm text-gray-600 mb-1">Payment Method</p>
                <p class="font-semibold text-lg">{{ ucfirst($order->payment_method) }}</p>
            </div>
            
            <div class="bg-gray-50 rounded-lg p-4">
                <p class="text-sm text-gray-600 mb-1">Payment Status</p>
                <div class="flex items-center">
                    @if($order->payment_status == 'paid')
                        <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">
                            <i class="fas fa-check-circle mr-1"></i>Paid
                        </span>
                    @elseif($order->payment_status == 'pending')
                        <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm font-medium">
                            <i class="fas fa-clock mr-1"></i>Pending
                        </span>
                    @else
                        <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm font-medium">
                            <i class="fas fa-times-circle mr-1"></i>Failed
                        </span>
                    @endif
                </div>
            </div>
            
            @if($order->payment)
            <div class="bg-gray-50 rounded-lg p-4">
                <p class="text-sm text-gray-600 mb-1">Transaction ID</p>
                <p class="font-mono text-sm">{{ $order->payment->transaction_id }}</p>
            </div>
            
            <div class="bg-gray-50 rounded-lg p-4">
                <p class="text-sm text-gray-600 mb-1">Payment Type</p>
                <p class="font-semibold">{{ ucfirst(str_replace('_', ' ', $order->payment->payment_type)) }}</p>
            </div>
            @endif
        </div>
        
        @if($order->canContinuePayment())
        <div class="mt-4 p-4 bg-blue-50 rounded-lg border border-blue-200">
            <div class="flex items-start">
                <i class="fas fa-info-circle text-blue-500 mr-3 mt-1"></i>
                <div class="flex-1">
                    <p class="text-blue-800 font-medium mb-2">How to Complete Payment:</p>
                    <ol class="text-sm text-blue-700 space-y-1 list-decimal list-inside">
                        <li>Click the "Continue Payment Now" button above</li>
                        <li>You'll be redirected to Midtrans payment page</li>
                        <li>Choose your preferred payment method</li>
                        <li>Complete the payment process</li>
                        <li>You'll be redirected back to this page automatically</li>
                    </ol>
                    <p class="text-sm text-blue-600 mt-2">
                        <i class="fas fa-shield-alt mr-1"></i>
                        Secure payment powered by Midtrans
                    </p>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Order Items -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
        <h2 class="text-xl font-bold mb-6">Order Items ({{ $order->items->count() }})</h2>
        
        <div class="space-y-4">
            @foreach($order->items as $item)
            <div class="flex items-center border-b pb-4">
                <div class="w-20 h-20 rounded-lg overflow-hidden bg-gray-100 mr-4 flex-shrink-0">
                    @if($item->product->image && file_exists(public_path('storage/' . $item->product->image)))
                        <img src="{{ asset('storage/' . $item->product->image) }}" 
                             alt="{{ $item->product->name }}"
                             class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-green-100 to-green-50 flex items-center justify-center">
                            <i class="fas fa-leaf text-2xl text-green-300"></i>
                        </div>
                    @endif
                </div>
                
                <div class="flex-1">
                    <h3 class="font-semibold">{{ $item->product->name }}</h3>
                    <p class="text-sm text-gray-600 mb-1">{{ $item->product->description }}</p>
                    <div class="flex items-center">
                        <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded mr-2">
                            {{ str_replace('_', ' ', $item->product->category) }}
                        </span>
                        <span class="text-gray-500 text-sm">Quantity: {{ $item->quantity }}</span>
                    </div>
                </div>
                
                <div class="text-right">
                    <p class="font-bold text-lg matcha-text">
                        Rp {{ number_format($item->price, 0, ',', '.') }}
                    </p>
                    <p class="text-gray-500 text-sm">
                        Total: Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                    </p>
                    <a href="{{ route('products.show', $item->product_id) }}" 
                       class="inline-block mt-2 text-matcha-green hover:underline text-sm">
                        <i class="fas fa-eye mr-1"></i>View Product
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        
        <!-- Order Total -->
        <div class="mt-8 pt-6 border-t">
            <div class="flex justify-end">
                <div class="w-64 space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Subtotal:</span>
                        <span class="font-medium">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Shipping:</span>
                        <span class="font-medium">FREE</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Tax:</span>
                        <span class="font-medium">Rp 0</span>
                    </div>
                    <div class="border-t pt-2">
                        <div class="flex justify-between font-bold text-lg">
                            <span>Total:</span>
                            <span class="matcha-text">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Customer Support -->
    <div class="bg-gray-50 rounded-xl p-6">
        <h3 class="font-semibold text-lg mb-4">Need Help?</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="text-center">
                <div class="w-12 h-12 mx-auto mb-3 rounded-full bg-matcha-green flex items-center justify-center">
                    <i class="fas fa-phone text-white"></i>
                </div>
                <h4 class="font-medium mb-1">Call Us</h4>
                <p class="text-gray-600 text-sm">+81 123-456-789</p>
                <p class="text-gray-500 text-xs">Available 24/7</p>
            </div>
            <div class="text-center">
                <div class="w-12 h-12 mx-auto mb-3 rounded-full bg-matcha-green flex items-center justify-center">
                    <i class="fas fa-envelope text-white"></i>
                </div>
                <h4 class="font-medium mb-1">Email Support</h4>
                <p class="text-gray-600 text-sm">support@matchastore.com</p>
                <p class="text-gray-500 text-xs">Response within 24 hours</p>
            </div>
            <div class="text-center">
                <div class="w-12 h-12 mx-auto mb-3 rounded-full bg-matcha-green flex items-center justify-center">
                    <i class="fas fa-comments text-white"></i>
                </div>
                <h4 class="font-medium mb-1">Live Chat</h4>
                <p class="text-gray-600 text-sm">Chat with us online</p>
                <p class="text-gray-500 text-xs">Mon-Fri, 9AM-6PM</p>
            </div>
        </div>
    </div>
</div>

<script>
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

</script>
@endsection