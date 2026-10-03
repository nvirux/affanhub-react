<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IdentityVerification extends Model
{
    use HasFactory;

    protected $table = 'identity_verifications';

    protected $fillable = [
        'store_id',
        'user_id',
        'service_id',
        'slip_id',
        'transaction_id',
        'search_type',
        'search_value',
        'reference',
        'provider_reference',
        'status',
        'recipient_name',
        'tracking_id',
        'photo',
        'slip_download_url',
        'slip_file_path',
        'data_payload',
        'fee_charged',
        'cost_price',
        'merchant_cost',
        'profit',
    ];

    protected $casts = [
        'data_payload' => 'array',
        'fee_charged' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'merchant_cost' => 'decimal:2',
        'profit' => 'decimal:2',
    ];

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function slip(): BelongsTo
    {
        return $this->belongsTo(Slip::class);
    }

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }
}
