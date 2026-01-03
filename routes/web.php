<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
// TAMBAHKAN INI:
use App\Http\Controllers\Admin\ProductController as AdminProductController;

// Public routes
Route::get('/', [ProductController::class, 'index'])->name('home');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    
    // Password reset routes
    Route::get('/password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');
});

// Google OAuth routes
Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

// Authenticated routes
Route::middleware(['auth'])->group(function () {
    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    
    // Cart routes
    Route::prefix('cart')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('cart.index');
        Route::post('/add/{product}', [CartController::class, 'add'])->name('cart.add');
        Route::put('/update/{id}', [CartController::class, 'update'])->name('cart.update');
        Route::delete('/remove/{id}', [CartController::class, 'destroy'])->name('cart.remove');
    });

    // Wishlist routes
    Route::prefix('wishlist')->group(function () {
        Route::get('/', [WishlistController::class, 'index'])->name('wishlist.index');
        Route::post('/toggle/{product}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
    });

    // Comment routes
    Route::post('/products/{id}/comment', [ProductController::class, 'addComment'])->name('products.comment');

    // Checkout routes
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
    
    // Order routes
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
    Route::put('/orders/{id}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    Route::post('/orders/{id}/reorder', [OrderController::class, 'reorder'])->name('orders.reorder');

    // Feedback routes
    Route::get('/feedback', [FeedbackController::class, 'create'])->name('feedback.create');
    Route::post('/feedback', [FeedbackController::class, 'store'])->name('feedback.store');
});

// Admin routes - Using admin middleware
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/users', [DashboardController::class, 'users'])->name('users.index');
    // Admin Product Routes - PERHATIKAN PERUBAHAN DI SINI
    Route::get('/products', [AdminProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [AdminProductController::class, 'create'])->name('products.create');
    Route::post('/products', [AdminProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}', [AdminProductController::class, 'show'])->name('products.show');
    Route::get('/products/{product}/edit', [AdminProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [AdminProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [AdminProductController::class, 'destroy'])->name('products.destroy');
    Route::post('/products/{product}/toggle-featured', [AdminProductController::class, 'toggleFeatured'])->name('products.toggle-featured');

    // Admin Order Routes
    Route::get('/orders', [DashboardController::class, 'orders'])->name('orders');
    // Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::post('/orders/{id}/status', [DashboardController::class, 'updateOrderStatus'])->name('orders.status');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
    Route::put('/orders/{id}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    Route::post('/orders/{id}/reorder', [OrderController::class, 'reorder'])->name('orders.reorder');

    // Admin Feedback Routes
    Route::get('/feedbacks', [DashboardController::class, 'feedbacks'])->name('feedbacks');
    Route::post('/feedbacks/{id}/status', [DashboardController::class, 'updateFeedbackStatus'])->name('feedbacks.status');
    Route::get('/feedbacks/{id}/details', [DashboardController::class, 'feedbackDetails'])->name('feedbacks.details');
});

// Wishlist routes
Route::prefix('wishlist')->group(function () {
    Route::get('/', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/toggle/{product}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::delete('/clear', [WishlistController::class, 'clear'])->name('wishlist.clear');
    Route::post('/add-all-to-cart', [WishlistController::class, 'addAllToCart'])->name('wishlist.addAllToCart');
    Route::post('/move-to-cart/{product}', [WishlistController::class, 'moveToCart'])->name('wishlist.moveToCart');
});

// Midtrans callback
Route::post('/payment/callback', [CheckoutController::class, 'callback']);

// Fallback route
Route::fallback(function () {
    return redirect('/');
});