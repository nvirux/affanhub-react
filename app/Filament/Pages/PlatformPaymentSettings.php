<?php

namespace App\Filament\Pages;

use App\Models\PlatformSetting;
use BackedEnum;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;

class PlatformPaymentSettings extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;

    protected static string|\UnitEnum|null $navigationGroup = '📊 Finance & Platform';

    protected string $view = 'filament.pages.platform-payment-settings';

    protected static ?string $title = 'Platform Payment & Gateway Settings';

    protected static ?string $navigationLabel = 'Gateway Settings';

    protected static ?int $navigationSort = 10;

    public string $feeType = 'percentage';

    public float $feePercent = 1.0;

    public float $feeFlat = 0.0;

    public float $adminMarkupPercent = 0.0;

    public float $maxFeeCap = 100.0;

    public function mount(): void
    {
        $this->feeType = PlatformSetting::get('paymint_fee_type', 'percentage');
        $this->feePercent = (float) PlatformSetting::get('paymint_fee_percent', 1.0);
        $this->feeFlat = (float) PlatformSetting::get('paymint_fee_flat', 0.0);
        $this->adminMarkupPercent = (float) PlatformSetting::get('paymint_admin_markup_percent', 0.0);
        $this->maxFeeCap = (float) PlatformSetting::get('paymint_max_fee_cap', 100.0);
    }

    public function save(): void
    {
        $this->validate([
            'feeType' => 'required|in:percentage,flat',
            'feePercent' => 'required|numeric|min:0|max:10',
            'feeFlat' => 'required|numeric|min:0|max:500',
            'adminMarkupPercent' => 'required|numeric|min:0|max:10',
            'maxFeeCap' => 'required|numeric|min:0|max:1000',
        ]);

        PlatformSetting::set('paymint_fee_type', $this->feeType, 'string', 'PayMint Gateway Base Fee Type (percentage or flat)');
        PlatformSetting::set('paymint_fee_percent', $this->feePercent, 'float', 'PayMint Gateway Base Fee Percentage');
        PlatformSetting::set('paymint_fee_flat', $this->feeFlat, 'float', 'PayMint Gateway Base Flat Fee in NGN');
        PlatformSetting::set('paymint_admin_markup_percent', $this->adminMarkupPercent, 'float', 'Platform Admin Markup on deposits');
        PlatformSetting::set('paymint_max_fee_cap', $this->maxFeeCap, 'float', 'Maximum Gateway Fee Cap in NGN');

        Notification::make()
            ->title('Platform Payment Settings Saved')
            ->body('New deposit fee configuration is now live for all stores.')
            ->success()
            ->send();
    }
}
