<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Matcha Store</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --matcha-green: #2D5A27;
            --matcha-light: #A7C957;
            --matcha-cream: #F2E8CF;
            --matcha-red: #BC4749;
            --matcha-brown: #6A4C39;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f9f9f9;
        }
        
        .matcha-bg {
            background-color: var(--matcha-green);
        }
        
        .matcha-text {
            color: var(--matcha-green);
        }
        
        .matcha-light-bg {
            background-color: var(--matcha-light);
        }
        
        .matcha-cream-bg {
            background-color: var(--matcha-cream);
        }
        
        .font-playfair {
            font-family: 'Playfair Display', serif;
        }
        
        .hero-gradient {
            background: linear-gradient(135deg, var(--matcha-green) 0%, var(--matcha-light) 100%);
        }
        
        .btn-matcha {
            background-color: var(--matcha-green);
            color: white;
            transition: all 0.3s ease;
        }
        
        .btn-matcha:hover {
            background-color: var(--matcha-brown);
            transform: translateY(-2px);
        }
        
        .card-hover {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Navigation -->
    <nav class="matcha-bg text-white shadow-lg">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center space-x-2">
                    <i class="fas fa-leaf text-2xl text-green-300"></i>
                    <span class="font-playfair text-2xl font-bold">MatchaStore</span>
                </a>
                
                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-6">
                    @include('partials.pending-payment-alert')
                    <a href="{{ route('home') }}" class="hover:text-green-300 transition">Home</a>
                    <a href="{{ route('products.index') }}" class="hover:text-green-300 transition">Products</a>
                    <a href="#" class="hover:text-green-300 transition">About</a>
                    
                    @auth
                    <div class="relative group">
                    <a href="#" class="flex items-center space-x-1 hover:text-green-300">
                        <i class="fas fa-user"></i>
                        <span>{{ Auth::user()->name }}</span>
                        <i class="fas fa-chevron-down text-xs"></i>
                    </a>

                    <div class="absolute right-0 top-full pt-2 w-48 bg-white text-gray-800 rounded-lg shadow-xl py-2 
                                hidden group-hover:block z-50">
                        <a href="{{ route('orders.index') }}" class="block px-4 py-2 hover:bg-gray-100">My Orders</a>
                        <a href="{{ route('wishlist.index') }}" class="block px-4 py-2 hover:bg-gray-100">Wishlist</a>

                        @if(Auth::user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 hover:bg-gray-100">Admin Panel</a>
                        @endif

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 hover:bg-gray-100">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>

                        
                        <!-- Cart -->
                        <a href="{{ route('cart.index') }}" class="relative hover:text-green-300">
                            <i class="fas fa-shopping-cart text-xl"></i>
                            @if(auth()->user()->carts->count() > 0)
                                <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
                                    {{ auth()->user()->carts->count() }}
                                </span>
                            @endif
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-matcha-green px-4 py-2 rounded-lg font-semibold hover:bg-gray-600">Login</a>
                        <a href="{{ route('register') }}" class="bg-green-600 text-matcha-green px-4 py-2 rounded-lg font-semibold hover:bg-green-500">Register</a>
                    @endauth
                </div>
                
                <!-- Mobile Menu Button -->
                <button class="md:hidden" id="mobile-menu-button">
                    <i class="fas fa-bars text-2xl"></i>
                </button>
            </div>
            
            <!-- Mobile Menu -->
            <div class="md:hidden hidden py-4" id="mobile-menu">
                <div class="flex flex-col space-y-4">
                    <a href="{{ route('home') }}" class="hover:text-green-300 transition">Home</a>
                    <a href="{{ route('products.index') }}" class="hover:text-green-300 transition">Products</a>
                    @auth
                        <a href="{{ route('cart.index') }}" class="hover:text-green-300 transition">Cart</a>
                        <a href="{{ route('wishlist.index') }}" class="hover:text-green-300 transition">Wishlist</a>
                        <a href="{{ route('orders.index') }}" class="hover:text-green-300 transition">My Orders</a>
                        @if(Auth::user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="hover:text-green-300 transition">Admin Panel</a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-left hover:text-green-300">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="hover:text-green-300 transition">Login</a>
                        <a href="{{ route('register') }}" class="hover:text-green-300 transition">Register</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>
    
    <!-- Main Content -->
    <main class="min-h-screen">
        @yield('content')
    </main>
    
    <!-- Footer -->
    <footer class="matcha-bg text-white py-8 mt-12">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <a href="{{ route('home') }}" class="flex items-center space-x-2 mb-4">
                        <i class="fas fa-leaf text-2xl text-green-300"></i>
                        <span class="font-playfair text-2xl font-bold">MatchaStore</span>
                    </a>
                    <p class="text-gray-300">Premium matcha products delivered to your door.</p>
                </div>
                
                <div>
                    <h3 class="text-xl font-semibold mb-4">Quick Links</h3>
                    <ul class="space-y-2">
                        <li><a href="{{ route('home') }}" class="text-gray-300 hover:text-white">Home</a></li>
                        <li><a href="{{ route('products.index') }}" class="text-gray-300 hover:text-white">Products</a></li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="text-xl font-semibold mb-4">Contact Us</h3>
                    <ul class="space-y-2">
                        <li class="flex items-center space-x-2">
                            <i class="fas fa-envelope text-green-300"></i>
                            <span class="text-gray-300">info@matchastore.com</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-green-800 mt-8 pt-8 text-center text-gray-300">
                <p>&copy; {{ date('Y') }} MatchaStore. All rights reserved.</p>
            </div>
        </div>
    </footer>
    
    <!-- Scripts -->
    <script>
        // Mobile menu toggle
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        });
    </script>
    
    @stack('scripts')
</body>
</html>
