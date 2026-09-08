<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanAirtimeDiscount extends Model
{
    protected $fillable = [
        'plan_id',
        'network_id',
        'wholesale_discount',
        'min_amount',
        'max_amount',
    ];

    protected $casts = [
        'wholesale_discount' => 'decimal:2',
        'min_amount' => 'decimal:2',
        'max_amount' => 'decimal:2',
    ];

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function network()
    {
        return $this->belongsTo(Network::class);
    }
}
