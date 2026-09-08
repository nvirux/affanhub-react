<?php

namespace App\Services;

use App\Models\Referral;
use App\Models\Store;
use App\Models\StoreReferralSetting;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class ReferralService
{
    public function __construct(
        protected WalletService $walletService
    ) {}

    /**
     * Record a referral relationship upon user registration.
     */
    public function recordRegistration(User $user, ?string $referralCode): ?Referral
    {
        if (empty($referralCode) || ! $user->store_id) {
            return null;
        }

        $store = Store::find($user->store_id);
        if (! $store || ! $store->hasFeature('referral_system')) {
            return null;
        }

        $code = strtoupper(trim($referralCode));

        $referrer = User::where('store_id', $user->store_id)
            ->where('referral_code', $code)
            ->first();

        if (! $referrer || $referrer->id === $user->id) {
            return null;
        }

        // Prevent duplicate records if already referred
        if (Referral::where('referred_id', $user->id)->exists()) {
            return null;
        }

        $user->referred_by = $referrer->id;
        $user->saveQuietly();

        return Referral::create([
            'store_id' => $user->store_id,
            'referrer_id' => $referrer->id,
            'referred_id' => $user->id,
            'status' => 'pending',
            'reward_amount' => null,
        ]);
    }

    /**
     * Evaluate qualification condition and reward referrer atomically.
     *
     * @param  User  $user  (The customer performing the action)
     * @param  string  $triggerType  ('deposit' or 'purchase')
     * @param  float  $amount  (Deposit or purchase amount)
     */
    public function checkAndReward(User $user, string $triggerType, float $amount = 0.0): bool
    {
        $referral = Referral::where('referred_id', $user->id)
            ->where('status', 'pending')
            ->first();

        if (! $referral) {
            return false;
        }

        $store = $referral->store;
        if (! $store || ! $store->hasFeature('referral_system')) {
            return false;
        }

        /** @var StoreReferralSetting $settings */
        $settings = StoreReferralSetting::firstOrCreate(
            ['store_id' => $store->id],
            [
                'is_enabled' => true,
                'reward_amount' => 50.00,
                'condition_type' => 'first_deposit',
                'min_deposit_amount' => 500.00,
            ]
        );

        if (! $settings->is_enabled) {
            return false;
        }

        // Qualification check
        $conditionMet = false;

        if ($settings->condition_type === 'first_deposit') {
            if ($triggerType === 'deposit' && $amount >= (float) $settings->min_deposit_amount) {
                $conditionMet = true;
            }
        } elseif ($settings->condition_type === 'first_purchase') {
            if ($triggerType === 'purchase') {
                $conditionMet = true;
            }
        }

        if (! $conditionMet) {
            return false;
        }

        $rewardAmount = (float) $settings->reward_amount;
        $referrer = $referral->referrer;

        if (! $referrer) {
            return false;
        }

        try {
            // Credit referrer main wallet atomically
            $referrerWallet = $referrer->wallet('main');

            $this->walletService->credit(
                $referrerWallet,
                $rewardAmount,
                'referral_bonus',
                "Referral Bonus: {$user->name} completed {$settings->condition_type}",
                [
                    'referred_user_id' => $user->id,
                    'referred_name' => $user->name,
                    'condition_type' => $settings->condition_type,
                    'amount' => $amount,
                ],
                'REF_BONUS_'.$referral->id.'_'.time()
            );

            $referral->update([
                'status' => 'completed',
                'reward_amount' => $rewardAmount,
                'completed_at' => now(),
            ]);

            Log::info("Referral reward of ₦{$rewardAmount} credited to User #{$referrer->id} for referring User #{$user->id}");

            return true;
        } catch (\Throwable $e) {
            Log::error('Failed to credit referral reward: '.$e->getMessage());

            return false;
        }
    }
}
