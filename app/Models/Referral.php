<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Referral extends Model
{
    protected $fillable = [
        'store_id',
        'referrer_id',
        'referred_id',
        'status',
        'reward_amount',
        'completed_at',
    ];

    protected $casts = [
        'reward_amount' => 'decimal:2',
        'completed_at' => 'datetime',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function referrer()
    {
        return $this->belongsTo(User::class, 'referrer_id');
    }

    public function referred()
    {
        return $this->belongsTo(User::class, 'referred_id');
    }
}
