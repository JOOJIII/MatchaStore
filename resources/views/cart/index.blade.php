@extends('layouts.app')

@section('title', 'Shopping Cart - Matcha Store')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Shopping Cart</h1>
    
    @if($cartItems->count() > 0)
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Cart Items -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xshadow overflow-hidden">
                @foreach($cartItems as $item)
                <div class="p-6 border-b flex items-center">
                    <div class="h-24 w-24 bg-gray-100 rounded-lg flex items-center justify-center mr-6">
                        <i class="fas fa-leaf text-3xl text-gray-400"></i>
                    </div>
                    <div class="flex-grow">
                        <h3 class="font-semibold text-lg">{{ $item->product->name }}</h3>
                        <p class="text-gray-600 text-sm mb-2">${{ number_format($item->product->price, 2) }} each</p>
                        <div class="flex items-center">
                            <div class="flex items-center border rounded-lg mr-4">
                                <button class="px-3 py-1" onclick="updateQuantity({{ $item->id }}, -1)">-</button>
                                <span class="px-3 py-1 border-x">{{ $item->quantity }}</span>
                                <button class="px-3 py-1" onclick="updateQuantity({{ $item->id }}, 1)">+</button>
                            </div>
                            <button onclick="removeItem({{ $item->id }})" class="text-red-500 hover:text-red-700">
                                <i class="fas fa-trash"></i> Remove
                            </button>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-2xl font-bold matcha-text mb-2">
                            ${{ number_format($item->product->price * $item->quantity, 2) }}
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        
        <!-- Order Summary -->
        <div>
            <div class="bg-white rounded-xl shadow p-6">
                <h2 class="text-xl font-bold mb-4">Order Summary</h2>
                <div class="space-y-3 mb-6">
                    <div class="flex justify-between">
                        <span>Subtotal</span>
                        <span>${{ number_format($total, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Shipping</span>
                        <span class="text-green-600">Free</span>
                    </div>
                    <div class="border-t pt-3">
                        <div class="flex justify-between text-lg font-bold">
                            <span>Total</span>
                            <span class="matcha-text">${{ number_format($total, 2) }}</span>
                        </div>
                    </div>
                </div>
                
                <a href="{{ route('checkout.index') }}" class="block w-full btn-matcha text-center py-3 rounded-lg text-lg font-semibold mb-4">
                    Proceed to Checkout
                </a>
                
                <a href="{{ route('products.index') }}" class="block w-full border-2 border-matcha-green text-matcha-green text-center py-3 rounded-lg hover:bg-matcha-green hover:text-white transition">
                    Continue Shopping
                </a>
            </div>
        </div>
    </div>
    @else
    <div class="text-center py-16">
        <i class="fas fa-shopping-cart text-6xl text-gray-300 mb-4"></i>
        <h2 class="text-2xl font-semibold text-gray-600 mb-2">Your cart is empty</h2>
        <p class="text-gray-500 mb-6">Add some delicious matcha products to your cart!</p>
        <a href="{{ route('products.index') }}" class="btn-matcha px-8 py-3 rounded-lg text-lg">Browse Products</a>
    </div>
    @endif
</div>

<script>
function updateQuantity(itemId, change) {
    const formData = new FormData();
    formData.append('_token', '{{ csrf_token() }}');
    formData.append('_method', 'PUT');
    
    fetch(`/cart/update/${itemId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'X-HTTP-Method-Override': 'PUT'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
}

function removeItem(itemId) {
    if (confirm('Are you sure you want to remove this item?')) {
        fetch(`/cart/remove/${itemId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        });
    }
}
</script>
@endsection
