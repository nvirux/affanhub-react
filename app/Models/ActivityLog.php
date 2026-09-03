<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    public $timestamps = false;

    protected $guarded = [];

    protected $casts = [
        'properties' => 'array',
        'created_at' => 'datetime',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_at = $model->created_at ?? now();
        });
    }

    public function tenant()
    {
        return $this->belongsTo(Store::class, 'tenant_id');
    }

    public function causer()
    {
        return $this->morphTo();
    }
}
