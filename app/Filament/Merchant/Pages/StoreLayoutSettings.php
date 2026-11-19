<?php

namespace App\Filament\Merchant\Pages;

use BackedEnum;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use UnitEnum;

class StoreLayoutSettings extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-view-columns';

    protected static string|UnitEnum|null $navigationGroup = 'Services & Layout';

    protected static ?string $navigationLabel = 'Layout';

    protected static ?string $title = 'Services & Layout';

    protected static ?string $slug = 'service-layout';

    protected static ?int $navigationSort = 2;

    protected string $view = 'filament.merchant.pages.store-layout-settings';

    public string $serviceLayout = 'compact_4';

    public bool $showServiceDescriptions = true;

    public int $mobileQuickActionsCount = 7;

    public function mount(): void
    {
        $store = Filament::getTenant();
        if ($store) {
            $this->serviceLayout = $store->service_layout ?? 'compact_4';
            $this->showServiceDescriptions = $store->show_service_descriptions !== null ? (bool) $store->show_service_descriptions : true;
            $this->mobileQuickActionsCount = (int) ($store->mobile_quick_actions_count ?? 7);
        }
    }

    public function save(): void
    {
        $store = Filament::getTenant();
        if (! $store) {
            return;
        }

        $store->service_layout = in_array($this->serviceLayout, ['compact_4', 'cards_2'], true) ? $this->serviceLayout : 'compact_4';
        $store->show_service_descriptions = $this->showServiceDescriptions;
        $store->mobile_quick_actions_count = $this->mobileQuickActionsCount;
        $store->save();

        Notification::make()
            ->title('Layout Settings Saved')
            ->body('Your storefront service layout has been updated.')
            ->success()
            ->send();
    }
}
