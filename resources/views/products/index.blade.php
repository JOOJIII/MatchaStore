@extends('layouts.app')

@section('title', 'Products - Matcha Store')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Hero Section -->
    <div class="hero-gradient text-white rounded-2xl p-8 mb-12">
        <h1 class="font-playfair text-4xl md:text-5xl font-bold mb-4">Premium Matcha Collection</h1>
        <p class="text-xl mb-6">Discover the finest Japanese matcha powders, teas, and accessories</p>
        <a href="#products" class="text-matcha-green px-6 py-3 rounded-lg font-semibold text-lg hover:text-green-300 transition">Shop Now</a>
    </div>
    
    <!-- Featured Products -->
    @if($featured->count() > 0)
    <div class="mb-12">
        <h2 class="text-3xl font-bold matcha-text mb-6">Featured Products</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($featured as $product)
            <div class="bg-white rounded-xl shadow-md overflow-hidden card-hover">
                <!-- PERBAIKAN: Ganti icon dengan gambar produk -->
                <div class="h-48 relative overflow-hidden">
                    @if($product->image && file_exists(public_path('storage/' . $product->image)))
                        <img src="{{ asset('storage/' . $product->image) }}" 
                             alt="{{ $product->name }}"
                             class="w-full h-full object-cover hover:scale-110 transition duration-300"
                             onerror="this.src='https://via.placeholder.com/300x200/2D5A27/FFFFFF?text=Matcha'">
                    @else
                        <!-- Fallback jika gambar tidak ada -->
                        <div class="w-full h-full bg-gradient-to-br from-green-100 to-green-50 flex items-center justify-center">
                            <i class="fas fa-leaf text-4xl text-green-300"></i>
                        </div>
                    @endif
                    
                    @if($product->is_featured)
                        <span class="absolute top-2 left-2 bg-red-500 text-white text-xs px-2 py-1 rounded">Featured</span>
                    @endif
                </div>
                <div class="p-4">
                    <h3 class="font-semibold text-lg mb-2">{{ $product->name }}</h3>
                    <p class="text-gray-600 text-sm mb-3">{{ Str::limit($product->description, 60) }}</p>
                    <div class="flex justify-between items-center">
                        <span class="text-xl font-bold matcha-text">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                        <a href="{{ route('products.show', $product->id) }}" class="btn-matcha px-4 py-2 rounded-lg text-sm">View Details</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
    
    <!-- All Products -->
    <div id="products">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-3xl font-bold matcha-text">All Products</h2>
            <div class="flex space-x-2">
                <select class="border rounded-lg px-4 py-2">
                    <option>Sort by: Featured</option>
                    <option>Price: Low to High</option>
                    <option>Price: High to Low</option>
                </select>
            </div>
        </div>
        
        @if($products->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($products as $product)
            <div class="bg-white rounded-xl shadow-md overflow-hidden card-hover">
                <!-- PERBAIKAN: Ganti icon dengan gambar produk -->
                <div class="h-56 relative overflow-hidden">
                    @if($product->image && file_exists(public_path('storage/' . $product->image)))
                        <img src="{{ asset('storage/' . $product->image) }}" 
                             alt="{{ $product->name }}"
                             class="w-full h-full object-cover hover:scale-110 transition duration-300"
                             onerror="this.src='https://via.placeholder.com/300x200/2D5A27/FFFFFF?text=Matcha'">
                    @else
                        <!-- Fallback jika gambar tidak ada -->
                        <div class="w-full h-full bg-gradient-to-br from-green-100 to-green-50 flex items-center justify-center">
                            <i class="fas fa-leaf text-5xl text-green-300"></i>
                        </div>
                    @endif
                    
                    @if($product->is_featured)
                        <span class="absolute top-2 right-2 bg-red-500 text-white text-xs px-2 py-1 rounded">Featured</span>
                    @endif
                </div>
                <div class="p-4">
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="font-semibold text-lg">{{ $product->name }}</h3>
                        <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded">{{ str_replace('_', ' ', $product->category) }}</span>
                    </div>
                    <p class="text-gray-600 text-sm mb-3">{{ Str::limit($product->description, 80) }}</p>
                    
                    <div class="flex items-center mb-3">
                        <div class="flex text-yellow-400">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= floor($product->rating))
                                    <i class="fas fa-star"></i>
                                @elseif($i - 0.5 <= $product->rating)
                                    <i class="fas fa-star-half-alt"></i>
                                @else
                                    <i class="far fa-star"></i>
                                @endif
                            @endfor
                        </div>
                        <span class="text-gray-500 text-sm ml-2">({{ $product->total_reviews }})</span>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <div>
                            <span class="text-2xl font-bold matcha-text">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                            @if($product->stock < 10)
                                <p class="text-red-500 text-xs">Only {{ $product->stock }} left!</p>
                            @else
                                <p class="text-green-500 text-xs">In Stock</p>
                            @endif
                        </div>
                        <div class="flex space-x-2">
                            @auth
                            <form action="{{ route('wishlist.toggle', $product->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="bg-gray-100 p-2 rounded-lg hover:bg-gray-200" title="Add to Wishlist">
                                    <i class="far fa-heart"></i>
                                </button>
                            </form>
                            @endauth
                            <a href="{{ route('products.show', $product->id) }}" class="btn-matcha px-4 py-2 rounded-lg">View</a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <!-- Pagination -->
        <div class="mt-8">
            {{ $products->links() }}
        </div>
        @else
        <div class="text-center py-12">
            <i class="fas fa-leaf text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-600 mb-2">No products found</h3>
            <p class="text-gray-500">Check back soon for our matcha collection!</p>
        </div>
        @endif
    </div>
    
    <!-- Categories -->
    <div class="mt-12">
        <h2 class="text-3xl font-bold matcha-text mb-6">Shop by Category</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="{{ route('products.index', ['category' => 'matcha_powder']) }}" class="bg-matcha-cream p-6 rounded-xl text-center hover:shadow-lg transition">
                <i class="fas fa-mortar-pestle text-3xl matcha-text mb-2"></i>
                <h3 class="font-semibold">Matcha Powder</h3>
            </a>
            <a href="{{ route('products.index', ['category' => 'matcha_tea']) }}" class="bg-matcha-cream p-6 rounded-xl text-center hover:shadow-lg transition">
                <i class="fas fa-mug-hot text-3xl matcha-text mb-2"></i>
                <h3 class="font-semibold">Matcha Tea</h3>
            </a>
            <a href="{{ route('products.index', ['category' => 'matcha_dessert']) }}" class="bg-matcha-cream p-6 rounded-xl text-center hover:shadow-lg transition">
                <i class="fas fa-ice-cream text-3xl matcha-text mb-2"></i>
                <h3 class="font-semibold">Desserts</h3>
            </a>
            <a href="{{ route('products.index', ['category' => 'matcha_accessories']) }}" class="bg-matcha-cream p-6 rounded-xl text-center hover:shadow-lg transition">
                <i class="fas fa-utensils text-3xl matcha-text mb-2"></i>
                <h3 class="font-semibold">Accessories</h3>
            </a>
        </div>
    </div>
</div>

<script>
    // Tambahkan script untuk menangani gambar error
    document.addEventListener('DOMContentLoaded', function() {
        const images = document.querySelectorAll('img');
        images.forEach(img => {
            img.addEventListener('error', function() {
                this.onerror = null; // Mencegah loop infinite
                this.src = 'https://via.placeholder.com/300x200/2D5A27/FFFFFF?text=Matcha';
            });
        });
    });
</script>
@endsection