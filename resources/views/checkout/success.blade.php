@extends('layouts.app')

@section('title', 'Payment Successful')

@section('content')
<div class="container mx-auto px-4 py-16">
    <div class="max-w-md mx-auto text-center">
        <div class="w-20 h-20 mx-auto mb-6 rounded-full bg-green-100 flex items-center justify-center">
            <i class="fas fa-check-circle text-4xl text-green-500"></i>
        </div>
        
        <h1 class="text-3xl font-bold mb-4">Payment Successful!</h1>
        <p class="text-gray-600 mb-8">
            Your order #{{ $order->order_number }} has been confirmed.<br>
            Total: Rp {{ number_format($order->total_amount, 0, ',', '.') }}
        </p>
        
        <div class="space-x-4">
            <a href="{{ route('orders.show', $order->id) }}" class="btn-matcha px-6 py-3 rounded-lg">
                View Order Details
            </a>
            <a href="{{ route('products.index') }}" class="px-6 py-3 border border-matcha-green text-matcha-green rounded-lg hover:bg-green-50">
                Continue Shopping
            </a>
        </div>
    </div>
</div>
@endsection