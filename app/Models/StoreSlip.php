<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StoreSlip extends Model
{
    use HasFactory;

    protected $table = 'store_slips';

    protected $fillable = [
        'store_id',
        'slip_id',
        'selling_price',
        'is_enabled',
    ];

    protected $casts = [
        'selling_price' => 'decimal:2',
        'is_enabled' => 'boolean',
    ];

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function slip(): BelongsTo
    {
        return $this->belongsTo(Slip::class);
    }

    public function getWholesaleCost(): float
    {
        return (float) ($this->slip?->selling_price ?? 0.00);
    }

    public function getEstProfit(): float
    {
        return max(0, (float) $this->selling_price - $this->getWholesaleCost());
    }
}
