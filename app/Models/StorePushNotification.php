<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StorePushNotification extends Model
{
    protected $table = 'store_push_notifications';

    protected $fillable = [
        'store_id',
        'title',
        'message',
        'action_url',
        'target_audience',
        'status',
        'recipients_count',
        'onesignal_id',
        'failure_reason',
        'sent_by_id',
    ];

    protected $casts = [
        'recipients_count' => 'integer',
    ];

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }
}
