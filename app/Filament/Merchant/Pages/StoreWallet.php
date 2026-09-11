<?php

namespace App\Filament\Merchant\Pages;

use App\Services\Payment\PayMintService;
use BackedEnum;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;

class StoreWallet extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedWallet;

    protected static string|\UnitEnum|null $navigationGroup = 'Finance & Wallet';

    protected string $view = 'filament.merchant.pages.store-wallet';

    protected static ?string $title = 'Store Wallet & Funding';

    protected static ?string $navigationLabel = 'Wallet & Funding';

    protected static ?int $navigationSort = 1;

    public string $kycType = 'nin';

    public string $kycNumber = '';

    public string $phone = '';

    public function mount()
    {
        $store = Filament::getTenant();
        if ($store && $store->owner) {
            $this->phone = $store->owner->phone ?? '';
            $this->kycNumber = $store->owner->nin ?? $store->owner->bvn ?? '';
            if (! empty($store->owner->bvn) && empty($store->owner->nin)) {
                $this->kycType = 'bvn';
            }
        }
    }

    public function updatedKycType($value)
    {
        $store = Filament::getTenant();
        if ($store && $store->owner) {
            if ($value === 'nin' && ! empty($store->owner->nin)) {
                $this->kycNumber = $store->owner->nin;
            } elseif ($value === 'bvn' && ! empty($store->owner->bvn)) {
                $this->kycNumber = $store->owner->bvn;
            }
        }
    }

    public function generateVirtualAccount()
    {
        $this->validate([
            'kycType' => 'required|in:nin,bvn',
            'kycNumber' => 'required|string|size:11',
            'phone' => 'required|string',
        ]);

        $store = Filament::getTenant();

        if (! $store) {
            Notification::make()->title('Store not found.')->danger()->send();

            return;
        }

        try {
            // Save phone to owner model if missing
            if ($store->owner && empty($store->owner->phone) && ! empty($this->phone)) {
                $store->owner->phone = $this->phone;
                $store->owner->save();
            }

            $payMintService = app(PayMintService::class);
            $account = $payMintService->createVirtualAccount(
                $store,
                $this->kycType,
                $this->kycNumber,
                $this->phone,
                'AffanHub - '.$store->name
            );

            Notification::make()
                ->title('Store Virtual Account Created!')
                ->body("Bank: {$account->bank_name} | Account Number: {$account->account_number}")
                ->success()
                ->send();
        } catch (\Throwable $e) {
            Notification::make()
                ->title('Account Generation Failed')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }
}
