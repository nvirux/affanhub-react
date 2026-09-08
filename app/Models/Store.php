<?php

namespace App\Models;

use App\Traits\HasEntitlements;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Str;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;
use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;

class Store extends BaseTenant implements TenantWithDatabase
{
    use HasDatabase, HasDomains, HasEntitlements;

    protected $table = 'stores';

    public static function getCustomColumns(): array
    {
        return [
            'id',
            'public_id',
            'owner_id',
            'name',
            'status',
        ];
    }

    protected static function booted(): void
    {
        parent::booted();

        static::creating(function (Store $store) {
            if (empty($store->public_id)) {
                $store->public_id = 'str_'.strtolower(Str::random(10));
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'public_id';
    }

    public function owner()
    {
        return $this->belongsTo(Owner::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'store_id');
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(Owner::class, 'store_owner')->withPivot('role')->withTimestamps();
    }

    public function wallets(): MorphMany
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

    public function virtualAccounts(): MorphMany
    {
        return $this->morphMany(VirtualAccount::class, 'holder');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function storeServices(): HasMany
    {
        return $this->hasMany(StoreService::class);
    }

    public function storeDataPlans(): HasMany
    {
        return $this->hasMany(StoreDataPlan::class);
    }

    public function storeAirtimeDiscounts(): HasMany
    {
        return $this->hasMany(StoreAirtimeDiscount::class);
    }

    public function settlementAccount(): HasOne
    {
        return $this->hasOne(SettlementAccount::class, 'store_id')->where('is_active', true);
    }

    public function referralSetting(): HasOne
    {
        return $this->hasOne(StoreReferralSetting::class);
    }

    public function referrals(): HasMany
    {
        return $this->hasMany(Referral::class);
    }
}
