<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'total_amount',
        'status',
        'payment_status',
        'payment_method',
        'snap_token',
        'snap_redirect_url',
        'shipping_address',
        'notes'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class)->latestOfMany();
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    // ADD THESE HELPER METHODS
    
    /**
     * Check if order payment is pending and can be continued
     */
    public function canContinuePayment(): bool
    {
        return $this->payment_status === 'pending' 
               && in_array($this->status, ['pending', 'processing'])
               && !empty($this->snap_token);
    }

    /**
     * Get the payment URL for Midtrans
     */
    public function getPaymentUrl(): ?string
    {
        if (empty($this->snap_token)) {
            return null;
        }
        
        $baseUrl = config('services.midtrans.is_production') 
            ? 'https://app.midtrans.com'
            : 'https://app.sandbox.midtrans.com';
            
        return $baseUrl . '/snap/v2/vtweb/' . $this->snap_token;
    }

    /**
     * Check if payment has expired (24 hours)
     */
    public function isPaymentExpired(): bool
    {
        if ($this->payment_status !== 'pending') {
            return false;
        }
        
        return $this->created_at->addHours(24)->isPast();
    }
}