<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'google_token',
        'avatar',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'google_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Relationship dengan carts
    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class);
    }

    // Relationship dengan wishlists
    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    // Relationship dengan comments
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    // Relationship dengan orders
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    // Relationship dengan feedbacks
    public function feedbacks(): HasMany
    {
        return $this->hasMany(Feedback::class);
    }

    public function wishlistProducts()
    {
        return $this->belongsToMany(Product::class, 'wishlists')
                    ->withTimestamps()
                    ->withPivot('created_at');
    }

    // Check if user is admin
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    // Check if user has product in wishlist
    public function hasInWishlist($productId): bool
    {
        return $this->wishlists()->where('product_id', $productId)->exists();
    }

    // Get cart item count
    public function getCartCountAttribute(): int
    {
        return $this->carts()->count();
    }

    // Get wishlist item count
    public function getWishlistCountAttribute(): int
    {
        return $this->wishlists()->count();
    }
}