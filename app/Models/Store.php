<?php

namespace App\Models;

use App\Traits\HasEntitlements;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Storage;
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

        static::deleting(function (Store $store) {
            if ($store->logo_path) {
                Storage::delete($store->logo_path);
            }
            if ($store->favicon_path) {
                Storage::delete($store->favicon_path);
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

    public function getPrimaryDomain(): ?string
    {
        return $this->domains()->where('is_primary', true)->value('domain')
            ?? $this->domains()->value('domain');
    }

    public function getStoreUrl(): string
    {
        $domain = $this->getPrimaryDomain();

        if (! $domain) {
            $baseDomain = parse_url(config('app.url'), PHP_URL_HOST) ?? 'localhost';
            $domain = $this->public_id.'.'.$baseDomain;
        }

        $port = request()->getPort();
        if ($port && ! in_array((int) $port, [80, 443]) && ! str_contains($domain, ':')) {
            $domain .= ":{$port}";
        }

        $scheme = request()->getScheme() ?: (app()->environment('local') ? 'http' : 'https');

        return "{$scheme}://{$domain}";
    }

    /**
     * Calculate funding fee charged to customer when depositing via virtual account.
     */
    public function calculateCustomerDepositFee(float $amount): float
    {
        $feeType = $this->customer_funding_fee_type ?? 'free';
        $feeAmount = (float) ($this->customer_funding_fee_amount ?? 0);
        $maxCap = (float) ($this->customer_funding_fee_cap ?? 100);

        if ($feeType === 'flat') {
            return min($amount, $feeAmount);
        }

        if ($feeType === 'percentage') {
            $percentFee = ($amount * $feeAmount) / 100;

            return min($amount, min($percentFee, $maxCap));
        }

        return 0.0;
    }

    /**
     * Get customer-facing description of funding fee.
     */
    public function getCustomerDepositFeeText(): string
    {
        $feeType = $this->customer_funding_fee_type ?? 'free';
        $feeAmount = (float) ($this->customer_funding_fee_amount ?? 0);

        if ($feeType === 'free' || $feeAmount <= 0) {
            return '0% Fee (Free Funding)';
        }

        if ($feeType === 'flat') {
            return '₦'.number_format($feeAmount, 0).' transfer fee applies';
        }

        if ($feeType === 'percentage') {
            return "{$feeAmount}% transfer fee applies";
        }

        return '0% Fee';
    }

    /**
     * Pending staff invitations for this store.
     */
    public function staffInvitations()
    {
        return $this->hasMany(StaffInvitation::class);
    }
}
