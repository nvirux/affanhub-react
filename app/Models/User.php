<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $phone
 * @property string|null $bvn
 * @property string|null $nin
 * @property string|null $referral_code
 * @property int|null $referred_by
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $two_factor_secret
 * @property string|null $two_factor_recovery_codes
 * @property Carbon|null $two_factor_confirmed_at
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['name', 'email', 'phone', 'bvn', 'nin', 'referral_code', 'referred_by', 'password', 'store_id'])]
#[Hidden(['password', 'two_factor_secret', 'two_factor_recovery_codes', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'two_factor_confirmed_at' => 'datetime',
        ];
    }

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id');
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

    public function virtualAccounts(): MorphMany
    {
        return $this->morphMany(VirtualAccount::class, 'holder');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function referrer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'referred_by');
    }

    public function referrals(): HasMany
    {
        return $this->hasMany(User::class, 'referred_by');
    }

    public function storeReferrals(): HasMany
    {
        return $this->hasMany(Referral::class, 'referrer_id');
    }

    public function referredReferral(): HasOne
    {
        return $this->hasOne(Referral::class, 'referred_id');
    }

    public function ensureReferralCode(): string
    {
        if (! empty($this->referral_code)) {
            return $this->referral_code;
        }

        do {
            $code = 'AF'.strtoupper(Str::random(5));
        } while (static::where('referral_code', $code)->exists());

        $this->referral_code = $code;
        $this->saveQuietly();

        return $code;
    }
}
