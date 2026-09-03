<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Filament\Models\Contracts\HasTenants;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Owner extends Authenticatable implements FilamentUser, HasTenants
{
    protected $guarded = [];

    // The stores this owner can access
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
