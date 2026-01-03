@extends('layouts.app')

@section('title', 'Feedback - Matcha Store')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="max-w-3xl mx-auto">
        <div class="text-center mb-10">
            <i class="fas fa-comments text-5xl matcha-text mb-4"></i>
            <h1 class="text-3xl font-bold mb-2">Send Us Feedback</h1>
            <p class="text-gray-600">We value your opinion! Please share your experience with us.</p>
        </div>

        @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                <span>{{ session('success') }}</span>
            </div>
        </div>
        @endif

        <div class="bg-white rounded-2xl shadow-lg p-8">
            <form method="POST" action="{{ route('feedback.store') }}">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-gray-700 mb-2">Your Name *</label>
                        <input type="text" name="name" 
                               value="{{ old('name', auth()->user()->name) }}"
                               class="w-full border rounded-lg px-4 py-3 focus:outline-none focus:border-matcha-green" 
                               required>
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 mb-2">Email Address *</label>
                        <input type="email" name="email" 
                               value="{{ old('email', auth()->user()->email) }}"
                               class="w-full border rounded-lg px-4 py-3 focus:outline-none focus:border-matcha-green" 
                               required>
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 mb-2">Subject *</label>
                    <input type="text" name="subject" 
                           value="{{ old('subject') }}"
                           class="w-full border rounded-lg px-4 py-3 focus:outline-none focus:border-matcha-green" 
                           placeholder="What is your feedback about?" 
                           required>
                    @error('subject')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 mb-2">Your Message *</label>
                    <textarea name="message" rows="6" 
                              class="w-full border rounded-lg px-4 py-3 focus:outline-none focus:border-matcha-green" 
                              placeholder="Please share your thoughts, suggestions, or any issues you've encountered..." 
                              required>{{ old('message') }}</textarea>
                    @error('message')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="flex items-center">
                        <input type="checkbox" name="newsletter" class="rounded mr-2">
                        <span class="text-gray-700">Subscribe to our newsletter for updates and offers</span>
                    </label>
                </div>

                <div class="flex justify-between items-center">
                    <a href="{{ url()->previous() }}" class="text-gray-600 hover:text-matcha-green">
                        <i class="fas fa-arrow-left mr-2"></i> Back
                    </a>
                    
                    <button type="submit" class="btn-matcha px-8 py-3 rounded-lg text-lg font-semibold">
                        <i class="fas fa-paper-plane mr-2"></i> Send Feedback
                    </button>
                </div>
            </form>
        </div>

        <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-matcha-cream p-6 rounded-xl text-center">
                <i class="fas fa-clock text-3xl matcha-text mb-3"></i>
                <h3 class="font-semibold mb-2">Quick Response</h3>
                <p class="text-gray-600 text-sm">We typically respond within 24 hours</p>
            </div>
            
            <div class="bg-matcha-cream p-6 rounded-xl text-center">
                <i class="fas fa-star text-3xl matcha-text mb-3"></i>
                <h3 class="font-semibold mb-2">Your Opinion Matters</h3>
                <p class="text-gray-600 text-sm">Help us improve our products and services</p>
            </div>
            
            <div class="bg-matcha-cream p-6 rounded-xl text-center">
                <i class="fas fa-shield-alt text-3xl matcha-text mb-3"></i>
                <h3 class="font-semibold mb-2">Privacy Protected</h3>
                <p class="text-gray-600 text-sm">Your feedback is confidential and secure</p>
            </div>
        </div>
    </div>
</div>
@endsection
