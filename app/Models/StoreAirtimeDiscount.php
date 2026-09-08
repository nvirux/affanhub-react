<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreAirtimeDiscount extends Model
{
    protected $fillable = [
        'store_id',
        'network_id',
        'selling_discount',
        'min_amount',
        'max_amount',
        'is_enabled',
    ];

    protected $casts = [
        'selling_discount' => 'decimal:2',
        'min_amount' => 'decimal:2',
        'max_amount' => 'decimal:2',
        'is_enabled' => 'boolean',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function network()
    {
        return $this->belongsTo(Network::class);
    }
}
