@extends('layouts.app')

@section('title', 'About Us - Matcha Store')

@section('content')
<div class="min-h-screen">
    <!-- Hero Section -->
    <div class="hero-gradient text-white py-20">
        <div class="container mx-auto px-4 text-center">
            <div class="max-w-3xl mx-auto">
                <i class="fas fa-leaf text-6xl mb-6 animate-bounce"></i>
                <h1 class="font-playfair text-5xl md:text-6xl font-bold mb-6">About Matcha Store</h1>
                <p class="text-xl md:text-2xl text-green-100">
                    Bringing the authentic taste of Japanese matcha to your doorstep
                </p>
            </div>
        </div>
    </div>

    <!-- Our Story Section -->
    <div class="container mx-auto px-4 py-16">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div>
                <h2 class="text-3xl md:text-4xl font-bold matcha-text mb-6">Our Story</h2>
                <div class="space-y-4 text-gray-700 text-lg">
                    <p>
                        Founded in 2020, Matcha Store began with a simple mission: to share the incredible 
                        benefits and exquisite taste of authentic Japanese matcha with the world.
                    </p>
                    <p>
                        Our journey started when our founder discovered the traditional art of matcha preparation 
                        during a visit to Kyoto, Japan. Inspired by the centuries-old tea ceremony and the health 
                        benefits of matcha, we decided to bring these premium products to matcha enthusiasts everywhere.
                    </p>
                    <p>
                        Today, we work directly with tea farmers in Uji and Nishio, the most renowned matcha-producing 
                        regions in Japan, to source only the finest quality matcha powder and products.
                    </p>
                </div>
            </div>
            <div class="relative">
                <div class="bg-gradient-to-br from-green-100 to-green-50 rounded-2xl p-8 h-96 flex items-center justify-center">
                    <i class="fas fa-spa text-9xl text-green-300"></i>
                </div>
                <div class="absolute -bottom-6 -left-6 bg-matcha-green text-white p-6 rounded-xl shadow-lg">
                    <p class="text-4xl font-bold">4+</p>
                    <p class="text-sm">Years of Excellence</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Our Values Section -->
    <div class="bg-matcha-cream py-16">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold matcha-text mb-4">Our Values</h2>
                <p class="text-gray-600 text-lg">What drives us every day</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Quality -->
                <div class="bg-white rounded-xl p-8 text-center card-hover shadow-md">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-award text-matcha-green text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Premium Quality</h3>
                    <p class="text-gray-600">
                        We source only the finest ceremonial and culinary grade matcha from certified 
                        organic farms in Japan, ensuring every product meets our high standards.
                    </p>
                </div>

                <!-- Authenticity -->
                <div class="bg-white rounded-xl p-8 text-center card-hover shadow-md">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-certificate text-matcha-green text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Authenticity</h3>
                    <p class="text-gray-600">
                        Direct partnerships with Japanese tea farmers guarantee authentic matcha 
                        products, preserving traditional cultivation and processing methods.
                    </p>
                </div>

                <!-- Sustainability -->
                <div class="bg-white rounded-xl p-8 text-center card-hover shadow-md">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-leaf text-matcha-green text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Sustainability</h3>
                    <p class="text-gray-600">
                        We're committed to eco-friendly practices, from sustainable farming to 
                        recyclable packaging, protecting our planet for future generations.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Why Choose Us Section -->
    <div class="container mx-auto px-4 py-16">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold matcha-text mb-4">Why Choose Matcha Store?</h2>
            <p class="text-gray-600 text-lg">What makes us different</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-4xl mx-auto">
            <div class="flex items-start space-x-4 bg-white p-6 rounded-xl shadow-sm">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-shipping-fast text-matcha-green text-xl"></i>
                </div>
                <div>
                    <h3 class="font-bold text-lg mb-2">Fast & Free Shipping</h3>
                    <p class="text-gray-600">Free delivery on all orders. Your matcha arrives fresh and fast.</p>
                </div>
            </div>

            <div class="flex items-start space-x-4 bg-white p-6 rounded-xl shadow-sm">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-shield-alt text-matcha-green text-xl"></i>
                </div>
                <div>
                    <h3 class="font-bold text-lg mb-2">Quality Guarantee</h3>
                    <p class="text-gray-600">100% satisfaction guaranteed or your money back, no questions asked.</p>
                </div>
            </div>

            <div class="flex items-start space-x-4 bg-white p-6 rounded-xl shadow-sm">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-user-check text-matcha-green text-xl"></i>
                </div>
                <div>
                    <h3 class="font-bold text-lg mb-2">Expert Support</h3>
                    <p class="text-gray-600">Our matcha experts are here to help with any questions or guidance.</p>
                </div>
            </div>

            <div class="flex items-start space-x-4 bg-white p-6 rounded-xl shadow-sm">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-heart text-matcha-green text-xl"></i>
                </div>
                <div>
                    <h3 class="font-bold text-lg mb-2">Customer First</h3>
                    <p class="text-gray-600">Your satisfaction is our priority. We're here to serve you better.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Section -->
    <div class="bg-matcha-green text-white py-16">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                <div>
                    <div class="text-4xl md:text-5xl font-bold mb-2">10,000+</div>
                    <div class="text-green-100">Happy Customers</div>
                </div>
                <div>
                    <div class="text-4xl md:text-5xl font-bold mb-2">50+</div>
                    <div class="text-green-100">Premium Products</div>
                </div>
                <div>
                    <div class="text-4xl md:text-5xl font-bold mb-2">5★</div>
                    <div class="text-green-100">Average Rating</div>
                </div>
                <div>
                    <div class="text-4xl md:text-5xl font-bold mb-2">100%</div>
                    <div class="text-green-100">Organic & Natural</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Health Benefits Section -->
    <div class="container mx-auto px-4 py-16">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold matcha-text mb-4">Health Benefits of Matcha</h2>
            <p class="text-gray-600 text-lg">Why matcha is good for you</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div class="bg-gradient-to-br from-green-50 to-white p-6 rounded-xl">
                <i class="fas fa-bolt text-3xl text-matcha-green mb-4"></i>
                <h3 class="text-xl font-bold mb-3">Energy Boost</h3>
                <p class="text-gray-600">
                    Natural caffeine provides sustained energy without the jitters or crash associated with coffee.
                </p>
            </div>

            <div class="bg-gradient-to-br from-green-50 to-white p-6 rounded-xl">
                <i class="fas fa-brain text-3xl text-matcha-green mb-4"></i>
                <h3 class="text-xl font-bold mb-3">Mental Clarity</h3>
                <p class="text-gray-600">
                    L-theanine promotes calm focus and concentration, perfect for work or meditation.
                </p>
            </div>

            <div class="bg-gradient-to-br from-green-50 to-white p-6 rounded-xl">
                <i class="fas fa-shield-virus text-3xl text-matcha-green mb-4"></i>
                <h3 class="text-xl font-bold mb-3">Antioxidants</h3>
                <p class="text-gray-600">
                    Packed with EGCG catechins, one of the most powerful antioxidants found in nature.
                </p>
            </div>

            <div class="bg-gradient-to-br from-green-50 to-white p-6 rounded-xl">
                <i class="fas fa-heart text-3xl text-matcha-green mb-4"></i>
                <h3 class="text-xl font-bold mb-3">Heart Health</h3>
                <p class="text-gray-600">
                    Helps maintain healthy cholesterol levels and supports cardiovascular health.
                </p>
            </div>

            <div class="bg-gradient-to-br from-green-50 to-white p-6 rounded-xl">
                <i class="fas fa-weight text-3xl text-matcha-green mb-4"></i>
                <h3 class="text-xl font-bold mb-3">Metabolism Boost</h3>
                <p class="text-gray-600">
                    Naturally enhances metabolism and supports healthy weight management goals.
                </p>
            </div>

            <div class="bg-gradient-to-br from-green-50 to-white p-6 rounded-xl">
                <i class="fas fa-smile text-3xl text-matcha-green mb-4"></i>
                <h3 class="text-xl font-bold mb-3">Mood Enhancement</h3>
                <p class="text-gray-600">
                    Natural compounds promote relaxation and positive mood throughout the day.
                </p>
            </div>
        </div>
    </div>

    <!-- Sourcing Section -->
    <div class="bg-gradient-to-r from-green-700 to-green-600 text-white py-16">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center">
                <h2 class="text-3xl md:text-4xl font-bold mb-6">Sourced from Japan's Finest Tea Regions</h2>
                <p class="text-xl text-green-100 mb-8">
                    Our matcha comes from Uji (Kyoto) and Nishio (Aichi), regions with over 800 years 
                    of matcha cultivation expertise. These areas provide the perfect climate and soil 
                    conditions for growing premium matcha tea leaves.
                </p>
                <div class="flex flex-wrap justify-center gap-4">
                    <span class="bg-white bg-opacity-20 px-6 py-3 rounded-full">🇯🇵 100% Japanese</span>
                    <span class="bg-white bg-opacity-20 px-6 py-3 rounded-full">🌿 Organic Certified</span>
                    <span class="bg-white bg-opacity-20 px-6 py-3 rounded-full">✨ Premium Quality</span>
                    <span class="bg-white bg-opacity-20 px-6 py-3 rounded-full">🏆 Award Winning</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Team Section -->
    <div class="container mx-auto px-4 py-16">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold matcha-text mb-4">Meet Our Team</h2>
            <p class="text-gray-600 text-lg">Passionate about bringing you the best matcha</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl mx-auto">
            <div class="text-center">
                <div class="w-40 h-40 mx-auto mb-4 rounded-full bg-gradient-to-br from-green-100 to-green-50 flex items-center justify-center">
                    <i class="fas fa-user-tie text-5xl text-matcha-green"></i>
                </div>
                <h3 class="text-xl font-bold mb-2">Takeshi Yamamoto</h3>
                <p class="text-matcha-green font-medium mb-2">Founder & CEO</p>
                <p class="text-gray-600 text-sm">
                    Tea master with 15+ years experience in Japanese tea ceremony
                </p>
            </div>

            <div class="text-center">
                <div class="w-40 h-40 mx-auto mb-4 rounded-full bg-gradient-to-br from-green-100 to-green-50 flex items-center justify-center">
                    <i class="fas fa-user-friends text-5xl text-matcha-green"></i>
                </div>
                <h3 class="text-xl font-bold mb-2">Sarah Chen</h3>
                <p class="text-matcha-green font-medium mb-2">Head of Quality</p>
                <p class="text-gray-600 text-sm">
                    Ensures every product meets our strict quality standards
                </p>
            </div>

            <div class="text-center">
                <div class="w-40 h-40 mx-auto mb-4 rounded-full bg-gradient-to-br from-green-100 to-green-50 flex items-center justify-center">
                    <i class="fas fa-user-graduate text-5xl text-matcha-green"></i>
                </div>
                <h3 class="text-xl font-bold mb-2">David Kim</h3>
                <p class="text-matcha-green font-medium mb-2">Customer Success</p>
                <p class="text-gray-600 text-sm">
                    Dedicated to providing exceptional customer experience
                </p>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="bg-matcha-cream py-16">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl md:text-4xl font-bold matcha-text mb-4">
                Ready to Experience Premium Matcha?
            </h2>
            <p class="text-gray-600 text-lg mb-8 max-w-2xl mx-auto">
                Join thousands of satisfied customers who have discovered the incredible taste 
                and benefits of authentic Japanese matcha.
            </p>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="{{ route('products.index') }}" class="btn-matcha px-8 py-4 rounded-lg text-lg font-semibold">
                    <i class="fas fa-store mr-2"></i>Shop Now
                </a>
                <a href="{{ route('feedback.create') }}" class="px-8 py-4 border-2 border-matcha-green text-matcha-green rounded-lg text-lg font-semibold hover:bg-matcha-green hover:text-white transition">
                    <i class="fas fa-envelope mr-2"></i>Contact Us
                </a>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    @keyframes bounce {
        0%, 100% {
            transform: translateY(0);
        }
        50% {
            transform: translateY(-10px);
        }
    }
    
    .animate-bounce {
        animation: bounce 2s infinite;
    }
</style>
@endpush
@endsection