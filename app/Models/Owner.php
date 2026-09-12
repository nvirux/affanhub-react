<?php

namespace App\Models;

use Filament\Auth\MultiFactor\App\Concerns\InteractsWithAppAuthentication;
use Filament\Auth\MultiFactor\App\Concerns\InteractsWithAppAuthenticationRecovery;
use Filament\Auth\MultiFactor\App\Contracts\HasAppAuthentication;
use Filament\Auth\MultiFactor\App\Contracts\HasAppAuthenticationRecovery;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasTenants;
use Filament\Panel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Collection;

class Owner extends Authenticatable implements FilamentUser, HasAppAuthentication, HasAppAuthenticationRecovery, HasTenants
{
    use InteractsWithAppAuthentication;
    use InteractsWithAppAuthenticationRecovery;

    protected $guarded = [];

    protected $hidden = [
        'password',
        'remember_token',
        'app_authentication_secret',
        'app_authentication_recovery_codes',
    ];

    protected $casts = [
        'max_stores' => 'integer',
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

    public function canAccessTenant(Model $tenant): bool
    {
        return $this->stores()->whereKey($tenant)->exists();
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }
}
