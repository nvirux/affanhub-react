<?php

namespace App\Filament\Merchant\Widgets;

use App\Filament\Merchant\Pages\Billing;
use App\Models\Service;
use Filament\Facades\Filament;
use Filament\Widgets\Widget;

class AirtimeCashPromoBannerWidget extends Widget
{
    protected string $view = 'filament.merchant.widgets.airtime-cash-promo-banner';

    protected int|string|array $columnSpan = 'full';

    public static function canView(): bool
    {
        $store = Filament::getTenant();
        $service = Service::where('key', 'airtime_cash')->first();

        if (! $service || ! $store) {
            return false;
        }

        $isAccessible = $service->isAccessibleByStore($store);
        $isInPromo = $service->isInPromo();
        $isTrial = $service->trial_days && $store->created_at && $store->created_at->addDays($service->trial_days)->isFuture();

        return $isInPromo || $isTrial || ! $isAccessible;
    }

    public function getBannerData(): ?array
    {
        $store = Filament::getTenant();
        $service = Service::where('key', 'airtime_cash')->first();

        if (! $service || ! $store) {
            return null;
        }

        $isAccessible = $service->isAccessibleByStore($store);
        $isInPromo = $service->isInPromo();
        $isTrial = $service->trial_days && $store->created_at && $store->created_at->addDays($service->trial_days)->isFuture();

        if ($isInPromo) {
            return [
                'type' => 'promo',
                'title' => '🔥 Special Promotion Active!',
                'message' => "Airtime to Cash is unlocked for all store plans free of charge until {$service->promo_until->format('M d, Y')}!",
                'badge' => 'FREE PROMO',
                'action_label' => null,
                'action_url' => null,
            ];
        }

        if ($isTrial) {
            $daysLeft = (int) now()->diffInDays($store->created_at->addDays($service->trial_days));

            return [
                'type' => 'trial',
                'title' => '⏱️ New Store Free Trial Active!',
                'message' => "You have full free access to Airtime to Cash for {$daysLeft} more days.",
                'badge' => 'FREE TRIAL',
                'action_label' => null,
                'action_url' => null,
            ];
        }

        if (! $isAccessible) {
            $requiredPlan = $service->getRequiredPlanForStore($store);

            return [
                'type' => 'locked',
                'title' => '🔒 Plan Upgrade Required',
                'message' => "Airtime to Cash is not included in your current subscription plan ({$requiredPlan} required). Upgrade your plan to activate airtime conversion on your customer storefront.",
                'badge' => 'UPGRADE REQUIRED',
                'action_label' => '⚡ Upgrade Subscription Plan',
                'action_url' => Billing::getUrl(),
            ];
        }

        return null;
    }
}
