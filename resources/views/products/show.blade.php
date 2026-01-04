@extends('layouts.app')

@section('title', $product->name . ' - Matcha Store')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <nav class="flex mb-6" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('home') }}" class="text-gray-600 hover:text-matcha-green">Home</a>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                    <a href="{{ route('products.index') }}" class="text-gray-600 hover:text-matcha-green">Products</a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                    <span class="text-matcha-green font-semibold">{{ $product->name }}</span>
                </div>
            </li>
        </ol>
    </nav>
    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
        <!-- Product Image -->
        <div class="bg-white rounded-2xl shadow-lg p-8">
            <!-- PERBAIKAN: Ganti icon dengan gambar produk -->
            <div class="h-96 rounded-xl overflow-hidden flex items-center justify-center">
                @if($product->image && file_exists(public_path('storage/' . $product->image)))
                    <img src="{{ asset('storage/' . $product->image) }}" 
                         alt="{{ $product->name }}"
                         class="w-full h-full object-cover"
                         onerror="this.src='https://via.placeholder.com/600x600/2D5A27/FFFFFF?text=Matcha'">
                @else
                    <!-- Fallback jika gambar tidak ada -->
                    <div class="w-full h-full bg-gradient-to-br from-green-100 to-green-50 flex items-center justify-center">
                        <i class="fas fa-leaf text-8xl text-green-300"></i>
                    </div>
                @endif
            </div>
            
        </div>
        
        <!-- Product Details -->
        <div>
            <h1 class="text-3xl font-bold mb-2">{{ $product->name }}</h1>
            <div class="flex items-center mb-4">
                <div class="flex text-yellow-400 mr-2">
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
                <span class="text-gray-600">({{ $product->total_reviews }} reviews)</span>
                <span class="mx-4 text-gray-400">|</span>
                <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-sm">{{ str_replace('_', ' ', $product->category) }}</span>
            </div>
            
            <p class="text-gray-700 text-lg mb-6">{{ $product->description }}</p>
            
            <div class="mb-6">
                <span class="text-4xl font-bold matcha-text">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                @if($product->stock > 0)
                <span class="ml-4 text-green-600">
                    <i class="fas fa-check-circle"></i> In Stock ({{ $product->stock }} available)
                </span>
                @else
                <span class="ml-4 text-red-600">
                    <i class="fas fa-times-circle"></i> Out of Stock
                </span>
                @endif
            </div>
            
            @if($product->stock > 0)
            <form action="{{ route('cart.add', $product->id) }}" method="POST" class="mb-6">
                @csrf
                <div class="flex items-center space-x-4 mb-4">
                    <div class="flex items-center border rounded-lg">
                        <button type="button" class="px-3 py-2 text-gray-600 hover:text-matcha-green" onclick="decreaseQuantity()">-</button>
                        <input type="number" name="quantity" id="quantity" value="1" min="1" max="{{ $product->stock }}" class="w-16 text-center py-2 border-x focus:outline-none">
                        <button type="button" class="px-3 py-2 text-gray-600 hover:text-matcha-green" onclick="increaseQuantity()">+</button>
                    </div>
                    <button type="submit" class="btn-matcha px-8 py-3 rounded-lg text-lg font-semibold flex items-center">
                        <i class="fas fa-shopping-cart mr-2"></i> Add to Cart
                    </button>
                    @auth
                    <form action="{{ route('wishlist.toggle', $product->id) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="border-2 border-matcha-green text-matcha-green px-4 py-3 rounded-lg hover:bg-matcha-green hover:text-white transition">
                            <i class="{{ $product->in_wishlist ? 'fas' : 'far' }} fa-heart"></i>
                        </button>
                    </form>
                    @endauth
                </div>
            </form>
            @endif
            
            <div class="border-t pt-6">
                <h3 class="font-semibold text-lg mb-2">Product Details</h3>
                <ul class="space-y-2 text-gray-600">
                    <li class="flex">
                        <span class="w-32 font-medium">Category:</span>
                        <span>{{ str_replace('_', ' ', $product->category) }}</span>
                    </li>
                    <li class="flex">
                        <span class="w-32 font-medium">Stock:</span>
                        <span>{{ $product->stock }} units</span>
                    </li>
                    <li class="flex">
                        <span class="w-32 font-medium">Rating:</span>
                        <span>{{ $product->rating }}/5 ({{ $product->total_reviews }} reviews)</span>
                    </li>
                    <li class="flex">
                        <span class="w-32 font-medium">Added:</span>
                        <span>{{ $product->created_at->format('d M Y') }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    
    <!-- Reviews Section -->
    <div class="mb-12">
        <h2 class="text-2xl font-bold mb-6">Customer Reviews</h2>
        
        <!-- Add Review Form -->
        @auth
        <div class="bg-white rounded-xl shadow p-6 mb-6">
            <h3 class="font-semibold text-lg mb-4">Write a Review</h3>
            <form action="{{ route('products.comment', $product->id) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block mb-2">Rating</label>
                    <div class="flex space-x-1">
                        @for($i = 1; $i <= 5; $i++)
                        <input type="radio" name="rating" value="{{ $i }}" id="rating{{ $i }}" class="hidden" {{ $i == 5 ? 'checked' : '' }}>
                        <label for="rating{{ $i }}" class="text-2xl cursor-pointer">
                            <i class="far fa-star text-gray-300 hover:text-yellow-400"></i>
                        </label>
                        @endfor
                    </div>
                </div>
                <div class="mb-4">
                    <textarea name="content" rows="3" class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-matcha-green focus:border-transparent" minlength="5" placeholder="Share your experience with this product..." required></textarea>
                </div>
                <button type="submit" class="btn-matcha px-6 py-2 rounded-lg">Submit Review</button>
            </form>
        </div>
        @else
        <div class="bg-gray-50 rounded-xl p-6 text-center mb-6">
            <p class="text-gray-600 mb-2">Please <a href="{{ route('login') }}" class="text-matcha-green font-semibold">login</a> to leave a review.</p>
        </div>
        @endauth
        
        <!-- Reviews List -->
        <div class="space-y-6">
            @forelse($product->comments as $comment)
            <div class="bg-white rounded-xl shadow p-6">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <h4 class="font-semibold">{{ $comment->user->name }}</h4>
                        <div class="flex text-yellow-400 text-sm">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= ($comment->rating ?? 5))
                                    <i class="fas fa-star"></i>
                                @else
                                    <i class="far fa-star"></i>
                                @endif
                            @endfor
                        </div>
                    </div>
                    <span class="text-gray-500 text-sm">{{ $comment->created_at->format('M d, Y') }}</span>
                </div>
                <p class="text-gray-700">{{ $comment->content }}</p>
            </div>
            @empty
            <div class="text-center py-8">
                <i class="fas fa-comment text-4xl text-gray-300 mb-3"></i>
                <p class="text-gray-600">No reviews yet. Be the first to review!</p>
            </div>
            @endforelse
        </div>
    </div>
    
    <!-- Related Products -->
    @if($related->count() > 0)
    <div>
        <h2 class="text-2xl font-bold mb-6">Related Products</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($related as $relatedProduct)
            <a href="{{ route('products.show', $relatedProduct->id) }}" class="bg-white rounded-xl shadow-md overflow-hidden card-hover">
                <!-- PERBAIKAN: Ganti icon dengan gambar produk -->
                <div class="h-48 relative overflow-hidden">
                    @if($relatedProduct->image && file_exists(public_path('storage/' . $relatedProduct->image)))
                        <img src="{{ asset('storage/' . $relatedProduct->image) }}" 
                             alt="{{ $relatedProduct->name }}"
                             class="w-full h-full object-cover hover:scale-110 transition duration-300"
                             onerror="this.src='https://via.placeholder.com/300x200/2D5A27/FFFFFF?text=Matcha'">
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-green-100 to-green-50 flex items-center justify-center">
                            <i class="fas fa-leaf text-4xl text-green-300"></i>
                        </div>
                    @endif
                    
                    @if($relatedProduct->is_featured)
                        <span class="absolute top-2 right-2 bg-red-500 text-white text-xs px-2 py-1 rounded">Featured</span>
                    @endif
                </div>
                <div class="p-4">
                    <h3 class="font-semibold text-lg mb-2">{{ $relatedProduct->name }}</h3>
                    <div class="flex justify-between items-center">
                        <span class="text-xl font-bold matcha-text">Rp {{ number_format($relatedProduct->price, 0, ',', '.') }}</span>
                        <span class="text-sm text-gray-500">View →</span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endif
</div>

<script>
    function increaseQuantity() {
        const input = document.getElementById('quantity');
        const max = parseInt(input.max);
        if (parseInt(input.value) < max) {
            input.value = parseInt(input.value) + 1;
        }
    }
    
    function decreaseQuantity() {
        const input = document.getElementById('quantity');
        if (parseInt(input.value) > 1) {
            input.value = parseInt(input.value) - 1;
        }
    }
    
    // Star rating interaction
    document.querySelectorAll('input[name="rating"]').forEach((radio, index) => {
        radio.addEventListener('change', function() {
            const stars = document.querySelectorAll('label[for^="rating"] i');
            stars.forEach((star, starIndex) => {
                if (starIndex <= index) {
                    star.classList.remove('far', 'text-gray-300');
                    star.classList.add('fas', 'text-yellow-400');
                } else {
                    star.classList.remove('fas', 'text-yellow-400');
                    star.classList.add('far', 'text-gray-300');
                }
            });
        });
    });
    
    // Handle image errors globally
    document.addEventListener('DOMContentLoaded', function() {
        const images = document.querySelectorAll('img');
        images.forEach(img => {
            img.addEventListener('error', function() {
                this.onerror = null;
                this.src = 'https://via.placeholder.com/600x600/2D5A27/FFFFFF?text=Matcha';
            });
        });
    });
</script>
@endsection