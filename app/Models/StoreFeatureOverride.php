<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StoreFeatureOverride extends Model
{
    protected $table = 'store_feature_overrides';

    protected $fillable = [
        'store_id',
        'feature_id',
        'value',
        'reason',
        'granted_by',
    ];

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'store_id');
    }

    public function feature(): BelongsTo
    {
        return $this->belongsTo(Feature::class);
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'granted_by');
    }
}
