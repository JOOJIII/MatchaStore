@extends('layouts.app')

@section('title', 'Confirm Password - Matcha Store')

@section('content')
<div class="container mx-auto px-4 py-16">
    <div class="max-w-md mx-auto">
        <div class="text-center mb-8">
            <i class="fas fa-shield-alt text-5xl matcha-text mb-4"></i>
            <h1 class="text-3xl font-bold">Confirm Password</h1>
            <p class="text-gray-600 mt-2">Please confirm your password before continuing</p>
        </div>
        
        <div class="bg-white rounded-2xl shadow-lg p-8">
            <form method="POST" action="{{ route('password.confirm') }}">
                @csrf
                
                <div class="mb-6">
                    <label class="block text-gray-700 mb-2">Password</label>
                    <input type="password" name="password" class="w-full border rounded-lg px-4 py-3 focus:outline-none focus:border-matcha-green" required>
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <button type="submit" class="w-full btn-matcha py-3 rounded-lg text-lg font-semibold">
                    Confirm Password
                </button>
                
                @if (Route::has('password.request'))
                <div class="text-center mt-6">
                    <a href="{{ route('password.request') }}" class="text-matcha-green hover:underline">Forgot Your Password?</a>
                </div>
                @endif
            </form>
        </div>
    </div>
</div>
@endsection
