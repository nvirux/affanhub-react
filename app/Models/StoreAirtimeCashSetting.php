<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StoreAirtimeCashSetting extends Model
{
    protected $fillable = [
        'store_id',
        'network_id',
        'receiving_phone',
        'exchange_rate',
        'min_amount',
        'max_amount',
        'ussd_code_template',
        'is_enabled',
    ];

    protected $casts = [
        'exchange_rate' => 'decimal:2',
        'min_amount' => 'decimal:2',
        'max_amount' => 'decimal:2',
        'is_enabled' => 'boolean',
    ];

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function network(): BelongsTo
    {
        return $this->belongsTo(Network::class);
    }

    /**
     * Get default USSD helper text based on network slug.
     */
    public function getDefaultUssdInstructions(): string
    {
        if ($this->ussd_code_template) {
            return $this->ussd_code_template;
        }

        $slug = strtolower($this->network?->slug ?? '');
        $phone = $this->receiving_phone ?: '[Merchant Phone]';

        return match ($slug) {
            'mtn' => "Dial *321# -> Airtime Share -> Transfer to {$phone} (Default PIN: 0000)",
            'airtel' => "Dial *432# -> Airtime Transfer -> Transfer to {$phone} (Default PIN: 1234)",
            'glo' => "Dial *131*{$phone}*[Amount]*[PIN]# (Default PIN: 00000)",
            '9mobile', 'etisalat' => "Dial *223*[PIN]*[Amount]*{$phone}# (Default PIN: 0000)",
            default => "Transfer airtime to {$phone} via your network's share/transfer service.",
        };
    }
}
