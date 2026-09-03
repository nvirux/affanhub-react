<?php

namespace App\Models;

use Stancl\Tenancy\Database\Models\Domain as BaseDomain;

class Domain extends BaseDomain
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_VERIFIED = 'verified';
    public const STATUS_FAILED = 'failed';
    public const STATUS_INACTIVE = 'inactive';

    public const VERIFY_PENDING_DNS = 'pending_dns';
    public const VERIFY_OWNERSHIP_VERIFIED = 'ownership_verified';
    public const VERIFY_CONNECTED = 'connected';
    public const VERIFY_ACTIVE = 'active';

    protected $fillable = [
        'tenant_id',
        'domain',
        'is_primary',
        'status',
        'is_verified',
        'verified_at',
        'verification_status',
        'is_approved',
        'approved_at',
        'is_routing_enabled',
        'routing_disabled_reason',
        'verification_error',
        'verification_token',
        'last_http_status',
        'last_resolved_ip',
        'last_verification_message',
        'cloudflare_detected',
        'origin_verified_at',
        'ownership_verified_at',
        'connection_verified_at',
        'is_legacy',
        'migrated_at',
        'verification_debug_info',
        'verification_attempts',
        'last_verification_attempt_at',
        'verification_failed_at',
        'verification_paused_at',
        'verification_failure_reason',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'is_verified' => 'boolean',
        'verified_at' => 'datetime',
        'is_approved' => 'boolean',
        'approved_at' => 'datetime',
        'is_routing_enabled' => 'boolean',
        'cloudflare_detected' => 'boolean',
        'origin_verified_at' => 'datetime',
        'ownership_verified_at' => 'datetime',
        'connection_verified_at' => 'datetime',
        'is_legacy' => 'boolean',
        'migrated_at' => 'datetime',
        'verification_debug_info' => 'array',
        'last_verification_attempt_at' => 'datetime',
        'verification_failed_at' => 'datetime',
        'verification_paused_at' => 'datetime',
    ];

    public function isHealthy(): bool
    {
        if (! $this->isCustom()) {
            return true;
        }

        return $this->is_verified 
            || $this->is_approved 
            || $this->is_routing_enabled 
            || $this->is_legacy
            || in_array($this->verification_status, [
                self::VERIFY_ACTIVE,
                self::VERIFY_CONNECTED,
                self::STATUS_VERIFIED
            ], true);
    }

    public function isVerified(): bool
    {
        if (! $this->isCustom()) {
            return true;
        }

        // If explicitly marked verified or legacy, trust it
        if ($this->is_verified || $this->is_legacy || $this->status === self::STATUS_VERIFIED) {
            return true;
        }

        return $this->ownership_verified_at !== null
            && in_array($this->verification_status, [
                self::VERIFY_CONNECTED,
                self::VERIFY_ACTIVE,
            ], true);
    }

    public function isApproved(): bool
    {
        if (! $this->isCustom()) {
            return true;
        }

        // Legacy/Healthy domains are implicitly approved
        if ($this->is_legacy || ($this->is_verified && $this->is_routing_enabled)) {
            return true;
        }

        return (bool) $this->is_approved && $this->approved_at !== null;
    }

    public function isActiveForRouting(): bool
    {
        if (! $this->isCustom()) {
            return true;
        }

        if (! $this->is_routing_enabled) {
            return false;
        }

        // For legacy/healthy domains, we only need is_routing_enabled
        if ($this->is_legacy || $this->is_verified) {
            return true;
        }

        return $this->isVerified() && $this->isApproved() && $this->verification_status === self::VERIFY_ACTIVE;
    }

    public function isCustom(): bool
    {
        $centralDomains = config('tenancy.central_domains', []);
        foreach ($centralDomains as $central) {
            if (str_ends_with($this->domain, '.' . $central)) {
                return false;
            }
        }
        return true;
    }

    public function typeLabel(): string
    {
        return $this->isCustom() ? 'Custom' : 'Platform';
    }

    public function routingStateLabel(): string
    {
        if ($this->isActiveForRouting()) {
            return 'Ready';
        }

        if (! $this->isCustom()) {
            return 'Ready';
        }

        if ($this->ownership_verified_at === null) {
            return 'Verification needed';
        }

        if (! $this->isApproved()) {
            return 'Approval needed';
        }

        return 'Ready';
    }

    public function markAsHealthyLegacy(): void
    {
        $this->update([
            'is_legacy' => true,
            'migrated_at' => $this->migrated_at ?? now(),
            'is_verified' => true,
            'verified_at' => $this->verified_at ?? $this->created_at ?? now(),
            'status' => self::STATUS_VERIFIED,
            'verification_status' => self::VERIFY_ACTIVE,
            'is_approved' => true,
            'approved_at' => $this->approved_at ?? $this->created_at ?? now(),
            'is_routing_enabled' => true,
            'ownership_verified_at' => $this->ownership_verified_at ?? $this->created_at ?? now(),
        ]);
    }

    public function store()
    {
        return $this->belongsTo(Store::class, 'tenant_id');
    }
}
