<?php

namespace App\Filament\Pages;

use App\Models\PlatformSetting;
use BackedEnum;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class PlatformMobileAppSettings extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDevicePhoneMobile;

    protected static UnitEnum|string|null $navigationGroup = '🏬 Tenancy & Stores';

    protected string $view = 'filament.pages.platform-mobile-app-settings';

    protected static ?string $title = 'Mobile App Pricing & Configuration';

    protected static ?string $navigationLabel = 'Mobile App Pricing';

    protected static ?int $navigationSort = 5;

    public float $setupFee = 15000.00;

    public float $playstoreFee = 30000.00;

    public function mount(): void
    {
        $this->setupFee = (float) PlatformSetting::get('mobile_app_setup_fee', 15000.00);
        $this->playstoreFee = (float) PlatformSetting::get('mobile_app_playstore_fee', 30000.00);
    }

    public function save(): void
    {
        $this->validate([
            'setupFee' => 'required|numeric|min:0|max:1000000',
            'playstoreFee' => 'required|numeric|min:0|max:1000000',
        ]);

        PlatformSetting::set('mobile_app_setup_fee', $this->setupFee, 'float', 'Merchant Android App Compilation & Hosting Fee (NGN)');
        PlatformSetting::set('mobile_app_playstore_fee', $this->playstoreFee, 'float', 'Google Play Store Submission & Publishing Fee (NGN)');

        Notification::make()
            ->title('Mobile App Pricing Saved')
            ->body('New pricing is now live on all merchant store dashboards.')
            ->success()
            ->send();
    }
}
