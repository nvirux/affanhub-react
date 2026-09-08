<?php

namespace App\Filament\Merchant\Resources\SettlementAccountResource\Pages;

use App\Filament\Merchant\Resources\SettlementAccountResource;
use App\Models\ActivityLog;
use App\Models\SettlementAccount;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class ListSettlementAccounts extends ListRecords
{
    protected static string $resource = SettlementAccountResource::class;

    public static function getNigerianBanks(): array
    {
        return [
            'Access Bank' => 'Access Bank',
            'Access Bank (Diamond)' => 'Access Bank (Diamond)',
            'Ecobank Nigeria' => 'Ecobank Nigeria',
            'Fidelity Bank' => 'Fidelity Bank',
            'First Bank of Nigeria' => 'First Bank of Nigeria',
            'First City Monument Bank (FCMB)' => 'First City Monument Bank (FCMB)',
            'Globus Bank' => 'Globus Bank',
            'Guaranty Trust Bank (GTBank)' => 'Guaranty Trust Bank (GTBank)',
            'Heritage Bank' => 'Heritage Bank',
            'Keystone Bank' => 'Keystone Bank',
            'Kuda Microfinance Bank' => 'Kuda Microfinance Bank',
            'Moniepoint Microfinance Bank' => 'Moniepoint Microfinance Bank',
            'OPay Digital Services' => 'OPay Digital Services',
            'PalmPay' => 'PalmPay',
            'Polaris Bank' => 'Polaris Bank',
            'PremiumTrust Bank' => 'PremiumTrust Bank',
            'Providus Bank' => 'Providus Bank',
            'Stanbic IBTC Bank' => 'Stanbic IBTC Bank',
            'Standard Chartered Bank' => 'Standard Chartered Bank',
            'Sterling Bank' => 'Sterling Bank',
            'SunTrust Bank' => 'SunTrust Bank',
            'Titan Trust Bank' => 'Titan Trust Bank',
            'Union Bank of Nigeria' => 'Union Bank of Nigeria',
            'United Bank for Africa (UBA)' => 'United Bank for Africa (UBA)',
            'Unity Bank' => 'Unity Bank',
            'VFD Microfinance Bank' => 'VFD Microfinance Bank',
            'Wema Bank (ALAT)' => 'Wema Bank (ALAT)',
            'Zenith Bank' => 'Zenith Bank',
        ];
    }

    protected function getTableQuery(): ?Builder
    {
        $store = Filament::getTenant();

        if (! $store) {
            return parent::getTableQuery()->whereRaw('1 = 0');
        }

        return parent::getTableQuery()->where('store_id', $store->id);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('add_settlement_account')
                ->label('Add Settlement Bank')
                ->icon('heroicon-o-plus')
                ->color('primary')
                ->visible(function () {
                    $store = Filament::getTenant();
                    if (! $store) {
                        return true;
                    }

                    return ! SettlementAccount::where('store_id', $store->id)
                        ->where('status', 'pending')
                        ->exists();
                })
                ->form([
                    Select::make('bank_name')
                        ->label('Select Nigerian Bank')
                        ->options(self::getNigerianBanks())
                        ->searchable()
                        ->required()
                        ->placeholder('Search & select your bank...'),
                    TextInput::make('account_number')
                        ->label('10-Digit NUBAN Account Number')
                        ->required()
                        ->length(10)
                        ->regex('/^\d{10}$/')
                        ->validationMessages([
                            'regex' => 'Account number must be exactly 10 digits.',
                            'length' => 'Account number must be exactly 10 digits.',
                        ])
                        ->placeholder('0123456789'),
                    TextInput::make('account_name')
                        ->label('Account Holder Name')
                        ->required()
                        ->maxLength(255)
                        ->placeholder('e.g., John Doe Enterprises'),
                ])
                ->action(function (array $data): void {
                    $store = Filament::getTenant();

                    if (! $store) {
                        Notification::make()->title('No store context found')->danger()->send();

                        return;
                    }

                    // Guard: Check if store already has a pending account
                    $hasPending = SettlementAccount::where('store_id', $store->id)
                        ->where('status', 'pending')
                        ->exists();

                    if ($hasPending) {
                        Notification::make()
                            ->title('Submission Blocked')
                            ->body('You already have a pending bank account verification request in progress.')
                            ->danger()
                            ->send();

                        return;
                    }

                    // Create new settlement account record in pending status
                    SettlementAccount::create([
                        'store_id' => $store->id,
                        'bank_name' => $data['bank_name'],
                        'account_number' => $data['account_number'],
                        'account_name' => $data['account_name'],
                        'status' => 'pending',
                        'is_active' => false,
                    ]);

                    ActivityLog::create([
                        'tenant_id' => $store->id,
                        'causer_type' => Auth::user() ? get_class(Auth::user()) : null,
                        'causer_id' => Auth::id(),
                        'event' => 'settlement_account_submitted',
                        'description' => sprintf('Submitted new settlement bank account for approval: %s (%s - %s)', $data['account_name'], $data['bank_name'], $data['account_number']),
                        'properties' => $data,
                    ]);

                    Notification::make()
                        ->title('New Bank Account Submitted!')
                        ->body(sprintf('Bank details for %s (%s - %s) are pending Super Admin approval. Your existing active account remains unchanged until approved.', $data['account_name'], $data['bank_name'], $data['account_number']))
                        ->success()
                        ->persistent()
                        ->send();
                }),
        ];
    }
}
