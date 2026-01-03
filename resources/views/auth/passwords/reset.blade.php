@extends('layouts.app')

@section('title', 'Reset Password - Matcha Store')

@section('content')
<div class="container mx-auto px-4 py-16">
    <div class="max-w-md mx-auto">
        <div class="text-center mb-8">
            <i class="fas fa-lock text-5xl matcha-text mb-4"></i>
            <h1 class="text-3xl font-bold">Set New Password</h1>
            <p class="text-gray-600 mt-2">Enter your new password below</p>
        </div>
        
        <div class="bg-white rounded-2xl shadow-lg p-8">
            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                
                <div class="mb-6">
                    <label class="block text-gray-700 mb-2">Email Address</label>
                    <input type="email" name="email" value="{{ $email ?? old('email') }}" class="w-full border rounded-lg px-4 py-3 focus:outline-none focus:border-matcha-green" required autofocus>
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-6">
                    <label class="block text-gray-700 mb-2">New Password</label>
                    <input type="password" name="password" class="w-full border rounded-lg px-4 py-3 focus:outline-none focus:border-matcha-green" required>
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-6">
                    <label class="block text-gray-700 mb-2">Confirm New Password</label>
                    <input type="password" name="password_confirmation" class="w-full border rounded-lg px-4 py-3 focus:outline-none focus:border-matcha-green" required>
                </div>
                
                <button type="submit" class="w-full btn-matcha py-3 rounded-lg text-lg font-semibold">
                    Reset Password
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
