<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // TAMBAHKAN INI
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();
        
        // Filter by category
        if ($request->has('category')) {
            $query->where('category', $request->category);
        }
        
        // Filter by search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        // Get featured products
        $featured = Product::where('is_featured', true)->limit(4)->get();
        
        // Sort products
        switch ($request->get('sort', 'newest')) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'rating':
                $query->orderBy('rating', 'desc');
                break;
            default:
                $query->latest();
        }
        
        $products = $query->paginate(12)->withQueryString();
        
        return view('products.index', compact('products', 'featured'));
    }
    
    public function show($id)
    {
        $product = Product::with(['comments.user'])->findOrFail($id);
        
        // PERBAIKAN: Gunakan Auth::check() dan Auth::user() dengan benar
        if (Auth::check()) {
            $product->in_wishlist = Auth::user()->hasInWishlist($product->id);
        } else {
            $product->in_wishlist = false;
        }
        
        // Get related products
        $related = Product::where('category', $product->category)
                         ->where('id', '!=', $product->id)
                         ->limit(4)
                         ->get();
        
        return view('products.show', compact('product', 'related'));
    }
    
    public function addComment(Request $request, $id)
    {
        $request->validate([
            'content' => 'required|string|min:5|max:500',
            'rating' => 'required|integer|min:1|max:5',
        ]);
        
        $product = Product::findOrFail($id);
        
        $comment = new Comment();
        $comment->user_id = Auth::id();
        $comment->product_id = $product->id;
        $comment->content = $request->content;
        $comment->rating = $request->rating;
        $comment->save();
        
        // Update product rating
        $this->updateProductRating($product);
        
        return redirect()->back()->with('success', 'Review added successfully!');
    }
    
    private function updateProductRating(Product $product): void
    {
        $comments = $product->comments()->whereNotNull('rating');
        
        if ($comments->count() > 0) {
            $averageRating = $comments->avg('rating');
            $product->rating = round($averageRating, 2);
            $product->total_reviews = $comments->count();
        } else {
            $product->rating = 0;
            $product->total_reviews = 0;
        }
        
        $product->save();
    }
}