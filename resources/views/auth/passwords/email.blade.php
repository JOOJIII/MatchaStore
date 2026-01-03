@extends('layouts.app')

@section('title', 'Reset Password - Matcha Store')

@section('content')
<div class="container mx-auto px-4 py-16">
    <div class="max-w-md mx-auto">
        <div class="text-center mb-8">
            <i class="fas fa-key text-5xl matcha-text mb-4"></i>
            <h1 class="text-3xl font-bold">Reset Password</h1>
            <p class="text-gray-600 mt-2">Enter your email to receive a password reset link</p>
        </div>
        
        <div class="bg-white rounded-2xl shadow-lg p-8">
            @if (session('status'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                
                <div class="mb-6">
                    <label class="block text-gray-700 mb-2">Email Address</label>
                    <input type="email" name="email" class="w-full border rounded-lg px-4 py-3 focus:outline-none focus:border-matcha-green" required autofocus>
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <button type="submit" class="w-full btn-matcha py-3 rounded-lg text-lg font-semibold mb-6">
                    Send Password Reset Link
                </button>
                
                <div class="text-center">
                    <a href="{{ route('login') }}" class="text-matcha-green hover:underline">Back to Login</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
