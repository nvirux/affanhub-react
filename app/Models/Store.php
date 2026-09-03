<?php

namespace App\Models;

use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;
use App\Traits\HasEntitlements;

class Store extends BaseTenant implements TenantWithDatabase
{
    use HasDatabase, HasDomains, HasEntitlements;

    protected $table = 'stores';

    public static function getCustomColumns(): array
    {
        return [
            'id',
            'owner_id',
            'name',
            'status',
        ];
    }

    public function owner()
    {
        return $this->belongsTo(Owner::class);
    }

    public function users(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(User::class, 'store_id');
    }

    public function members(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Owner::class, 'store_owner')->withPivot('role')->withTimestamps();
    }

    public function wallets(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(Wallet::class, 'holder');
    }

    public function wallet(string $type = 'main'): Wallet
    {
        return $this->wallets()->firstOrCreate([
            'type' => $type,
        ], [
            'balance' => 0.00,
            'currency' => 'NGN',
            'status' => 'active',
        ]);
    }

    public function mainWallet(): Wallet
    {
        return $this->wallet('main');
    }

    public function profitWallet(): Wallet
    {
        return $this->wallet('profit');
    }

    public function virtualAccounts(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(VirtualAccount::class, 'holder');
    }

    public function transactions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function storeServices(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(StoreService::class);
    }

    public function storeDataPlans(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(StoreDataPlan::class);
    }
}
