<?php

namespace App\Filament\Merchant\Pages;

use App\Models\Plan;
use BackedEnum;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;

class Billing extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCreditCard;

    protected static string|\UnitEnum|null $navigationGroup = 'Settings';

    protected string $view = 'filament.merchant.pages.billing';

    protected static ?string $title = 'Billing & Subscriptions';

    protected static ?string $navigationLabel = 'Billing';

    protected static ?int $navigationSort = 10;

    public string $interval = 'month'; // 'month' or 'year'

    public function selectInterval(string $interval)
    {
        $this->interval = $interval;
    }

    public function subscribe(int $planId)
    {
        $tenant = Filament::getTenant();
        $plan = Plan::findOrFail($planId);

        if ($plan->slug === 'enterprise') {
            Notification::make()
                ->title('Enterprise Plan Request')
                ->body('Our enterprise plan requires a custom agreement. Our sales team has been notified and will contact you soon!')
                ->info()
                ->send();

            return;
        }

        $price = $this->interval === 'year' ? $plan->price_yearly : $plan->price_monthly;

        // Cancel previous subscriptions
        $tenant->subscriptions()->update(['status' => 'cancelled']);

        // Create new subscription
        $tenant->subscriptions()->create([
            'plan_id' => $plan->id,
            'price' => $price ?? 0.00,
            'billing_interval' => $this->interval,
            'status' => 'active',
            'starts_at' => now(),
            'ends_at' => $this->interval === 'year' ? now()->addYear() : now()->addMonth(),
        ]);

        Notification::make()
            ->title("Upgraded to {$plan->name}!")
            ->body("Your store {$tenant->name} has been successfully upgraded to the {$plan->name} plan.")
            ->success()
            ->send();
    }
}
