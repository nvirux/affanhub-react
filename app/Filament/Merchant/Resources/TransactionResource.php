<?php

namespace App\Filament\Merchant\Resources;

use App\Filament\Merchant\Resources\TransactionResource\Pages\ListTransactions;
use App\Models\Transaction;
use BackedEnum;
use Filament\Actions\ViewAction;
use Filament\Facades\Filament;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use UnitEnum;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShoppingBag;

    protected static string|UnitEnum|null $navigationGroup = 'Sales & Orders';

    protected static ?string $navigationLabel = 'Orders & Transactions';

    protected static ?string $modelLabel = 'Order';

    protected static ?string $tenantRelationshipName = 'transactions';

    protected static ?string $tenantOwnershipRelationshipName = 'store';

    protected static ?int $navigationSort = 1;

    public static function getNavigationBadge(): ?string
    {
        $store = Filament::getTenant();

        return $store ? (string) $store->transactions()->count() : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'primary';
    }

    public static function table(Table $table): Table
    {
        return $table
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
                    ->searchable()
                    ->sortable(),

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
                    ->weight('bold')
                    ->sortable(),

                TextColumn::make('cost_price')
                    ->label('Wholesale Cost')
                    ->money('NGN')
                    ->color('gray')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('profit')
                    ->label('Store Profit')
                    ->money('NGN')
                    ->color('success')
                    ->weight('semibold')
                    ->sortable(),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match (strtolower($state)) {
                        'success', 'completed' => 'success',
                        'failed' => 'danger',
                        'pending', 'processing' => 'warning',
                        default => 'gray',
                    })
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
                        TextInput::make('service_type')->label('Service Type')->disabled(),
                        TextInput::make('recipient')->label('Recipient / Account')->disabled(),
                        TextInput::make('amount_paid')
                            ->label('Customer Paid (₦)')
                            ->formatStateUsing(fn ($state) => '₦'.number_format((float) $state, 2))
                            ->disabled(),
                        TextInput::make('profit')
                            ->label('Store Net Profit (₦)')
                            ->formatStateUsing(fn ($state) => '₦'.number_format((float) $state, 2))
                            ->disabled(),
                        TextInput::make('cost_price')
                            ->label('Wholesale Network Cost (₦)')
                            ->formatStateUsing(fn ($state) => '₦'.number_format((float) $state, 2))
                            ->disabled(),
                        TextInput::make('status')->label('Status')->disabled(),
                        TextInput::make('created_at')->label('Timestamp')->disabled(),
                        Textarea::make('api_response')
                            ->label('Provider / API Response')
                            ->formatStateUsing(fn ($state) => is_array($state) ? json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) : $state)
                            ->rows(4)
                            ->columnSpanFull()
                            ->disabled(),
                    ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTransactions::route('/'),
        ];
    }
}
