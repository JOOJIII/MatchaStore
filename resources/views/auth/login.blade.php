@extends('layouts.app')

@section('title', 'Login - Matcha Store')

@section('content')
<div class="container mx-auto px-4 py-16">
    <div class="max-w-md mx-auto">
        <div class="text-center mb-8">
            <i class="fas fa-leaf text-5xl matcha-text mb-4"></i>
            <h1 class="text-3xl font-bold">Welcome Back</h1>
            <p class="text-gray-600 mt-2">Sign in to your Matcha Store account</p>
        </div>
        
        <div class="bg-white rounded-2xl shadow-lg p-8">
            <form method="POST" action="{{ route('login') }}">
                @csrf
                
                <div class="mb-6">
                    <label class="block text-gray-700 mb-2">Email Address</label>
                    <input type="email" name="email" class="w-full border rounded-lg px-4 py-3 focus:outline-none focus:border-matcha-green" required autofocus>
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-6">
                    <label class="block text-gray-700 mb-2">Password</label>
                    <input type="password" name="password" class="w-full border rounded-lg px-4 py-3 focus:outline-none focus:border-matcha-green" required>
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-6">
                    <label class="flex items-center">
                        <input type="checkbox" name="remember" class="rounded">
                        <span class="ml-2 text-gray-700">Remember me</span>
                    </label>
                </div>
                
                <button type="submit" class="w-full btn-matcha py-3 rounded-lg text-lg font-semibold mb-6">
                    Sign In
                </button>
                
                <div class="text-center mb-6">
                    <a href="{{ route('password.request') }}" class="text-matcha-green hover:underline">Forgot your password?</a>
                </div>
                
                <div class="relative mb-6">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white text-gray-500">Or continue with</span>
                    </div>
                </div>
                
                <a href="{{ route('google.login') }}" class="flex items-center justify-center w-full border-2 border-gray-300 py-3 rounded-lg hover:bg-gray-50 transition mb-4">
                    <i class="fab fa-google text-red-500 mr-3"></i>
                    Sign in with Google
                </a>
                
                <div class="text-center mt-6">
                    <p class="text-gray-600">Don't have an account? 
                        <a href="{{ route('register') }}" class="text-matcha-green font-semibold hover:underline">Sign up</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
