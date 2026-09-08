<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreReferralSetting extends Model
{
    protected $fillable = [
        'store_id',
        'is_enabled',
        'reward_amount',
        'condition_type',
        'min_deposit_amount',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
        'reward_amount' => 'decimal:2',
        'min_deposit_amount' => 'decimal:2',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
