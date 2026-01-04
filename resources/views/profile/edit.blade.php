@extends('layouts.app')

@section('title', 'My Profile - Matcha Store')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold matcha-text mb-2">My Profile</h1>
        <p class="text-gray-600">Manage your account information</p>
    </div>

    <!-- Success Messages -->
    @if(session('success'))
    <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-lg">
        <div class="flex items-center">
            <i class="fas fa-check-circle text-green-500 text-xl mr-3"></i>
            <span class="text-green-800">{{ session('success') }}</span>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Profile Stats -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="text-center mb-6">
                <div class="w-24 h-24 mx-auto mb-4 rounded-full bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center">
                    @if(auth()->user()->avatar)
                        <img src="{{ auth()->user()->avatar }}" alt="Avatar" class="w-full h-full rounded-full object-cover">
                    @else
                        <span class="text-3xl text-white font-bold">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </span>
                    @endif
                </div>
                <h3 class="text-xl font-bold">{{ auth()->user()->name }}</h3>
                <p class="text-gray-600">{{ auth()->user()->email }}</p>
                <span class="inline-block mt-2 px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm">
                    {{ ucfirst(auth()->user()->role) }}
                </span>
            </div>

            <div class="border-t pt-4 space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Member Since</span>
                    <span class="font-semibold">{{ auth()->user()->created_at->format('M Y') }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Total Orders</span>
                    <span class="font-semibold">{{ auth()->user()->orders()->count() }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Wishlist Items</span>
                    <span class="font-semibold">{{ auth()->user()->wishlists()->count() }}</span>
                </div>
            </div>

            <div class="mt-6 space-y-2">
                <a href="{{ route('orders.index') }}" class="block w-full text-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                    <i class="fas fa-shopping-bag mr-2"></i>My Orders
                </a>
                <a href="{{ route('wishlist.index') }}" class="block w-full text-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                    <i class="fas fa-heart mr-2"></i>Wishlist
                </a>
            </div>
        </div>

        <!-- Profile Forms -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Update Profile Information -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-bold mb-6 flex items-center">
                    <i class="fas fa-user text-matcha-green mr-2"></i>
                    Profile Information
                </h2>

                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Full Name</label>
                        <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" 
                               class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-matcha-green focus:border-transparent @error('name') border-red-500 @enderror" 
                               required>
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-700 font-medium mb-2">Email Address</label>
                        <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" 
                               class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-matcha-green focus:border-transparent @error('email') border-red-500 @enderror" 
                               required>
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="btn-matcha px-6 py-3 rounded-lg font-semibold">
                            <i class="fas fa-save mr-2"></i>Update Profile
                        </button>
                    </div>
                </form>
            </div>

            <!-- Change Password -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-bold mb-6 flex items-center">
                    <i class="fas fa-lock text-matcha-green mr-2"></i>
                    Change Password
                </h2>

                <form action="{{ route('profile.password') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Current Password</label>
                        <input type="password" name="current_password" 
                               class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-matcha-green focus:border-transparent @error('current_password') border-red-500 @enderror" 
                               required>
                        @error('current_password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">New Password</label>
                        <input type="password" name="password" 
                               class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-matcha-green focus:border-transparent @error('password') border-red-500 @enderror" 
                               required>
                        @error('password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-gray-500 text-sm mt-1">Must be at least 8 characters</p>
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-700 font-medium mb-2">Confirm New Password</label>
                        <input type="password" name="password_confirmation" 
                               class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-matcha-green focus:border-transparent" 
                               required>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="btn-matcha px-6 py-3 rounded-lg font-semibold">
                            <i class="fas fa-key mr-2"></i>Change Password
                        </button>
                    </div>
                </form>
            </div>

            <!-- Account Actions -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-bold mb-6 flex items-center">
                    <i class="fas fa-cog text-matcha-green mr-2"></i>
                    Account Settings
                </h2>

                <div class="space-y-4">
                    <div class="flex justify-between items-center p-4 border rounded-lg">
                        <div>
                            <h3 class="font-semibold">Email Notifications</h3>
                            <p class="text-gray-600 text-sm">Receive updates about your orders</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" checked class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-matcha-green"></div>
                        </label>
                    </div>

                    <div class="flex justify-between items-center p-4 border rounded-lg">
                        <div>
                            <h3 class="font-semibold">Marketing Emails</h3>
                            <p class="text-gray-600 text-sm">Receive promotional offers and news</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-matcha-green"></div>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection