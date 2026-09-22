<?php

namespace App\Filament\Resources\Users\Tables;

use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->with(['store', 'wallets'])->withCount(['transactions', 'virtualAccounts']))
            ->columns([
                TextColumn::make('name')
                    ->label('Customer')
                    ->description(fn (User $record): ?string => $record->phone)
                    ->searchable(['name', 'phone'])
                    ->sortable()
                    ->weight('semibold'),

                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->copyable()
                    ->icon('heroicon-m-envelope')
                    ->color('gray'),

                TextColumn::make('store.name')
                    ->label('Store Tenant')
                    ->badge()
                    ->color('amber')
                    ->placeholder('Platform User')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('wallet_balance')
                    ->label('Wallet (₦)')
                    ->state(function (User $record): string {
                        $bal = $record->wallets->firstWhere('type', 'main')?->balance ?? 0.00;

                        return '₦'.number_format($bal, 2);
                    })
                    ->badge()
                    ->color(function (User $record): string {
                        $bal = $record->wallets->firstWhere('type', 'main')?->balance ?? 0.00;

                        return $bal > 0 ? 'success' : 'gray';
                    }),

                TextColumn::make('virtual_accounts_count')
                    ->label('Virtual Accts')
                    ->counts('virtualAccounts')
                    ->badge()
                    ->color('info')
                    ->alignCenter(),

                TextColumn::make('transactions_count')
                    ->label('Total Orders')
                    ->counts('transactions')
                    ->badge()
                    ->color('purple')
                    ->alignCenter(),

                TextColumn::make('kyc_status')
                    ->label('KYC Status')
                    ->badge()
                    ->state(function (User $record): string {
                        if (! empty($record->bvn)) {
                            return 'BVN Verified';
                        }
                        if (! empty($record->nin)) {
                            return 'NIN Verified';
                        }

                        return 'Unverified';
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'BVN Verified' => 'success',
                        'NIN Verified' => 'info',
                        default => 'gray',
                    }),

                TextColumn::make('is_active')
                    ->label('Status')
                    ->badge()
                    ->state(fn (User $record): string => $record->is_active !== false ? 'Active' : 'Suspended')
                    ->color(fn (string $state): string => match ($state) {
                        'Active' => 'success',
                        'Suspended' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('created_at')
                    ->label('Joined Date')
                    ->dateTime('M d, Y H:i')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                TernaryFilter::make('is_active')
                    ->label('Account Status')
                    ->trueLabel('Active Only')
                    ->falseLabel('Suspended Only')
                    ->queries(
                        true: fn (Builder $query) => $query->where('is_active', true),
                        false: fn (Builder $query) => $query->where('is_active', false),
                    ),

                SelectFilter::make('store_id')
                    ->label('Filter by Store')
                    ->relationship('store', 'name')
                    ->searchable()
                    ->preload(),

                TernaryFilter::make('has_bvn')
                    ->label('BVN Verified')
                    ->queries(
                        true: fn (Builder $query) => $query->whereNotNull('bvn')->where('bvn', '!=', ''),
                        false: fn (Builder $query) => $query->whereNull('bvn')->orWhere('bvn', ''),
                    ),

                Filter::make('has_balance')
                    ->label('Has Wallet Balance')
                    ->query(fn (Builder $query) => $query->whereHas('wallets', fn ($q) => $q->where('type', 'main')->where('balance', '>', 0))),
            ])
            ->recordActions([
                Action::make('suspend')
                    ->label('Ban / Suspend')
                    ->icon('heroicon-o-no-symbol')
                    ->color('danger')
                    ->visible(fn (User $record): bool => $record->is_active !== false)
                    ->requiresConfirmation()
                    ->modalHeading(fn (User $record): string => "Suspend {$record->name}")
                    ->modalDescription('Are you sure you want to suspend this user? They will be immediately logged out, active browser sessions will be terminated, and all purchases/logins blocked.')
                    ->action(function (User $record): void {
                        $record->ban('Suspended by Super Admin');

                        Notification::make()
                            ->title("User {$record->name} has been suspended and sessions terminated.")
                            ->danger()
                            ->send();
                    }),

                Action::make('activate')
                    ->label('Activate')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (User $record): bool => $record->is_active === false)
                    ->requiresConfirmation()
                    ->modalHeading(fn (User $record): string => "Reactivate {$record->name}")
                    ->modalDescription('Are you sure you want to restore access for this user?')
                    ->action(function (User $record): void {
                        $record->activate();

                        Notification::make()
                            ->title("User {$record->name} has been reactivated.")
                            ->success()
                            ->send();
                    }),

                ViewAction::make()
                    ->slideOver(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
