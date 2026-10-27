<?php

namespace App\Models;

use App\Notifications\OwnerEmailOtpNotification;
use Filament\Auth\MultiFactor\App\Concerns\InteractsWithAppAuthentication;
use Filament\Auth\MultiFactor\App\Concerns\InteractsWithAppAuthenticationRecovery;
use Filament\Auth\MultiFactor\App\Contracts\HasAppAuthentication;
use Filament\Auth\MultiFactor\App\Contracts\HasAppAuthenticationRecovery;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasDefaultTenant;
use Filament\Models\Contracts\HasTenants;
use Filament\Panel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class Owner extends Authenticatable implements FilamentUser, HasAppAuthentication, HasAppAuthenticationRecovery, HasDefaultTenant, HasTenants, MustVerifyEmail
{
    use InteractsWithAppAuthentication;
    use InteractsWithAppAuthenticationRecovery;
    use Notifiable;

    protected $guarded = [];

    protected $hidden = [
        'password',
        'remember_token',
        'app_authentication_secret',
        'app_authentication_recovery_codes',
    ];

    protected $casts = [
        'max_stores' => 'integer',
        'email_verified_at' => 'datetime',
    ];

    // The stores this owner actually founded and owns (excludes stores where they are only staff)
    public function ownedStores(): HasMany
    {
        return $this->hasMany(Store::class, 'owner_id');
    }

    // Check if owner has reached their store creation limit
    public function canCreateMoreStores(): bool
    {
        $maxAllowed = $this->max_stores ?? 3;

        return $this->ownedStores()->count() < $maxAllowed;
    }

    // The stores this owner can access (including staff memberships)
    public function stores()
    {
        return $this->belongsToMany(Store::class, 'store_owner')->withPivot('role');
    }

    // Filament Multi-Tenancy Required Methods
    public function getTenants(Panel $panel): array|Collection
    {
        return $this->stores;
    }

    public function getDefaultTenant(Panel $panel): ?Model
    {
        if ($this->last_active_store_id) {
            $lastStore = $this->stores()->whereKey($this->last_active_store_id)->first();
            if ($lastStore) {
                return $lastStore;
            }
        }

        return $this->stores()->first();
    }

    public function recordActiveStore(int|string $storeId): void
    {
        if ($this->last_active_store_id != $storeId) {
            $this->updateQuietly(['last_active_store_id' => $storeId]);
        }
    }

    public function canAccessTenant(Model $tenant): bool
    {
        return $this->stores()->whereKey($tenant)->exists();
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

    public function hasTransactionPin(): bool
    {
        return true;
    }

    public function wallet(?string $name = 'main')
    {
        return $this->stores()->first()?->mainWallet();
    }

    /**
     * Send 6-digit OTP verification email notification.
     */
    public function sendEmailVerificationNotification(): void
    {
        $otp = (string) random_int(100000, 999999);

        Cache::put("owner_email_otp_{$this->id}", hash('sha256', $otp), now()->addMinutes(15));
        Cache::forget("owner_email_otp_attempts_{$this->id}");

        $this->notify(new OwnerEmailOtpNotification($otp));
    }

    /**
     * Verify the provided 6-digit OTP code against the cached code.
     */
    public function verifyEmailOtp(string $code): bool
    {
        $cacheKey = "owner_email_otp_{$this->id}";
        $attemptsKey = "owner_email_otp_attempts_{$this->id}";

        $cachedHash = Cache::get($cacheKey);
        if (! $cachedHash) {
            return false;
        }

        $attempts = (int) Cache::get($attemptsKey, 0);
        if ($attempts >= 5) {
            Cache::forget($cacheKey);
            Cache::forget($attemptsKey);

            return false;
        }

        if (hash_equals($cachedHash, hash('sha256', trim($code)))) {
            Cache::forget($cacheKey);
            Cache::forget($attemptsKey);
            $this->markEmailAsVerified();

            return true;
        }

        Cache::put($attemptsKey, $attempts + 1, now()->addMinutes(15));

        return false;
    }
}
