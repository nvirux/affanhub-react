<?php

namespace App\Filament\Merchant\Pages;

use App\Models\Plan;
use BackedEnum;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Str;

class Billing extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCreditCard;

    protected static string|\UnitEnum|null $navigationGroup = 'Settings';

    protected string $view = 'filament.merchant.pages.billing';

    protected static ?string $title = 'Billing & Subscriptions';

    protected static ?string $navigationLabel = 'Billing';

    protected static ?int $navigationSort = 10;

    public string $interval = 'month'; // 'month' or 'year'

    public bool $showPaymentModal = false;

    public ?int $selectedPlanId = null;

    public function selectInterval(string $interval): void
    {
        $this->interval = $interval;
    }

    public function getSelectedPlanProperty(): ?Plan
    {
        return $this->selectedPlanId ? Plan::find($this->selectedPlanId) : null;
    }

    /**
     * Initiate subscription flow. If plan requires payment, opens the confirmation/payment modal.
     */
    public function subscribe(int $planId): void
    {
        $this->openPaymentModal($planId);
    }

    public function openPaymentModal(int $planId): void
    {
        $plan = Plan::findOrFail($planId);

        if ($plan->slug === 'enterprise') {
            Notification::make()
                ->title('Enterprise Plan Request')
                ->body('Our enterprise plan requires a custom agreement. Our sales team has been notified and will contact you soon!')
                ->info()
                ->send();

            return;
        }

        $price = (float) ($this->interval === 'year' ? $plan->price_yearly : $plan->price_monthly);

        // If the plan is free (e.g. Starter), activate immediately without charging
        if ($price <= 0) {
            $this->activateFreePlan($plan);

            return;
        }

        $this->selectedPlanId = $plan->id;
        $this->showPaymentModal = true;
    }

    public function closePaymentModal(): void
    {
        $this->showPaymentModal = false;
        $this->selectedPlanId = null;
    }

    /**
     * Process wallet debit and activate the chosen paid subscription plan.
     */
    public function confirmAndPay(): void
    {
        if (! $this->selectedPlanId) {
            return;
        }

        $tenant = Filament::getTenant();
        $plan = Plan::findOrFail($this->selectedPlanId);
        $price = (float) ($this->interval === 'year' ? $plan->price_yearly : $plan->price_monthly);

        $wallet = $tenant->mainWallet();

        if (! $wallet->hasSufficientBalance($price)) {
            Notification::make()
                ->title('Insufficient Store Balance')
                ->body('Your store main wallet balance (₦'.number_format($wallet->balance, 2).') is insufficient for this plan (₦'.number_format($price, 2).'). Please fund your store wallet first.')
                ->danger()
                ->send();

            return;
        }

        try {
            // Debit store main wallet atomically
            $wallet->withdraw(
                amount: $price,
                description: "Subscription payment for {$plan->name} plan (".ucfirst($this->interval).'ly)',
                reference: 'sub_'.strtolower(Str::random(14)),
                meta: [
                    'plan_id' => $plan->id,
                    'plan_name' => $plan->name,
                    'billing_interval' => $this->interval,
                    'type' => 'subscription',
                ]
            );

            // Cancel previous active subscriptions
            $tenant->subscriptions()->where('status', 'active')->update(['status' => 'cancelled']);

            // Create new active subscription
            $tenant->subscriptions()->create([
                'plan_id' => $plan->id,
                'price' => $price,
                'billing_interval' => $this->interval,
                'status' => 'active',
                'starts_at' => now(),
                'ends_at' => $this->interval === 'year' ? now()->addYear() : now()->addMonth(),
            ]);

            $this->closePaymentModal();

            Notification::make()
                ->title("Upgraded to {$plan->name}!")
                ->body('₦'.number_format($price, 2)." has been debited from your store wallet. Store {$tenant->name} is now upgraded to {$plan->name}!")
                ->success()
                ->send();

        } catch (\Throwable $e) {
            Notification::make()
                ->title('Payment Failed')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    /**
     * Activate a free plan (e.g. Starter) without debiting the wallet.
     */
    protected function activateFreePlan(Plan $plan): void
    {
        $tenant = Filament::getTenant();

        // Cancel previous active subscriptions
        $tenant->subscriptions()->where('status', 'active')->update(['status' => 'cancelled']);

        // Create new active subscription
        $tenant->subscriptions()->create([
            'plan_id' => $plan->id,
            'price' => 0.00,
            'billing_interval' => $this->interval,
            'status' => 'active',
            'starts_at' => now(),
            'ends_at' => null,
        ]);

        Notification::make()
            ->title("Switched to {$plan->name}")
            ->body("Your store {$tenant->name} has been switched to the {$plan->name} plan.")
            ->success()
            ->send();
    }
}
