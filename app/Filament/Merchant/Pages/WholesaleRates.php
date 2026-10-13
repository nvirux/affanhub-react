<?php

namespace App\Filament\Merchant\Pages;

use App\Models\AirtimeDiscount;
use App\Models\DataPlan;
use App\Models\Network;
use App\Models\Plan;
use App\Models\PlanAirtimeDiscount;
use App\Models\PlanDataPrice;
use App\Models\Service;
use App\Models\Slip;
use BackedEnum;
use Filament\Facades\Filament;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Collection;
use Livewire\Attributes\Url;
use UnitEnum;

class WholesaleRates extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBolt;

    protected static string|UnitEnum|null $navigationGroup = 'Settings';

    protected static ?string $title = 'Wholesale Rates & Pricing';

    protected static ?string $navigationLabel = 'Wholesale Rates';

    protected static ?int $navigationSort = 2;

    protected string $view = 'filament.merchant.pages.wholesale-rates';

    #[Url(as: 'tab')]
    public string $activeTab = 'data'; // 'data', 'airtime', 'identity', 'utilities'

    public string $search = '';

    public string $selectedNetwork = 'all';

    public function mount(): void
    {
        $tab = request()->query('tab');
        if ($tab && in_array($tab, ['data', 'airtime', 'identity', 'utilities'], true)) {
            $this->activeTab = $tab;
        }
    }

    public function selectTab(string $tab): void
    {
        $this->activeTab = $tab;
    }

    public function getCurrentPlanProperty(): ?Plan
    {
        $tenant = Filament::getTenant();
        $subscription = $tenant?->subscriptions()
            ->where('status', 'active')
            ->latest()
            ->first();

        if ($subscription && $subscription->plan) {
            return $subscription->plan;
        }

        return Plan::where('slug', 'starter')->first() ?? Plan::first();
    }

    public function getIsProOrHigherProperty(): bool
    {
        $slug = $this->currentPlan->slug ?? 'starter';

        return in_array($slug, ['pro', 'enterprise']);
    }

    public function getStarterPlanProperty(): ?Plan
    {
        return Plan::where('slug', 'starter')->first();
    }

    public function getProPlanProperty(): ?Plan
    {
        return Plan::where('slug', 'pro')->first();
    }

    public function getNetworksProperty(): Collection
    {
        return Network::where('is_active', true)->get();
    }

    public function getDataPlansListProperty(): Collection
    {
        $starterPlan = $this->starterPlan;
        $proPlan = $this->proPlan;
        $currentPlan = $this->currentPlan;
        $isPro = $this->isProOrHigher;

        $query = DataPlan::with(['network', 'dataType'])
            ->where('is_active', true);

        if ($this->selectedNetwork !== 'all') {
            $query->where('network_id', $this->selectedNetwork);
        }

        if (filled($this->search)) {
            $term = strtolower(trim($this->search));
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                    ->orWhereHas('network', fn ($nq) => $nq->where('name', 'like', "%{$term}%"))
                    ->orWhereHas('dataType', fn ($dq) => $dq->where('name', 'like', "%{$term}%"));
            });
        }

        $starterPrices = $starterPlan
            ? PlanDataPrice::where('plan_id', $starterPlan->id)->pluck('wholesale_price', 'data_plan_id')
            : collect();

        $proPrices = $proPlan
            ? PlanDataPrice::where('plan_id', $proPlan->id)->pluck('wholesale_price', 'data_plan_id')
            : collect();

        return $query->get()->map(function (DataPlan $plan) use ($starterPrices, $proPrices, $isPro) {
            $basePrice = (float) ($plan->selling_price ?? $plan->cost_price ?? 0);

            $starterRate = isset($starterPrices[$plan->id])
                ? (float) $starterPrices[$plan->id]
                : $basePrice;

            $proRate = isset($proPrices[$plan->id])
                ? (float) $proPrices[$plan->id]
                : max(0, $starterRate - 10.0); // Default ₦10 discount if not explicitly set

            $activeRate = $isPro ? $proRate : $starterRate;
            $savings = max(0, $starterRate - $proRate);

            return [
                'id' => $plan->id,
                'name' => $plan->name,
                'network' => $plan->network?->name ?? 'Unknown',
                'network_id' => $plan->network_id,
                'data_type' => $plan->dataType?->name ?? 'Standard',
                'validity' => $plan->validity ?? '30 Days',
                'starter_rate' => $starterRate,
                'pro_rate' => $proRate,
                'active_rate' => $activeRate,
                'savings' => $savings,
            ];
        });
    }

    public function getAirtimeDiscountsListProperty(): Collection
    {
        $starterPlan = $this->starterPlan;
        $proPlan = $this->proPlan;
        $isPro = $this->isProOrHigher;

        $networks = Network::where('is_active', true)->get();
        $discounts = AirtimeDiscount::where('is_active', true)->get()->keyBy('network_id');

        $starterDiscounts = $starterPlan
            ? PlanAirtimeDiscount::where('plan_id', $starterPlan->id)->pluck('wholesale_discount', 'network_id')
            : collect();

        $proDiscounts = $proPlan
            ? PlanAirtimeDiscount::where('plan_id', $proPlan->id)->pluck('wholesale_discount', 'network_id')
            : collect();

        return $networks->map(function (Network $network) use ($discounts, $starterDiscounts, $proDiscounts, $isPro) {
            $baseDiscount = (float) ($discounts[$network->id]->default_merchant_discount ?? 2.0);

            $starterDisc = isset($starterDiscounts[$network->id])
                ? (float) $starterDiscounts[$network->id]
                : $baseDiscount;

            $proDisc = isset($proDiscounts[$network->id])
                ? (float) $proDiscounts[$network->id]
                : ($starterDisc + 1.5); // Default +1.5% extra margin for Pro

            $activeDisc = $isPro ? $proDisc : $starterDisc;

            return [
                'network' => $network->name,
                'starter_discount' => $starterDisc,
                'pro_discount' => $proDisc,
                'active_discount' => $activeDisc,
                'extra_discount' => max(0, $proDisc - $starterDisc),
            ];
        });
    }

    public function getIdentityServicesListProperty(): Collection
    {
        return Slip::with('service')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get()
            ->map(function (Slip $slip) {
                $serviceName = $slip->service?->name ?? (in_array($slip->slug, ['basic', 'advance', 'plastic']) ? 'BVN Verification' : 'NIN Verification');

                return [
                    'id' => $slip->id,
                    'service_name' => $serviceName,
                    'name' => $slip->name,
                    'badge' => $slip->badge ?? 'Official Slip',
                    'wholesale_cost' => (float) ($slip->selling_price ?? $slip->cost_price ?? 0.0),
                    'description' => $slip->description ?? 'Official identity verification document slip',
                    'features' => (array) ($slip->features ?? []),
                ];
            });
    }

    public function getAdditionalIdentityServicesProperty(): Collection
    {
        return Service::whereIn('key', ['nin_modification', 'bvn_modification', 'nin_validation', 'ipe_clearance'])
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    }

    public function getUtilityServicesListProperty(): Collection
    {
        return $this->identityServicesList;
    }
}
