<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AirtimeDiscount extends Model
{
    protected $fillable = [
        'network_id',
        'buy_discount',
        'default_merchant_discount',
        'default_retail_discount',
        'min_amount',
        'max_amount',
        'is_active',
    ];

    protected $casts = [
        'buy_discount' => 'decimal:2',
        'default_merchant_discount' => 'decimal:2',
        'default_retail_discount' => 'decimal:2',
        'min_amount' => 'decimal:2',
        'max_amount' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function network()
    {
        return $this->belongsTo(Network::class);
    }
}
