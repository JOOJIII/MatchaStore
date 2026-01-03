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
            
            <div class="mt-4 md:mt-0">
                @if($order->status == 'pending')
                <form action="{{ route('orders.cancel', $order->id) }}" method="POST" class="inline">
                    @csrf
                    @method('PUT')
                    <button type="submit" 
                            onclick="return confirm('Are you sure you want to cancel this order?')"
                            class="px-6 py-2 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition font-medium">
                        <i class="fas fa-times mr-2"></i>Cancel Order
                    </button>
                </form>
                @endif
                
                <button onclick="reorder({{ $order->id }})" 
                        class="ml-2 px-6 py-2 btn-matcha rounded-lg font-medium">
                    <i class="fas fa-redo mr-2"></i>Reorder
                </button>
            </div>
        </div>
        
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
                        <span class="font-medium">{{ ucfirst($order->payment_method) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Payment Status:</span>
                        <span class="font-medium text-green-600">
                            <i class="fas fa-check-circle mr-1"></i>Paid
                        </span>
                    </div>
                    @if($order->payment)
                    <div class="flex justify-between">
                        <span>Transaction ID:</span>
                        <span class="font-mono text-sm">{{ $order->payment->transaction_id }}</span>
                    </div>
                    @endif
                </div>
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
            fetch(`/orders/${orderId}/reorder`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    // Update cart count in navbar
                    const cartCount = document.querySelector('.cart-count');
                    if (cartCount && data.cart_count) {
                        cartCount.textContent = data.cart_count;
                    }
                }
            })
            .catch(error => {
                alert('Error reordering');
            });
        }
    }
</script>
@endsection