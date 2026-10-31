<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class VirtualAccount extends Model
{
    protected $guarded = [];

    protected $hidden = [
        'holder_type',
        'holder_id',
        'email_alias',
        'provider',
        'reference',
        'meta',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'meta' => 'array',
    ];

    /**
     * Get the owning holder model (User or Owner).
     */
    public function holder(): MorphTo
    {
        return $this->morphTo();
    }
}
