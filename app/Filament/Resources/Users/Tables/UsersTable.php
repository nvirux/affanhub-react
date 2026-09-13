<?php

namespace App\Filament\Resources\Users\Tables;

use App\Models\User;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
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

                TextColumn::make('created_at')
                    ->label('Joined Date')
                    ->dateTime('M d, Y H:i')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
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
                ViewAction::make()
                    ->slideOver(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
