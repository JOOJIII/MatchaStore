<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'image',
        'category',
        'rating',
        'total_reviews',
        'is_featured'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'rating' => 'decimal:2',
        'is_featured' => 'boolean',
    ];

    // Relationship dengan comments
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    // Relationship dengan wishlists (users melalui wishlist)
    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    // Relationship dengan users yang menambahkan ke wishlist
    public function wishlistUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'wishlists')
                    ->withTimestamps();
    }

    // Relationship dengan carts
    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class);
    }

    // Relationship dengan order items
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    // Scope untuk featured products
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    // Scope untuk kategori
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    // Scope untuk low stock
    public function scopeLowStock($query, $threshold = 10)
    {
        return $query->where('stock', '<', $threshold);
    }

    // Helper method untuk cek apakah product di wishlist user tertentu
    public function isInWishlist($userId): bool
    {
        return $this->wishlists()->where('user_id', $userId)->exists();
    }

    // Helper method untuk format harga
    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    // Helper method untuk stock status
    public function getStockStatusAttribute(): string
    {
        if ($this->stock <= 0) {
            return 'out-of-stock';
        } elseif ($this->stock < 10) {
            return 'low-stock';
        } else {
            return 'in-stock';
        }
    }

    // Helper method untuk stock status text
    public function getStockStatusTextAttribute(): string
    {
        return match($this->stock_status) {
            'out-of-stock' => 'Out of Stock',
            'low-stock' => 'Low Stock',
            default => 'In Stock'
        };
    }

    // Helper method untuk kategori yang diformat
    public function getFormattedCategoryAttribute(): string
    {
        return ucwords(str_replace('_', ' ', $this->category));
    }

    // src/app/Models/Product.php (tambahkan method ini)
    public function updateRating(): void
    {
        $comments = $this->comments()->whereNotNull('rating');
        
        if ($comments->count() > 0) {
            $this->rating = $comments->avg('rating');
            $this->total_reviews = $comments->count();
        } else {
            $this->rating = 0;
            $this->total_reviews = 0;
        }
        
        $this->save();
    }
}