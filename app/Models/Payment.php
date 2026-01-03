<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'transaction_id',
        'amount',
        'payment_type',
        'status',
        'response_data'
    ];

    protected $casts = [
        'response_data' => 'object', // atau 'array'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
