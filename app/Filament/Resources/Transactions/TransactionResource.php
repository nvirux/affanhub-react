<?php

namespace App\Filament\Resources\Transactions;

use App\Models\Transaction;
use App\Services\Vtu\VtuReconciliationService;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArrowsRightLeft;

    protected static string|\UnitEnum|null $navigationGroup = '📊 Finance & Platform';

    protected static ?string $navigationLabel = 'Service Transactions';

    protected static ?int $navigationSort = 1;

    public static function getNavigationBadge(): ?string
    {
        return (string) Transaction::count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'purple';
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('store.name')
                    ->label('Store')
                    ->placeholder('Platform Level')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('Customer')
                    ->placeholder('Tenant / System')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('service_type')
                    ->label('Service')
                    ->badge()
                    ->color('primary')
                    ->searchable(),
                TextColumn::make('amount')
                    ->money('NGN')
                    ->weight('bold')
                    ->sortable(),
                TextColumn::make('reference')
                    ->copyable()
                    ->searchable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'success', 'completed' => 'success',
                        'failed' => 'danger',
                        default => 'warning',
                    }),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('service_type')
                    ->options([
                        'airtime' => 'Airtime',
                        'data' => 'Data Bundle',
                        'cable' => 'Cable TV',
                        'electricity' => 'Electricity',
                    ]),
                SelectFilter::make('status')
                    ->options([
                        'success' => 'Success',
                        'failed' => 'Failed',
                        'pending' => 'Pending',
                    ]),
            ])
            ->actions([
                ViewAction::make()
                    ->form([
                        TextInput::make('reference')->label('Reference Code')->disabled(),
                        TextInput::make('store.name')->label('Storefront')->placeholder('Platform Level')->disabled(),
                        TextInput::make('user.name')->label('Customer')->placeholder('Direct / System')->disabled(),
                        TextInput::make('service_type')->label('Service Type')->disabled(),
                        TextInput::make('recipient')->label('Recipient (Phone / Account)')->disabled(),
                        TextInput::make('amount_paid')
                            ->label('Customer Paid (₦)')
                            ->formatStateUsing(fn ($state) => '₦'.number_format((float) $state, 2))
                            ->disabled(),
                        TextInput::make('cost_price')
                            ->label('Store Wholesale Cost (₦)')
                            ->formatStateUsing(fn ($state) => '₦'.number_format((float) $state, 2))
                            ->disabled(),
                        TextInput::make('profit')
                            ->label('Store Profit (₦)')
                            ->formatStateUsing(fn ($state) => '₦'.number_format((float) $state, 2))
                            ->disabled(),
                        TextInput::make('platform_profit')
                            ->label('Platform Profit (₦)')
                            ->formatStateUsing(fn ($state) => '₦'.number_format((float) $state, 2))
                            ->disabled(),
                        TextInput::make('status')->label('Status')->disabled(),
                        TextInput::make('created_at')->label('Created At')->disabled(),
                        Textarea::make('api_response')
                            ->label('Upstream Provider / Raw API Response (Super Admin)')
                            ->formatStateUsing(fn ($state) => is_array($state) ? json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) : $state)
                            ->rows(6)
                            ->columnSpanFull()
                            ->disabled(),
                    ]),
                Action::make('check_status')
                    ->label('Check Status')
                    ->icon('heroicon-o-arrow-path')
                    ->color('warning')
                    ->visible(fn (Transaction $record): bool => in_array(strtolower($record->status), ['pending', 'processing']))
                    ->action(function (Transaction $record, VtuReconciliationService $service) {
                        $result = $service->reconcile($record);
                        if ($result['status'] === 'successful') {
                            Notification::make()
                                ->title('Transaction Successful')
                                ->body('Order has been verified and marked as successful.')
                                ->success()
                                ->send();
                        } elseif ($result['status'] === 'failed') {
                            Notification::make()
                                ->title('Transaction Failed')
                                ->body('Provider reported failure. Customer wallet auto-refunded.')
                                ->danger()
                                ->send();
                        } else {
                            Notification::make()
                                ->title('Still Pending')
                                ->body($result['message'] ?? 'Transaction is still processing at provider.')
                                ->warning()
                                ->send();
                        }
                    }),
                Action::make('mark_as_delivered')
                    ->label('Mark Delivered & Debit')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->visible(fn (Transaction $record): bool => in_array(strtolower($record->status), ['failed', 'cancelled']))
                    ->requiresConfirmation()
                    ->modalHeading('Mark Transaction as Delivered & Recover Funds')
                    ->modalDescription(fn (Transaction $record) => "Are you sure this {$record->service_type} was actually delivered to {$record->recipient}? This will re-debit the customer's wallet (₦".number_format((float) $record->amount_paid, 2).') and store wholesale wallet (₦'.number_format((float) $record->cost_price, 2).'), sweep merchant profit, and update status to Successful.')
                    ->action(function (Transaction $record, VtuReconciliationService $service) {
                        $result = $service->markAsDelivered($record, true);
                        if ($result['success']) {
                            Notification::make()
                                ->title('Transaction Settled')
                                ->body($result['message'])
                                ->success()
                                ->send();
                        } else {
                            Notification::make()
                                ->title('Settlement Failed')
                                ->body($result['message'])
                                ->danger()
                                ->send();
                        }
                    }),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransactions::route('/'),
        ];
    }
}
