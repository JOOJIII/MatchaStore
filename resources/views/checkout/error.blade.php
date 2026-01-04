@extends('layouts.app')

@section('title', 'Payment Failed')

@section('content')
<div class="container mx-auto px-4 py-16">
    <div class="max-w-md mx-auto text-center">
        <div class="w-20 h-20 mx-auto mb-6 rounded-full bg-red-100 flex items-center justify-center">
            <i class="fas fa-times-circle text-4xl text-red-500"></i>
        </div>
        
        <h1 class="text-3xl font-bold mb-4">Payment Failed</h1>
        <p class="text-gray-600 mb-8">
            Unfortunately, your payment could not be processed.<br>
            Please try again or contact support.
        </p>
        
        <div class="space-x-4">
            <a href="{{ route('cart.index') }}" class="btn-matcha px-6 py-3 rounded-lg">
                Return to Cart
            </a>
            <a href="{{ route('home') }}" class="px-6 py-3 border border-matcha-green text-matcha-green rounded-lg hover:bg-green-50">
                Go to Home
            </a>
        </div>
    </div>
</div>
@endsection