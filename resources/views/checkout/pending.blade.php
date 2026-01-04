@extends('layouts.app')

@section('title', 'Payment Pending')

@section('content')
<div class="container mx-auto px-4 py-16">
    <div class="max-w-md mx-auto text-center">
        <div class="w-20 h-20 mx-auto mb-6 rounded-full bg-yellow-100 flex items-center justify-center">
            <i class="fas fa-clock text-4xl text-yellow-500"></i>
        </div>
        
        <h1 class="text-3xl font-bold mb-4">Payment Pending</h1>
        <p class="text-gray-600 mb-8">
            Your order #{{ $order->order_number }} is awaiting payment confirmation.<br>
            Please complete your payment to proceed.
        </p>
        
        <div class="space-x-4">
            <a href="{{ route('orders.show', $order->id) }}" class="btn-matcha px-6 py-3 rounded-lg">
                View Order Details
            </a>
        </div>
    </div>
</div>
@endsection