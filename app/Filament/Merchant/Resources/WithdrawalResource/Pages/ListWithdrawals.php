<?php

namespace App\Filament\Merchant\Resources\WithdrawalResource\Pages;

use App\Filament\Merchant\Resources\WithdrawalResource;
use App\Models\ActivityLog;
use App\Models\SettlementAccount;
use App\Models\Withdrawal;
use App\Services\WalletService;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ListWithdrawals extends ListRecords
{
    protected static string $resource = WithdrawalResource::class;

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
            Action::make('request_withdrawal')
                ->label('Request Profit Payout')
                ->icon('heroicon-o-paper-airplane')
                ->color('success')
                ->mountUsing(function () {
                    $store = Filament::getTenant();

                    if (! $store) {
                        return;
                    }

                    $settlement = SettlementAccount::where('store_id', $store->id)
                        ->where('status', 'approved')
                        ->where('is_active', true)
                        ->first();

                    if (! $settlement) {
                        Notification::make()
                            ->title('Active Settlement Account Required')
                            ->body('Please submit your Settlement Bank Account under Settings -> Settlement Banks and wait for Super Admin approval before requesting profit payouts.')
                            ->warning()
                            ->persistent()
                            ->send();
                    }
                })
                ->form(function () {
                    $store = Filament::getTenant();
                    $settlement = $store ? SettlementAccount::where('store_id', $store->id)->where('status', 'approved')->where('is_active', true)->first() : null;

                    if (! $settlement) {
                        return [
                            Placeholder::make('warning')
                                ->content('⚠️ You do not have an active, approved Settlement Bank Account yet. Please go to Settings -> Settlement Banks to submit your payout bank details for approval.'),
                        ];
                    }

                    return [
                        Placeholder::make('payout_destination')
                            ->label('Verified Destination Bank Account')
                            ->content(sprintf('🏛️ %s | Account: %s (%s)', $settlement->bank_name, $settlement->account_number, $settlement->account_name)),
                        TextInput::make('amount')
                            ->label('Withdrawal Amount (₦)')
                            ->numeric()
                            ->prefix('₦')
                            ->rules(['required', 'numeric', 'gt:0']),
                    ];
                })
                ->action(function (array $data, WalletService $walletService): void {
                    $store = Filament::getTenant();

                    if (! $store) {
                        Notification::make()->title('No store context found')->danger()->send();

                        return;
                    }

                    $settlement = SettlementAccount::where('store_id', $store->id)
                        ->where('status', 'approved')
                        ->where('is_active', true)
                        ->first();

                    if (! $settlement) {
                        Notification::make()
                            ->title('Approved active Settlement Bank Account required.')
                            ->danger()
                            ->send();

                        return;
                    }

                    $profitWallet = $store->profitWallet();
                    $amount = (float) $data['amount'];
                    $currentBalance = (float) $profitWallet->balance;

                    if ($currentBalance < $amount) {
                        Notification::make()
                            ->title(sprintf('Insufficient Profit Balance. Available: ₦%s, Requested: ₦%s.', number_format($currentBalance, 2), number_format($amount, 2)))
                            ->danger()
                            ->send();

                        return;
                    }

                    try {
                        $reference = 'WTH_'.strtoupper(Str::random(10));

                        // 1. Debit Merchant Profit Wallet
                        $walletService->debit(
                            $profitWallet,
                            $amount,
                            'profit_withdrawal',
                            sprintf('Profit Payout Request to %s (%s - %s)', $settlement->account_name, $settlement->bank_name, $settlement->account_number),
                            [
                                'bank_name' => $settlement->bank_name,
                                'account_number' => $settlement->account_number,
                                'account_name' => $settlement->account_name,
                            ],
                            $reference
                        );

                        // 2. Create Withdrawal Record
                        Withdrawal::create([
                            'store_id' => $store->id,
                            'amount' => $amount,
                            'bank_name' => $settlement->bank_name,
                            'account_number' => $settlement->account_number,
                            'account_name' => $settlement->account_name,
                            'status' => 'pending',
                            'reference' => $reference,
                        ]);

                        // 3. Log Activity
                        ActivityLog::create([
                            'tenant_id' => $store->id,
                            'causer_type' => Auth::user() ? get_class(Auth::user()) : null,
                            'causer_id' => Auth::id(),
                            'event' => 'profit_withdrawal_requested',
                            'description' => sprintf('Requested ₦%s profit payout to %s (%s - %s)', number_format($amount, 2), $settlement->account_name, $settlement->bank_name, $settlement->account_number),
                            'properties' => [
                                'amount' => $amount,
                                'bank' => $settlement->bank_name,
                                'account_number' => $settlement->account_number,
                            ],
                        ]);

                        Notification::make()
                            ->title(sprintf('Payout Request for ₦%s submitted successfully!', number_format($amount, 2)))
                            ->success()
                            ->send();
                    } catch (\Throwable $e) {
                        Notification::make()
                            ->title('Withdrawal Failed: '.$e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),
        ];
    }
}
