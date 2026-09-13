<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class StaffInvitation extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function invitedBy(): BelongsTo
    {
        return $this->belongsTo(Owner::class, 'invited_by');
    }

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    public static function generateToken(): string
    {
        return hash('sha256', Str::random(40).microtime(true));
    }

    public function getAcceptUrl(): string
    {
        $request = request();
        $scheme = $request->getScheme();
        $host = $request->getHost();
        $port = $request->getPort();
        $portString = ($port && ! in_array($port, [80, 443])) ? ":{$port}" : '';

        // If already on merchant subdomain
        if (str_starts_with($host, 'merchant.')) {
            return "{$scheme}://{$host}{$portString}/invitations/{$this->token}";
        }

        // Local development support
        if (str_contains($host, 'localhost') || $host === '127.0.0.1') {
            return "{$scheme}://merchant.localhost{$portString}/invitations/{$this->token}";
        }

        $appHost = parse_url(config('app.url'), PHP_URL_HOST) ?? $host;

        return "{$scheme}://merchant.{$appHost}{$portString}/invitations/{$this->token}";
    }
}
