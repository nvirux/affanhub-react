<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StoreServiceOverride extends Model
{
    protected $table = 'store_service_overrides';

    protected $fillable = [
        'store_id',
        'service_id',
        'is_allowed',
        'reason',
        'granted_by',
    ];

    protected function casts(): array
    {
        return [
            'is_allowed' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::saved(function (StoreServiceOverride $override) {
            if ($override->is_allowed) {
                StoreService::updateOrCreate(
                    [
                        'store_id' => $override->store_id,
                        'service_id' => $override->service_id,
                    ],
                    [
                        'is_enabled' => true,
                    ]
                );
            }
        });
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'store_id');
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'granted_by');
    }
}
