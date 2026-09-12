<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    protected $guarded = [];

    protected $casts = [
        'api_response' => 'array',
    ];

    /**
     * Get the customer user who created this transaction.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the storefront where this transaction occurred.
     */
    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    /**
     * Get the service definition associated with this transaction.
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }
}
