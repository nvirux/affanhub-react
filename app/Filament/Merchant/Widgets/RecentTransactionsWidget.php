<?php

namespace App\Filament\Merchant\Widgets;

use App\Models\Transaction;
use Filament\Facades\Filament;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentTransactionsWidget extends BaseWidget
{
    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        $store = Filament::getTenant();

        return $table
            ->query(
                $store
                    ? Transaction::query()->where('store_id', $store->id)->with(['user'])->latest()
                    : Transaction::query()->whereRaw('1 = 0')
            )
            ->heading('Recent Store Transactions')
            ->description('Latest retail airtime, data, electricity, and cable orders placed by customers')
            ->columns([
                TextColumn::make('created_at')
                    ->label('Date & Time')
                    ->dateTime('M d, Y H:i')
                    ->sortable(),

                TextColumn::make('reference')
                    ->label('Reference')
                    ->searchable()
                    ->copyable()
                    ->weight('bold'),

                TextColumn::make('user.name')
                    ->label('Customer')
                    ->placeholder('Guest / Direct')
                    ->icon('heroicon-m-user'),

                TextColumn::make('service_type')
                    ->label('Service')
                    ->badge()
                    ->color(fn (string $state): string => match (strtolower($state)) {
                        'airtime' => 'success',
                        'data' => 'info',
                        'cable' => 'warning',
                        'electricity' => 'danger',
                        default => 'primary',
                    })
                    ->icon(fn (string $state): string => match (strtolower($state)) {
                        'airtime' => 'heroicon-m-phone',
                        'data' => 'heroicon-m-wifi',
                        'cable' => 'heroicon-m-tv',
                        'electricity' => 'heroicon-m-bolt',
                        default => 'heroicon-m-shopping-bag',
                    })
                    ->formatStateUsing(fn ($state) => strtoupper($state)),

                TextColumn::make('recipient')
                    ->label('Beneficiary')
                    ->searchable()
                    ->copyable()
                    ->placeholder('N/A'),

                TextColumn::make('amount_paid')
                    ->label('Customer Paid')
                    ->money('NGN')
                    ->weight('bold'),

                TextColumn::make('profit')
                    ->label('Store Profit')
                    ->money('NGN')
                    ->color('success')
                    ->weight('semibold'),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match (strtolower($state)) {
                        'success', 'completed' => 'success',
                        'failed' => 'danger',
                        'pending', 'processing' => 'warning',
                        default => 'gray',
                    }),
            ])
            ->emptyStateHeading('No transactions yet')
            ->emptyStateDescription('When customers buy data, airtime, or utility bills on your storefront, orders will appear here.')
            ->emptyStateIcon('heroicon-o-shopping-bag')
            ->defaultPaginationPageOption(5)
            ->paginated([5, 10, 25]);
    }
}
