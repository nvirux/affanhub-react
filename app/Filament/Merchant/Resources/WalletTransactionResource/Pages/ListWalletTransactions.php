<?php

namespace App\Filament\Merchant\Resources\WalletTransactionResource\Pages;

use App\Filament\Merchant\Resources\WalletTransactionResource;
use App\Models\Store;
use App\Models\User;
use App\Models\Wallet;
use Filament\Facades\Filament;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListWalletTransactions extends ListRecords
{
    protected static string $resource = WalletTransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function getTableQuery(): ?Builder
    {
        $store = Filament::getTenant();

        if (! $store) {
            return parent::getTableQuery()->whereRaw('1 = 0');
        }

        // 1. Store Main & Profit Wallets
        $storeWalletIds = Wallet::where('holder_type', Store::class)
            ->where('holder_id', $store->id)
            ->pluck('id')
            ->toArray();

        // 2. Customer Wallets registered under this Store
        $customerUserIds = $store->users()->pluck('users.id')->toArray();

        $customerWalletIds = Wallet::where('holder_type', User::class)
            ->whereIn('holder_id', $customerUserIds)
            ->pluck('id')
            ->toArray();

        $allWalletIds = array_unique(array_merge($storeWalletIds, $customerWalletIds));

        return parent::getTableQuery()->whereIn('wallet_id', $allWalletIds);
    }
}
