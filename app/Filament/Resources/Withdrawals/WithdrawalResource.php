<?php

namespace App\Filament\Resources\Withdrawals;

use App\Filament\Resources\Withdrawals\Pages\ListWithdrawals;
use App\Models\ActivityLog;
use App\Models\Withdrawal;
use App\Services\WalletService;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use UnitEnum;

class WithdrawalResource extends Resource
{
    protected static ?string $model = Withdrawal::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;

    protected static UnitEnum|string|null $navigationGroup = 'Platform Management';

    protected static ?string $navigationLabel = 'Merchant Profit Payouts';

    protected static ?string $modelLabel = 'Merchant Payout';

    protected static ?int $navigationSort = 5;

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')
                    ->label('Requested At')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('store.name')
                    ->label('Store')
                    ->badge()
                    ->color('info')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('store.owner.name')
                    ->label('Merchant')
                    ->searchable(),
                TextColumn::make('amount')
                    ->label('Amount (₦)')
                    ->money('NGN')
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('bank_name')
                    ->label('Bank Name')
                    ->searchable(),
                TextColumn::make('account_number')
                    ->label('Account Number')
                    ->searchable()
                    ->copyable(),
                TextColumn::make('account_name')
                    ->label('Account Name')
                    ->searchable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'completed' => 'success',
                        'approved' => 'info',
                        'rejected' => 'danger',
                        default => 'warning',
                    })
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'completed' => 'Completed',
                        'rejected' => 'Rejected',
                    ]),
            ])
            ->recordActions([
                Action::make('approve_payout')
                    ->label('Approve & Mark Paid')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (Withdrawal $record) => $record->status === 'pending')
                    ->action(function (Withdrawal $record): void {
                        $record->update(['status' => 'completed']);

                        ActivityLog::create([
                            'tenant_id' => $record->store_id,
                            'causer_type' => Auth::guard('admin')->user() ? get_class(Auth::guard('admin')->user()) : null,
                            'causer_id' => Auth::guard('admin')->id(),
                            'event' => 'profit_withdrawal_completed',
                            'description' => sprintf('Super Admin approved and marked ₦%s payout as completed for %s (%s)', number_format($record->amount, 2), $record->account_name, $record->bank_name),
                            'properties' => [
                                'amount' => $record->amount,
                                'reference' => $record->reference,
                            ],
                        ]);

                        Notification::make()
                            ->title(sprintf('Payout #%s marked as Completed!', $record->reference))
                            ->success()
                            ->send();
                    }),
                Action::make('reject_payout')
                    ->label('Reject & Refund')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->form([
                        Textarea::make('reason')
                            ->label('Rejection Reason')
                            ->required()
                            ->placeholder('e.g., Invalid account name or bank details mismatch'),
                    ])
                    ->visible(fn (Withdrawal $record) => $record->status === 'pending')
                    ->action(function (Withdrawal $record, array $data, WalletService $walletService): void {
                        $store = $record->store;

                        if ($store) {
                            $profitWallet = $store->profitWallet();
                            $walletService->credit(
                                $profitWallet,
                                (float) $record->amount,
                                'profit_withdrawal_refund',
                                sprintf('Refund for Rejected Payout Request #%s (%s)', $record->reference, $data['reason']),
                                ['rejection_reason' => $data['reason']]
                            );
                        }

                        $record->update([
                            'status' => 'rejected',
                            'admin_notes' => $data['reason'],
                        ]);

                        ActivityLog::create([
                            'tenant_id' => $record->store_id,
                            'causer_type' => Auth::guard('admin')->user() ? get_class(Auth::guard('admin')->user()) : null,
                            'causer_id' => Auth::guard('admin')->id(),
                            'event' => 'profit_withdrawal_rejected',
                            'description' => sprintf('Super Admin rejected ₦%s payout request for %s and refunded profit wallet. Reason: %s', number_format($record->amount, 2), $record->store->name ?? 'Store', $data['reason']),
                            'properties' => [
                                'amount' => $record->amount,
                                'reason' => $data['reason'],
                            ],
                        ]);

                        Notification::make()
                            ->title(sprintf('Payout #%s rejected and refunded to merchant profit wallet.', $record->reference))
                            ->warning()
                            ->send();
                    }),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListWithdrawals::route('/'),
        ];
    }
}
