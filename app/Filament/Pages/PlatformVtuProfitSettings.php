<?php

namespace App\Filament\Pages;

use App\Models\PlatformSetting;
use BackedEnum;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;

class PlatformVtuProfitSettings extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShieldCheck;

    protected static string|\UnitEnum|null $navigationGroup = 'Platform & Settings';

    protected string $view = 'filament.pages.platform-vtu-profit-settings';

    protected static ?string $title = 'VTU Guaranteed Profit & Margin Engine';

    protected static ?string $navigationLabel = 'VTU Profit Margins';

    protected static ?int $navigationSort = 11;

    public float $starterMargin = 15.0;

    public float $proMargin = 10.0;

    public float $enterpriseMargin = 5.0;

    public float $absoluteFloor = 5.0;

    public float $merchantMargin = 15.0;

    public float $airtimeMarginPercent = 0.5;

    // Simulation inputs for live preview
    public float $simCost = 245.0;

    public float $simAirtimeAmount = 1000.0;

    public float $simAirtimeVendorDiscount = 3.0;

    public function mount(): void
    {
        $this->starterMargin = (float) PlatformSetting::get('vtu_min_profit_starter', 15.00);
        $this->proMargin = (float) PlatformSetting::get('vtu_min_profit_pro', 10.00);
        $this->enterpriseMargin = (float) PlatformSetting::get('vtu_min_profit_enterprise', 5.00);
        $this->absoluteFloor = (float) PlatformSetting::get('vtu_min_platform_profit_floor', 5.00);
        $this->merchantMargin = (float) PlatformSetting::get('vtu_min_merchant_margin', 15.00);
        $this->airtimeMarginPercent = (float) PlatformSetting::get('vtu_min_airtime_profit_percent', 0.50);
    }

    public function save(): void
    {
        $this->validate([
            'starterMargin' => 'required|numeric|min:0|max:1000',
            'proMargin' => 'required|numeric|min:0|max:1000',
            'enterpriseMargin' => 'required|numeric|min:0|max:1000',
            'absoluteFloor' => 'required|numeric|min:0|max:1000',
            'merchantMargin' => 'required|numeric|min:0|max:1000',
            'airtimeMarginPercent' => 'required|numeric|min:0|max:10',
        ]);

        PlatformSetting::set('vtu_min_profit_starter', $this->starterMargin, 'float', 'Minimum platform profit floor for Starter tier data plans (NGN)');
        PlatformSetting::set('vtu_min_profit_pro', $this->proMargin, 'float', 'Minimum platform profit floor for Pro tier data plans (NGN)');
        PlatformSetting::set('vtu_min_profit_enterprise', $this->enterpriseMargin, 'float', 'Minimum platform profit floor for Enterprise tier data plans (NGN)');
        PlatformSetting::set('vtu_min_platform_profit_floor', $this->absoluteFloor, 'float', 'Absolute safety profit floor for any data transaction (NGN)');
        PlatformSetting::set('vtu_min_merchant_margin', $this->merchantMargin, 'float', 'Minimum reseller margin between retail and wholesale (NGN)');
        PlatformSetting::set('vtu_min_airtime_profit_percent', $this->airtimeMarginPercent, 'float', 'Minimum platform profit margin kept on airtime discounts (%)');

        Notification::make()
            ->title('VTU Profit Margin Floor Updated')
            ->body('Guaranteed minimum profit engine settings have been saved and applied across all transactions.')
            ->success()
            ->send();
    }
}
