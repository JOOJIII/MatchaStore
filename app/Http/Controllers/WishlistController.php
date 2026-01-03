<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WishlistController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get wishlist items with pagination
        $wishlistItems = $user->wishlistProducts()
            ->withPivot('created_at')
            ->orderBy('wishlists.created_at', 'desc')
            ->paginate(10);
        
        // Calculate stats
        $featuredCount = $wishlistItems->where('is_featured', true)->count();
        $categoriesCount = $wishlistItems->unique('category')->count();
        
        // Count items already in cart
        $inCartCount = $user->carts()
            ->whereIn('product_id', $wishlistItems->pluck('id'))
            ->count();
        
        // Get recommendations (products from same categories but not in wishlist)
        $userCategories = $wishlistItems->pluck('category')->unique()->toArray();
        $wishlistProductIds = $wishlistItems->pluck('id')->toArray();
        
        $recommendations = Product::whereIn('category', $userCategories)
            ->whereNotIn('id', $wishlistProductIds)
            ->where('stock', '>', 0)
            ->inRandomOrder()
            ->limit(4)
            ->get();
        
        return view('wishlist.index', compact(
            'wishlistItems', 
            'featuredCount', 
            'categoriesCount', 
            'inCartCount',
            'recommendations'
        ));
    }
    
    public function toggle(Request $request, $productId)
    {
        $user = Auth::user();
        $product = Product::findOrFail($productId);
        
        // Check if already in wishlist
        $existing = Wishlist::where('user_id', $user->id)
                           ->where('product_id', $productId)
                           ->first();
        
        if ($existing) {
            // Remove from wishlist
            $existing->delete();
            $message = 'Product removed from wishlist';
            $inWishlist = false;
        } else {
            // Add to wishlist
            Wishlist::create([
                'user_id' => $user->id,
                'product_id' => $productId,
            ]);
            $message = 'Product added to wishlist';
            $inWishlist = true;
        }
        
        $wishlistCount = $user->wishlists()->count();
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'in_wishlist' => $inWishlist,
                'wishlist_count' => $wishlistCount
            ]);
        }
        
        return redirect()->back()->with('success', $message);
    }
    
    public function clear()
    {
        $user = Auth::user();
        $user->wishlists()->delete();
        
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Wishlist cleared successfully'
            ]);
        }
        // return redirect()->back()->with('success', 'Wishlist cleared successfully');
        
        return response()->json([
            'success' => true,
            'redirect' => route('wishlist.index'),
            'message' => 'Wishlist cleared successfully'
        ]);
        
    }
    
    public function addAllToCart()
    {
        $user = Auth::user();
        
        // Get wishlist products that are in stock
        $wishlistProducts = $user->wishlistProducts()
            ->where('stock', '>', 0)
            ->get();
        
        $addedCount = 0;
        
        foreach ($wishlistProducts as $product) {
            // Check if product already in cart
            $existingCart = $user->carts()
                ->where('product_id', $product->id)
                ->first();
            
            if ($existingCart) {
                // Update quantity
                $existingCart->increment('quantity');
            } else {
                // Add new cart item
                $user->carts()->create([
                    'product_id' => $product->id,
                    'quantity' => 1
                ]);
            }
            
            $addedCount++;
        }
        
        $cartCount = $user->carts()->count();
        
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "{$addedCount} items added to cart",
                'cart_count' => $cartCount
            ]);
        }
        
        // return redirect()->route('cart.index')->with('success', "{$addedCount} items added to cart");
        return response()->json([
            'success' => true,
            'redirect' => route('cart.index'),
            'message' => 'Items added to cart'
        ]);
    }
    
    public function moveToCart($productId)
    {
        $user = Auth::user();
        
        // Check if product is in wishlist
        $wishlistItem = $user->wishlists()
            ->where('product_id', $productId)
            ->first();
        
        if (!$wishlistItem) {
            return redirect()->back()->with('error', 'Product not found in wishlist');
        }
        
        // Check if product is in stock
        $product = Product::findOrFail($productId);
        if ($product->stock <= 0) {
            return redirect()->back()->with('error', 'Product is out of stock');
        }
        
        // Check if already in cart
        $existingCart = $user->carts()
            ->where('product_id', $productId)
            ->first();
        
        if ($existingCart) {
            // Update quantity
            $existingCart->increment('quantity');
        } else {
            // Add to cart
            $user->carts()->create([
                'product_id' => $productId,
                'quantity' => 1
            ]);
        }
        
        // Remove from wishlist
        $wishlistItem->delete();
        
        $cartCount = $user->carts()->count();
        $wishlistCount = $user->wishlists()->count();
        
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Product moved to cart',
                'cart_count' => $cartCount,
                'wishlist_count' => $wishlistCount
            ]);
        }
        
        return redirect()->back()->with('success', 'Product moved to cart');
    }
}