{{-- resources/views/wishlist/index.blade.php --}}
@extends('layouts.app')

@section('title', 'My Wishlist - Matcha Store')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold matcha-text mb-2">My Wishlist</h1>
        <p class="text-gray-600">Your saved matcha favorites</p>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-xl shadow p-4">
            <div class="flex items-center">
                <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center mr-4">
                    <i class="fas fa-heart text-matcha-green text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Total Items</p>
                    <p class="text-2xl font-bold">{{ $wishlistItems->total() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow p-4">
            <div class="flex items-center">
                <div class="w-12 h-12 rounded-full bg-yellow-100 flex items-center justify-center mr-4">
                    <i class="fas fa-star text-yellow-500 text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Featured Items</p>
                    <p class="text-2xl font-bold">{{ $featuredCount }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow p-4">
            <div class="flex items-center">
                <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center mr-4">
                    <i class="fas fa-tags text-blue-500 text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Categories</p>
                    <p class="text-2xl font-bold">{{ $categoriesCount }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow p-4">
            <div class="flex items-center">
                <div class="w-12 h-12 rounded-full bg-purple-100 flex items-center justify-center mr-4">
                    <i class="fas fa-shopping-cart text-purple-500 text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">In Cart</p>
                    <p class="text-2xl font-bold">{{ $inCartCount }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Wishlist Content -->
    @if($wishlistItems->count() > 0)
    <div class="bg-white rounded-xl shadow overflow-hidden">
        <!-- Wishlist Header -->
        <div class="px-6 py-4 border-b">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-semibold">Wishlist Items</h2>
                    <p class="text-gray-500 text-sm">{{ $wishlistItems->total() }} items in your wishlist</p>
                </div>
                <div class="flex space-x-2">
                    <button onclick="clearWishlist()" class="px-4 py-2 border border-red-500 text-red-500 rounded-lg hover:bg-red-50 transition">
                        <i class="fas fa-trash mr-2"></i>Clear All
                    </button>
                    <button onclick="addAllToCart()" class="px-4 py-2 btn-matcha rounded-lg">
                        <i class="fas fa-cart-plus mr-2"></i>Add All to Cart
                    </button>
                </div>
            </div>
        </div>

        <!-- Wishlist Items -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="py-3 px-6 text-left text-gray-600 font-medium">Product</th>
                        <th class="py-3 px-6 text-left text-gray-600 font-medium">Price</th>
                        <th class="py-3 px-6 text-left text-gray-600 font-medium">Stock</th>
                        <th class="py-3 px-6 text-left text-gray-600 font-medium">Added On</th>
                        <th class="py-3 px-6 text-left text-gray-600 font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($wishlistItems as $item)
                    <tr class="hover:bg-gray-50 transition" id="wishlist-item-{{ $item->id }}">
                        <!-- Product Column -->
                        <td class="py-4 px-6">
                            <div class="flex items-center">
                                <div class="w-20 h-20 rounded-lg overflow-hidden bg-gray-100 mr-4 flex-shrink-0">
                                    @if($item->image && file_exists(public_path('storage/' . $item->image)))
                                        <img src="{{ asset('storage/' . $item->image) }}" 
                                             alt="{{ $item->name }}"
                                             class="w-full h-full object-cover hover:scale-110 transition duration-300"
                                             onerror="this.src='https://via.placeholder.com/100x100/2D5A27/FFFFFF?text=Matcha'">
                                    @else
                                        <div class="w-full h-full bg-gradient-to-br from-green-100 to-green-50 flex items-center justify-center">
                                            <i class="fas fa-leaf text-2xl text-green-300"></i>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <a href="{{ route('products.show', $item->id) }}" class="font-semibold text-gray-900 hover:text-matcha-green transition block">
                                        {{ $item->name }}
                                    </a>
                                    <div class="flex items-center mt-1">
                                        <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded mr-2">
                                            {{ str_replace('_', ' ', $item->category) }}
                                        </span>
                                        @if($item->is_featured)
                                            <span class="bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded">
                                                <i class="fas fa-star mr-1"></i>Featured
                                            </span>
                                        @endif
                                    </div>
                                    <div class="flex items-center mt-1">
                                        <div class="flex text-yellow-400 text-sm">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= floor($item->rating))
                                                    <i class="fas fa-star"></i>
                                                @elseif($i - 0.5 <= $item->rating)
                                                    <i class="fas fa-star-half-alt"></i>
                                                @else
                                                    <i class="far fa-star"></i>
                                                @endif
                                            @endfor
                                        </div>
                                        <span class="text-gray-500 text-sm ml-2">({{ $item->total_reviews }})</span>
                                    </div>
                                </div>
                            </div>
                        </td>

                        <!-- Price Column -->
                        <td class="py-4 px-6">
                            <div class="font-bold text-lg matcha-text">
                                Rp {{ number_format($item->price, 0, ',', '.') }}
                            </div>
                            @if($item->stock > 0)
                                <span class="text-green-600 text-sm">
                                    <i class="fas fa-check-circle"></i> Available
                                </span>
                            @else
                                <span class="text-red-600 text-sm">
                                    <i class="fas fa-times-circle"></i> Out of Stock
                                </span>
                            @endif
                        </td>

                        <!-- Stock Column -->
                        <td class="py-4 px-6">
                            @if($item->stock > 10)
                                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm">
                                    {{ $item->stock }} in stock
                                </span>
                            @elseif($item->stock > 0)
                                <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm">
                                    Only {{ $item->stock }} left
                                </span>
                            @else
                                <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm">
                                    Out of stock
                                </span>
                            @endif
                        </td>

                        <!-- Added On Column -->
                        <td class="py-4 px-6 text-gray-500">
                            {{ \Carbon\Carbon::parse($item->pivot->created_at)->format('d M Y') }}
                            <div class="text-sm text-gray-400">
                                {{ \Carbon\Carbon::parse($item->pivot->created_at)->diffForHumans() }}
                            </div>
                        </td>

                        <!-- Actions Column -->
                        <td class="py-4 px-6">
                            <div class="flex space-x-2">
                                @if($item->stock > 0)
                                    <form action="{{ route('cart.add', $item->id) }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="p-2 bg-matcha-green text-white rounded-lg hover:bg-green-800 transition" title="Add to Cart">
                                            <i class="fas fa-cart-plus"></i>
                                        </button>
                                    </form>
                                @endif
                                
                                <a href="{{ route('products.show', $item->id) }}" class="p-2 bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200 transition" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                                
                                <button onclick="removeFromWishlist({{ $item->id }})" class="p-2 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition" title="Remove">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t">
            {{ $wishlistItems->links() }}
        </div>
    </div>

    <!-- Recommendations Section -->
    @if($recommendations->count() > 0)
    <div class="mt-12">
        <h2 class="text-2xl font-bold matcha-text mb-6">You Might Also Like</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($recommendations as $product)
            <div class="bg-white rounded-xl shadow-md overflow-hidden card-hover">
                <div class="relative h-48 overflow-hidden">
                    @if($product->image && file_exists(public_path('storage/' . $product->image)))
                        <img src="{{ asset('storage/' . $product->image) }}" 
                             alt="{{ $product->name }}"
                             class="w-full h-full object-cover hover:scale-110 transition duration-300"
                             onerror="this.src='https://via.placeholder.com/300x200/2D5A27/FFFFFF?text=Matcha'">
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-green-100 to-green-50 flex items-center justify-center">
                            <i class="fas fa-leaf text-4xl text-green-300"></i>
                        </div>
                    @endif
                    
                    <form action="{{ route('wishlist.toggle', $product->id) }}" method="POST" class="absolute top-2 right-2">
                        @csrf
                        <button type="submit" class="bg-white p-2 rounded-full shadow-md hover:shadow-lg transition">
                            <i class="{{ auth()->user()->hasInWishlist($product->id) ? 'fas text-red-500' : 'far' }} fa-heart"></i>
                        </button>
                    </form>
                </div>
                <div class="p-4">
                    <h3 class="font-semibold text-lg mb-2 truncate">{{ $product->name }}</h3>
                    <div class="flex items-center mb-3">
                        <div class="flex text-yellow-400 text-sm">
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
                        <span class="ml-2 text-gray-600 text-sm">{{ $product->rating }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-xl font-bold matcha-text">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </span>
                        <a href="{{ route('products.show', $product->id) }}" class="btn-matcha px-4 py-2 rounded-lg text-sm">
                            View
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    @else
    <!-- Empty Wishlist State -->
    <div class="text-center py-16 bg-white rounded-xl shadow">
        <div class="w-24 h-24 mx-auto mb-6 rounded-full bg-gray-100 flex items-center justify-center">
            <i class="fas fa-heart text-4xl text-gray-300"></i>
        </div>
        <h3 class="text-2xl font-semibold text-gray-700 mb-3">Your wishlist is empty</h3>
        <p class="text-gray-500 mb-8 max-w-md mx-auto">
            Save your favorite matcha products here to keep track of items you love and want to purchase later.
        </p>
        <div class="space-x-4">
            <a href="{{ route('products.index') }}" class="btn-matcha px-6 py-3 rounded-lg text-lg">
                <i class="fas fa-store mr-2"></i>Browse Products
            </a>
            <a href="{{ route('home') }}" class="px-6 py-3 border border-matcha-green text-matcha-green rounded-lg hover:bg-green-50 transition">
                <i class="fas fa-home mr-2"></i>Go to Home
            </a>
        </div>
        
        <!-- Popular Categories -->
        <div class="mt-12">
            <h4 class="text-lg font-semibold mb-6">Popular Categories</h4>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 max-w-2xl mx-auto">
                <a href="{{ route('products.index', ['category' => 'matcha_powder']) }}" class="bg-matcha-cream p-4 rounded-lg text-center hover:shadow-md transition">
                    <i class="fas fa-mortar-pestle text-2xl matcha-text mb-2 block"></i>
                    <span class="font-medium">Matcha Powder</span>
                </a>
                <a href="{{ route('products.index', ['category' => 'matcha_tea']) }}" class="bg-matcha-cream p-4 rounded-lg text-center hover:shadow-md transition">
                    <i class="fas fa-mug-hot text-2xl matcha-text mb-2 block"></i>
                    <span class="font-medium">Matcha Tea</span>
                </a>
                <a href="{{ route('products.index', ['category' => 'matcha_dessert']) }}" class="bg-matcha-cream p-4 rounded-lg text-center hover:shadow-md transition">
                    <i class="fas fa-ice-cream text-2xl matcha-text mb-2 block"></i>
                    <span class="font-medium">Desserts</span>
                </a>
                <a href="{{ route('products.index', ['category' => 'matcha_accessories']) }}" class="bg-matcha-cream p-4 rounded-lg text-center hover:shadow-md transition">
                    <i class="fas fa-utensils text-2xl matcha-text mb-2 block"></i>
                    <span class="font-medium">Accessories</span>
                </a>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Modal for Clear Wishlist Confirmation -->
<div id="clearWishlistModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl p-6 max-w-md w-full mx-4">
        <div class="text-center mb-6">
            <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-red-100 flex items-center justify-center">
                <i class="fas fa-exclamation-triangle text-red-500 text-2xl"></i>
            </div>
            <h3 class="text-xl font-semibold mb-2">Clear Wishlist</h3>
            <p class="text-gray-600">Are you sure you want to remove all items from your wishlist?</p>
        </div>
        <div class="flex justify-center space-x-4">
            <button onclick="closeClearModal()" class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                Cancel
            </button>
            <button onclick="confirmClearWishlist()" class="px-6 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition">
                Clear All
            </button>
        </div>
    </div>
</div>

@push('styles')
<style>
    .card-hover {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .card-hover:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
</style>
@endpush

@push('scripts')
<script>
    // Remove item from wishlist
    function removeFromWishlist(productId) {
        if (confirm('Remove this item from wishlist?')) {
            fetch(`/wishlist/toggle/${productId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove row from table
                    const row = document.getElementById(`wishlist-item-${productId}`);
                    if (row) {
                        row.style.opacity = '0';
                        row.style.transition = 'opacity 0.3s';
                        setTimeout(() => row.remove(), 300);
                    }
                    
                    // Show success message
                    showNotification(data.message, 'success');
                    
                    // Update stats if needed
                    if (typeof updateWishlistCount === 'function') {
                        updateWishlistCount(data.wishlist_count);
                    }
                }
            })
            .catch(error => {
                showNotification('Error removing item', 'error');
            });
        }
    }
    
    // Clear wishlist modal
    function clearWishlist() {
        document.getElementById('clearWishlistModal').classList.remove('hidden');
    }
    
    function closeClearModal() {
        document.getElementById('clearWishlistModal').classList.add('hidden');
    }
    
    function confirmClearWishlist() {
        fetch('/wishlist/clear', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Reload page to show empty state
                location.reload();
            }
        })
        .catch(error => {
            showNotification('Error clearing wishlist', 'error');
        });
    }
    
    // Add all items to cart
    function addAllToCart() {
        if (confirm('Add all available items to cart?')) {
            fetch('/wishlist/add-all-to-cart', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                console.log(data.success);
                if (data.success) {
                    showNotification(data.message, 'success');
                    
                    // Update cart count in navbar
                    const cartCount = document.querySelector('.cart-count');
                    if (cartCount) {
                        cartCount.textContent = data.cart_count;
                    }
                }
            })
            .catch(error => {
                showNotification('Error adding items to cart', 'error');
            });
        }
    }
    
    // Notification function
    function showNotification(message, type = 'success') {
        // Remove existing notifications
        const existing = document.querySelector('.custom-notification');
        if (existing) existing.remove();
        
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 custom-notification ${
            type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
        }`;
        notification.innerHTML = `
            <div class="flex items-center">
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} mr-2"></i>
                <span>${message}</span>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        // Auto remove after 3 seconds
        setTimeout(() => {
            notification.style.opacity = '0';
            notification.style.transition = 'opacity 0.3s';
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }
    
    // Update wishlist count in navbar
    function updateWishlistCount(count) {
        const wishlistCount = document.querySelector('.wishlist-count');
        if (wishlistCount) {
            wishlistCount.textContent = count;
        }
    }
</script>
@endpush
@endsection