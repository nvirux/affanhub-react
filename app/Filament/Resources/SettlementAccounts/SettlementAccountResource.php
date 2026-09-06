<?php

namespace App\Filament\Resources\SettlementAccounts;

use App\Filament\Resources\SettlementAccounts\Pages\ListSettlementAccounts;
use App\Models\ActivityLog;
use App\Models\SettlementAccount;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use UnitEnum;

class SettlementAccountResource extends Resource
{
    protected static ?string $model = SettlementAccount::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingLibrary;

    protected static UnitEnum|string|null $navigationGroup = 'Platform Management';

    protected static ?string $navigationLabel = 'Merchant Settlement Banks';

    protected static ?string $modelLabel = 'Settlement Bank Account';

    protected static ?int $navigationSort = 6;

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')
                    ->label('Submitted At')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('store.name')
                    ->label('Store')
                    ->badge()
                    ->color('info')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('store.owner.name')
                    ->label('Merchant Name')
                    ->searchable(),
                TextColumn::make('bank_name')
                    ->label('Bank Name')
                    ->searchable()
                    ->weight('bold'),
                TextColumn::make('account_number')
                    ->label('Account Number')
                    ->searchable()
                    ->copyable()
                    ->badge()
                    ->color('info'),
                TextColumn::make('account_name')
                    ->label('Account Holder Name')
                    ->searchable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'approved' => 'success',
                        'rejected' => 'danger',
                        default => 'warning',
                    })
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'approved' => '🟢 Approved',
                        'rejected' => '🔴 Rejected',
                        default => '🟡 Pending Approval',
                    })
                    ->sortable(),
                IconColumn::make('is_active')
                    ->label('Active Payout Bank')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-badge')
                    ->falseIcon('heroicon-o-x-mark')
                    ->trueColor('success')
                    ->falseColor('gray'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending Approval',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ]),
            ])
            ->recordActions([
                Action::make('approve_account')
                    ->label('Approve Bank Details')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (SettlementAccount $record) => $record->status !== 'approved')
                    ->action(function (SettlementAccount $record): void {
                        // Clean up: Delete all old replaced / rejected accounts for this store
                        SettlementAccount::where('store_id', $record->store_id)
                            ->where('id', '!=', $record->id)
                            ->delete();

                        // Activate and approve current account
                        $record->update([
                            'status' => 'approved',
                            'is_active' => true,
                            'admin_notes' => null,
                        ]);

                        ActivityLog::create([
                            'tenant_id' => $record->store_id,
                            'causer_type' => Auth::guard('admin')->user() ? get_class(Auth::guard('admin')->user()) : null,
                            'causer_id' => Auth::guard('admin')->id(),
                            'event' => 'settlement_account_approved',
                            'description' => sprintf('Super Admin approved settlement bank account for %s (%s - %s)', $record->store->name ?? 'Store', $record->bank_name, $record->account_number),
                            'properties' => [
                                'account_number' => $record->account_number,
                                'bank_name' => $record->bank_name,
                            ],
                        ]);

                        Notification::make()
                            ->title(sprintf('Approved bank details for %s! Old accounts cleaned up.', $record->store->name ?? 'Store'))
                            ->success()
                            ->send();
                    }),
                Action::make('reject_account')
                    ->label('Reject Account')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->form([
                        Textarea::make('reason')
                            ->label('Rejection Reason')
                            ->required()
                            ->placeholder('e.g., Account name mismatch with business registration'),
                    ])
                    ->visible(fn (SettlementAccount $record) => $record->status !== 'rejected')
                    ->action(function (SettlementAccount $record, array $data): void {
                        $record->update([
                            'status' => 'rejected',
                            'is_active' => false,
                            'admin_notes' => $data['reason'],
                        ]);

                        ActivityLog::create([
                            'tenant_id' => $record->store_id,
                            'causer_type' => Auth::guard('admin')->user() ? get_class(Auth::guard('admin')->user()) : null,
                            'causer_id' => Auth::guard('admin')->id(),
                            'event' => 'settlement_account_rejected',
                            'description' => sprintf('Super Admin rejected settlement bank account for %s. Reason: %s', $record->store->name ?? 'Store', $data['reason']),
                            'properties' => [
                                'reason' => $data['reason'],
                            ],
                        ]);

                        Notification::make()
                            ->title(sprintf('Rejected bank details for %s.', $record->store->name ?? 'Store'))
                            ->warning()
                            ->send();
                    }),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSettlementAccounts::route('/'),
        ];
    }
}
