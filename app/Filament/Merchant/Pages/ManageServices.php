<?php

namespace App\Filament\Merchant\Pages;

use App\Models\Service;
use App\Models\StoreService;
use BackedEnum;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class ManageServices extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSquares2x2;

    protected static UnitEnum|string|null $navigationGroup = 'Products & Pricing';

    protected static ?string $navigationLabel = 'Services & Layout';

    protected static ?string $title = 'Services & Display Layout';

    protected static ?int $navigationSort = 1;

    protected string $view = 'filament.merchant.pages.manage-services';

    public array $serviceSettings = [];

    public string $categoryFilter = 'all';

    public string $searchQuery = '';

    public function mount(): void
    {
        $this->loadServiceSettings();
    }

    public function setCategoryFilter(string $category): void
    {
        $this->categoryFilter = $category;
    }

    public function loadServiceSettings(): void
    {
        $tenant = Filament::getTenant() ?? tenant();
        if (! $tenant) {
            return;
        }

        $allServices = Service::orderBy('sort_order', 'asc')->get();

        foreach ($allServices as $service) {
            $hasAccess = true;
            $requiredPlanName = 'Enterprise Plan or Contact Support';

            if ($service->feature_id && $service->feature) {
                $hasAccess = $tenant->hasFeature($service->feature->slug);

                $requiredPlanName = method_exists($tenant, 'getFeatureUpgradeRequirement')
                    ? $tenant->getFeatureUpgradeRequirement($service->feature->slug)
                    : 'Enterprise Plan';
            }

            $setting = StoreService::where('store_id', $tenant->id)
                ->where('service_id', $service->id)
                ->first();

            $this->serviceSettings[$service->id] = [
                'id' => $service->id,
                'name' => $service->name,
                'key' => $service->key,
                'category' => $service->category,
                'description' => $service->description,
                'icon' => $service->icon,
                'is_active' => (bool) $service->is_active,
                'has_access' => $hasAccess,
                'required_plan' => $requiredPlanName,
                'feature_name' => $service->feature?->name,
                'is_enabled' => $setting ? (bool) $setting->is_enabled : true,
                'sort_order' => $setting ? (int) $setting->sort_order : $service->sort_order,
            ];
        }
    }

    public function toggleService(int $serviceId): void
    {
        if (! isset($this->serviceSettings[$serviceId])) {
            return;
        }

        if (! $this->serviceSettings[$serviceId]['has_access']) {
            Notification::make()
                ->title('Feature Locked')
                ->body('Upgrade to '.$this->serviceSettings[$serviceId]['required_plan'].' to enable '.$this->serviceSettings[$serviceId]['name'].' on your storefront.')
                ->warning()
                ->send();

            return;
        }

        $this->serviceSettings[$serviceId]['is_enabled'] = ! $this->serviceSettings[$serviceId]['is_enabled'];
        $this->saveServiceSetting($serviceId);
    }

    public function updateSortOrder(int $serviceId, $order): void
    {
        if (! isset($this->serviceSettings[$serviceId])) {
            return;
        }

        $this->serviceSettings[$serviceId]['sort_order'] = (int) $order;
        $this->saveServiceSetting($serviceId);
    }

    public function saveServiceSetting(int $serviceId): void
    {
        $tenant = Filament::getTenant() ?? tenant();
        if (! $tenant || ! isset($this->serviceSettings[$serviceId])) {
            return;
        }

        $data = $this->serviceSettings[$serviceId];

        StoreService::updateOrCreate(
            [
                'store_id' => $tenant->id,
                'service_id' => $serviceId,
            ],
            [
                'is_enabled' => $data['is_enabled'],
                'sort_order' => $data['sort_order'],
            ]
        );

        Notification::make()
            ->title('Layout Updated')
            ->body("Updated display settings for {$data['name']}.")
            ->success()
            ->send();
    }
}
