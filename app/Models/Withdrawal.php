<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Withdrawal extends Model
{
    protected $guarded = [];

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'store_id');
    }

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'fee' => 'decimal:2',
            'net_amount' => 'decimal:2',
        ];
    }
}
