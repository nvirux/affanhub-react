<?php

namespace App\Filament\Resources\Stores\Tables;

use App\Models\Store;
use App\Services\WalletService;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;

class StoresTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('Store Slug')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                
                TextColumn::make('owner.name')
                    ->label('Owner (Merchant)')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('main_wallet')
                    ->label('Main Capital (₦)')
                    ->badge()
                    ->color('info')
                    ->state(fn (Store $record) => '₦' . number_format((float) ($record->mainWallet()->balance ?? 0), 2)),

                TextColumn::make('profit_wallet')
                    ->label('Profit Wallet (₦)')
                    ->badge()
                    ->color('success')
                    ->state(fn (Store $record) => '₦' . number_format((float) ($record->profitWallet()->balance ?? 0), 2)),

                TextColumn::make('activeSubscription.plan.name')
                    ->label('Active Plan')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'Starter' => 'gray',
                        'Pro' => 'primary',
                        'Enterprise' => 'success',
                        default => 'danger',
                    })
                    ->placeholder('No Active Plan'),
                
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'suspended' => 'danger',
                        default => 'warning',
                    })
                    ->sortable(),
                
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'suspended' => 'Suspended',
                        'pending' => 'Pending',
                    ]),
            ])
            ->recordActions([
                Action::make('fund_store_wallet')
                    ->label('Fund Store Capital')
                    ->icon('heroicon-o-banknotes')
                    ->color('warning')
                    ->form([
                        Select::make('operation')
                            ->label('Action Type')
                            ->options([
                                'credit' => 'Credit (+ Add Capital)',
                                'debit' => 'Debit (- Remove Capital)',
                            ])
                            ->default('credit')
                            ->required(),
                        TextInput::make('amount')
                            ->label('Amount (₦)')
                            ->numeric()
                            ->prefix('₦')
                            ->rules(['required', 'numeric', 'gt:0']),
                        TextInput::make('reason')
                            ->label('Reason / Bank Reference')
                            ->required()
                            ->placeholder('e.g., Bank Transfer / Wholesale Top-up Received'),
                    ])
                    ->action(function (Store $record, array $data, WalletService $walletService): void {
                        $mainWallet = $record->mainWallet();
                        $amount = (float) $data['amount'];
                        $reason = $data['reason'];

                        try {
                            if ($data['operation'] === 'credit') {
                                $walletService->credit(
                                    $mainWallet,
                                    $amount,
                                    'manual_admin_credit',
                                    $reason,
                                    [
                                        'channel' => 'manual',
                                        'funded_by' => 'Super Admin',
                                        'store_name' => $record->name,
                                    ]
                                );
                                Notification::make()
                                    ->title(sprintf('Successfully credited ₦%s capital to %s', number_format($amount, 2), $record->name))
                                    ->success()
                                    ->send();
                            } else {
                                $walletService->debit(
                                    $mainWallet,
                                    $amount,
                                    'manual_admin_debit',
                                    $reason,
                                    [
                                        'channel' => 'manual',
                                        'funded_by' => 'Super Admin',
                                        'store_name' => $record->name,
                                    ]
                                );
                                Notification::make()
                                    ->title(sprintf('Successfully debited ₦%s capital from %s', number_format($amount, 2), $record->name))
                                    ->warning()
                                    ->send();
                            }
                        } catch (\Throwable $e) {
                            Notification::make()
                                ->title('Operation Failed: ' . $e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
