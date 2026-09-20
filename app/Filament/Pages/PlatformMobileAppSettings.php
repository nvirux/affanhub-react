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

    public bool $builderEnabled = true;

    public bool $requireCustomDomain = false;

    public string $allowedPlans = 'all';

    public function mount(): void
    {
        $this->setupFee = (float) PlatformSetting::get('mobile_app_setup_fee', 15000.00);
        $this->playstoreFee = (float) PlatformSetting::get('mobile_app_playstore_fee', 30000.00);
        $this->builderEnabled = (bool) PlatformSetting::get('mobile_app_builder_enabled', true);
        $this->requireCustomDomain = (bool) PlatformSetting::get('mobile_app_require_custom_domain', false);
        $this->allowedPlans = (string) PlatformSetting::get('mobile_app_allowed_plans', 'all');
    }

    public function save(): void
    {
        $this->validate([
            'setupFee' => 'required|numeric|min:0|max:1000000',
            'playstoreFee' => 'required|numeric|min:0|max:1000000',
            'builderEnabled' => 'required|boolean',
            'requireCustomDomain' => 'required|boolean',
            'allowedPlans' => 'required|string|in:all,pro_and_above,enterprise_only',
        ]);

        PlatformSetting::set('mobile_app_setup_fee', $this->setupFee, 'float', 'Merchant Android App Compilation & Hosting Fee (NGN)');
        PlatformSetting::set('mobile_app_playstore_fee', $this->playstoreFee, 'float', 'Google Play Store Submission & Publishing Fee (NGN)');
        PlatformSetting::set('mobile_app_builder_enabled', $this->builderEnabled, 'boolean', 'Master toggle to enable/disable mobile app builder for merchants');
        PlatformSetting::set('mobile_app_require_custom_domain', $this->requireCustomDomain, 'boolean', 'Require stores to have an active verified custom domain before ordering an app');
        PlatformSetting::set('mobile_app_allowed_plans', $this->allowedPlans, 'string', 'Minimum subscription plan required to create mobile apps');

        Notification::make()
            ->title('Mobile App Settings Saved')
            ->body('App builder settings and restrictions are now live across all merchant stores.')
            ->success()
            ->send();
    }
}
