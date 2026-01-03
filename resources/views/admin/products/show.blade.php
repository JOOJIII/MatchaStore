@extends('admin.layout')

@section('title', $product->name)
@section('header', 'Product Details')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Breadcrumb -->
    <nav class="flex mb-6" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('admin.dashboard') }}" class="text-gray-700 hover:text-green-700">
                    Dashboard
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                    <a href="{{ route('admin.products.index') }}" class="text-gray-700 hover:text-green-700">
                        Products
                    </a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                    <span class="text-gray-500">{{ Str::limit($product->name, 20) }}</span>
                </div>
            </li>
        </ol>
    </nav>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column - Product Info -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <!-- Product Header -->
                <div class="px-6 py-4 border-b bg-green-50">
                    <div class="flex justify-between items-center">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-800">{{ $product->name }}</h1>
                            <div class="flex items-center space-x-3 mt-2">
                                <span class="px-3 py-1 text-sm rounded-full bg-green-100 text-green-800">
                                    {{ $product->grade }} Grade
                                </span>
                                @if($product->is_organic)
                                    <span class="px-3 py-1 text-sm rounded-full bg-emerald-100 text-emerald-800">
                                        <i class="fas fa-leaf mr-1"></i> Organic
                                    </span>
                                @endif
                                @if($product->is_featured)
                                    <span class="px-3 py-1 text-sm rounded-full bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-star mr-1"></i> Featured
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-3xl font-bold text-green-700">{{ $product->formatted_price }}</p>
                            <p class="text-sm text-gray-600">per {{ $product->weight_grams }}g</p>
                        </div>
                    </div>
                </div>
                
                <!-- Product Details -->
                <div class="p-6">
                    <!-- Image -->
                    <div class="mb-8">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" 
                                 alt="{{ $product->name }}"
                                 class="w-full h-64 object-cover rounded-lg shadow-md">
                        @else
                            <div class="w-full h-64 bg-green-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-leaf text-green-400 text-6xl"></i>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Description -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-800 mb-3">Description</h3>
                        <div class="prose max-w-none">
                            <p class="text-gray-700 whitespace-pre-line">{{ $product->description }}</p>
                        </div>
                    </div>
                    
                    <!-- Product Specs -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-500">Category</p>
                            <p class="font-semibold">{{ $product->category->name ?? 'N/A' }}</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-500">Origin</p>
                            <p class="font-semibold">{{ $product->origin ?? 'N/A' }}</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-500">Stock</p>
                            <p class="font-semibold {{ $product->stock < 10 ? 'text-red-600' : 'text-green-600' }}">
                                {{ $product->stock }} units
                            </p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-500">Rating</p>
                            <p class="font-semibold">
                                <i class="fas fa-star text-yellow-500"></i>
                                {{ number_format($product->average_rating, 1) }}/5.0
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Comments Section -->
            @if($product->comments->count() > 0)
            <div class="mt-8 bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b">
                    <h3 class="text-lg font-semibold text-gray-800">Customer Reviews</h3>
                    <p class="text-sm text-gray-600">{{ $product->comments->count() }} reviews</p>
                </div>
                <div class="p-6">
                    <div class="space-y-4 max-h-96 overflow-y-auto">
                        @foreach($product->comments as $comment)
                        <div class="border-b pb-4 last:border-0">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <p class="font-semibold">{{ $comment->user->name }}</p>
                                    <div class="flex items-center">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star {{ $i <= $comment->rating ? 'text-yellow-500' : 'text-gray-300' }} text-sm"></i>
                                        @endfor
                                    </div>
                                </div>
                                <span class="text-sm text-gray-500">{{ $comment->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-gray-700">{{ $comment->content }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>
        
        <!-- Right Column - Actions & Stats -->
        <div class="space-y-6">
            <!-- Action Buttons -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Actions</h3>
                <div class="space-y-3">
                    <a href="{{ route('admin.products.edit', $product) }}" 
                       class="w-full flex items-center justify-center px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                        <i class="fas fa-edit mr-2"></i> Edit Product
                    </a>
                    
                    <form action="{{ route('admin.products.destroy', $product) }}" 
                          method="POST" 
                          onsubmit="return confirm('Are you sure you want to delete this product? This action cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="w-full flex items-center justify-center px-4 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                            <i class="fas fa-trash mr-2"></i> Delete Product
                        </button>
                    </form>
                    
                    <a href="{{ route('products.show', $product->id) }}" 
                       target="_blank"
                       class="w-full flex items-center justify-center px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-eye mr-2"></i> View on Store
                    </a>
                </div>
            </div>
            
            <!-- Product Stats -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Product Statistics</h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-500">Product ID</p>
                        <p class="font-mono text-sm">{{ $product->id }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Slug</p>
                        <p class="font-mono text-sm">{{ $product->slug }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Created</p>
                        <p class="font-semibold">{{ $product->created_at->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Last Updated</p>
                        <p class="font-semibold">{{ $product->updated_at->format('M d, Y H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Total Reviews</p>
                        <p class="font-semibold">{{ $product->comments->count() }}</p>
                    </div>
                </div>
            </div>
            
            <!-- Stock Alert -->
            @if($product->stock < 10)
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700">
                            <strong>Low Stock Alert!</strong> Only {{ $product->stock }} units remaining.
                        </p>
                    </div>
                </div>
            </div>
            @endif
            
            @if($product->stock == 0)
            <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-times-circle text-red-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700">
                            <strong>Out of Stock!</strong> This product is currently unavailable.
                        </p>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection