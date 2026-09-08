<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Str;

class Wallet extends Model
{
    protected $guarded = [];

    /**
     * Get the owning holder model (User or Owner).
     */
    public function holder(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the transactions for the wallet.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(WalletTransaction::class);
    }

    /**
     * Check if the wallet has sufficient balance.
     */
    public function hasSufficientBalance(float $amount): bool
    {
        return $this->balance >= $amount;
    }

    /**
     * Deposit funds into the wallet securely.
     */
    public function deposit(float $amount, string $description, ?string $reference = null, ?array $meta = null): WalletTransaction
    {
        $reference ??= 'dep_'.Str::random(16);

        return \DB::transaction(function () use ($amount, $description, $reference, $meta) {
            // Lock the wallet row to prevent race conditions during updates
            $wallet = self::where('id', $this->id)->lockForUpdate()->first();
            $wallet->balance += $amount;
            $wallet->save();

            // Refresh the current model instance balance
            $this->balance = $wallet->balance;

            return $wallet->transactions()->create([
                'amount' => $amount,
                'type' => 'credit',
                'reference' => $reference,
                'description' => $description,
                'status' => 'success',
                'meta' => $meta,
            ]);
        });
    }

    /**
     * Withdraw funds from the wallet securely.
     */
    public function withdraw(float $amount, string $description, ?string $reference = null, ?array $meta = null): WalletTransaction
    {
        $reference ??= 'wth_'.Str::random(16);

        return \DB::transaction(function () use ($amount, $description, $reference, $meta) {
            // Lock the wallet row
            $wallet = self::where('id', $this->id)->lockForUpdate()->first();

            if (! $wallet->hasSufficientBalance($amount)) {
                throw new \Exception('Insufficient wallet balance.');
            }

            $wallet->balance -= $amount;
            $wallet->save();

            // Refresh current instance balance
            $this->balance = $wallet->balance;

            return $wallet->transactions()->create([
                'amount' => $amount,
                'type' => 'debit',
                'reference' => $reference,
                'description' => $description,
                'status' => 'success',
                'meta' => $meta,
            ]);
        });
    }
}
