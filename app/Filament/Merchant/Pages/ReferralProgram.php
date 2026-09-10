<?php

namespace App\Filament\Merchant\Pages;

use App\Models\Referral;
use App\Models\StoreReferralSetting;
use App\Services\Audit\ActivityLogger;
use BackedEnum;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class ReferralProgram extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedGift;

    protected static UnitEnum|string|null $navigationGroup = 'Store Management';

    protected static ?string $navigationLabel = 'Referral Program';

    protected static ?string $title = 'Referral & Viral Growth';

    protected static ?int $navigationSort = 5;

    protected string $view = 'filament.merchant.pages.referral-program';

    public bool $hasAccess = false;

    public bool $isEnabled = true;

    public float|string $rewardAmount = 50.00;

    public string $conditionType = 'first_deposit';

    public float|string $minDepositAmount = 500.00;

    public int $totalReferrals = 0;

    public int $completedReferrals = 0;

    public int $pendingReferrals = 0;

    public float $totalRewardsPaid = 0.00;

    public array $recentReferrals = [];

    public function mount(): void
    {
        $tenant = Filament::getTenant() ?? (function_exists('tenant') ? tenant() : null);
        if (! $tenant) {
            return;
        }

        $this->hasAccess = $tenant->hasFeature('referral_system');

        $settings = StoreReferralSetting::firstOrCreate(
            ['store_id' => $tenant->id],
            [
                'is_enabled' => true,
                'reward_amount' => 50.00,
                'condition_type' => 'first_deposit',
                'min_deposit_amount' => 500.00,
            ]
        );

        $this->isEnabled = (bool) $settings->is_enabled;
        $this->rewardAmount = (float) $settings->reward_amount;
        $this->conditionType = $settings->condition_type ?? 'first_deposit';
        $this->minDepositAmount = (float) $settings->min_deposit_amount;

        $this->totalReferrals = Referral::where('store_id', $tenant->id)->count();
        $this->completedReferrals = Referral::where('store_id', $tenant->id)->where('status', 'completed')->count();
        $this->pendingReferrals = Referral::where('store_id', $tenant->id)->where('status', 'pending')->count();
        $this->totalRewardsPaid = (float) Referral::where('store_id', $tenant->id)
            ->where('status', 'completed')
            ->sum('reward_amount');

        $this->recentReferrals = Referral::with(['referrer:id,name,email', 'referred:id,name,email'])
            ->where('store_id', $tenant->id)
            ->latest()
            ->take(15)
            ->get()
            ->map(fn ($r) => [
                'id' => $r->id,
                'referrer_name' => $r->referrer?->name ?? 'Unknown',
                'referrer_email' => $r->referrer?->email ?? '-',
                'referred_name' => $r->referred?->name ?? 'Unknown',
                'referred_email' => $r->referred?->email ?? '-',
                'status' => $r->status,
                'reward_amount' => (float) ($r->reward_amount ?? $this->rewardAmount),
                'created_at' => $r->created_at?->toFormattedDateString() ?? '-',
                'completed_at' => $r->completed_at?->toFormattedDateString() ?? '-',
            ])
            ->toArray();
    }

    public function saveSettings(): void
    {
        $tenant = Filament::getTenant() ?? (function_exists('tenant') ? tenant() : null);
        if (! $tenant) {
            return;
        }

        if (! $this->hasAccess) {
            $required = method_exists($tenant, 'getFeatureUpgradeRequirement')
                ? $tenant->getFeatureUpgradeRequirement('referral_system')
                : 'Pro Plan';

            Notification::make()
                ->title('Feature Locked')
                ->body("The Customer Referral System is not available on your current plan. Please upgrade to {$required} or contact support.")
                ->warning()
                ->send();

            return;
        }

        $this->validate([
            'rewardAmount' => 'required|numeric|min:1|max:100000',
            'conditionType' => 'required|in:first_deposit,first_purchase',
            'minDepositAmount' => 'required|numeric|min:0|max:1000000',
        ]);

        $settings = StoreReferralSetting::updateOrCreate(
            ['store_id' => $tenant->id],
            [
                'is_enabled' => $this->isEnabled,
                'reward_amount' => (float) $this->rewardAmount,
                'condition_type' => $this->conditionType,
                'min_deposit_amount' => (float) $this->minDepositAmount,
            ]
        );

        ActivityLogger::log(
            'referral_settings_updated',
            'Updated customer referral & rewards configuration',
            [
                'is_enabled' => $this->isEnabled,
                'reward_amount' => $this->rewardAmount,
                'condition_type' => $this->conditionType,
                'min_deposit_amount' => $this->minDepositAmount,
            ]
        );

        Notification::make()
            ->title('Settings Saved')
            ->body('Customer referral program settings have been updated.')
            ->success()
            ->send();
    }
}
