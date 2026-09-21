<?php

namespace App\Filament\Merchant\Resources;

use App\Filament\Merchant\Resources\StoreDataPlanResource\Pages\ListStoreDataPlans;
use App\Models\StoreDataPlan;
use BackedEnum;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use UnitEnum;

class StoreDataPlanResource extends Resource
{
    protected static ?string $model = StoreDataPlan::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCircleStack;

    protected static string|UnitEnum|null $navigationGroup = 'Products & Pricing';

    protected static ?string $navigationLabel = 'Data Plan Pricing';

    protected static ?string $modelLabel = 'Store Data Plan';

    protected static ?string $tenantRelationshipName = 'storeDataPlans';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('selling_price')
                ->label('Custom Store Price (₦)')
                ->numeric()
                ->prefix('₦')
                ->required(),
            Toggle::make('is_enabled')
                ->label('Enable for Store Customers')
                ->default(true),
            Toggle::make('is_best_offer')
                ->label('HOT 🔥 Best Offer Badge on Storefront')
                ->default(false),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('dataPlan.network.name')
                    ->label('Network')
                    ->badge()
                    ->color('info')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('dataPlan.name')
                    ->label('Plan Name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('dataPlan.dataType.name')
                    ->label('Data Type')
                    ->badge()
                    ->color('warning')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('dataPlan.validity')
                    ->label('Validity')
                    ->searchable(),
                TextColumn::make('wholesale_cost')
                    ->label('Your Cost (₦)')
                    ->badge()
                    ->color('purple')
                    ->state(fn (StoreDataPlan $record) => '₦'.number_format($record->getWholesaleCost(), 2)),
                TextInputColumn::make('selling_price')
                    ->label('Your Store Price (₦)')
                    ->rules(['required', 'numeric', 'min:0'])
                    ->sortable(),
                TextColumn::make('est_profit')
                    ->label('Est. Profit (₦)')
                    ->badge()
                    ->color('success')
                    ->state(fn (StoreDataPlan $record) => '₦'.number_format((float) $record->selling_price - $record->getWholesaleCost(), 2)),
                ToggleColumn::make('is_best_offer')
                    ->label('HOT 🔥 Best Offer'),
                ToggleColumn::make('is_enabled')
                    ->label('Store Enabled'),
            ])
            ->filters([
                SelectFilter::make('network')
                    ->label('Network')
                    ->relationship('dataPlan.network', 'name'),
                SelectFilter::make('data_type')
                    ->label('Data Type')
                    ->relationship('dataPlan.dataType', 'name'),
                TernaryFilter::make('is_best_offer')
                    ->label('HOT 🔥 Best Offer'),
                TernaryFilter::make('is_enabled')
                    ->label('Store Enabled'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('bulk_set_profit')
                        ->label('⚡ Set Profit on Selected (+₦)')
                        ->icon('heroicon-o-currency-dollar')
                        ->color('success')
                        ->form([
                            TextInput::make('profit_amount')
                                ->label('Profit Margin (+₦ on Wholesale Cost)')
                                ->numeric()
                                ->prefix('₦')
                                ->default(50.00)
                                ->required(),
                            Select::make('round_to')
                                ->label('Price Rounding')
                                ->options([
                                    'none' => 'Exact Decimals',
                                    '5' => 'Nearest ₦5 (e.g. ₦285, ₦290)',
                                    '10' => 'Nearest ₦10 (e.g. ₦280, ₦290)',
                                ])
                                ->default('5')
                                ->required(),
                        ])
                        ->action(function ($records, array $data): void {
                            $profit = (float) $data['profit_amount'];
                            $roundTo = $data['round_to'];
                            $count = 0;

                            foreach ($records as $record) {
                                $cost = $record->getWholesaleCost();
                                $newPrice = $cost + $profit;

                                if ($roundTo === '5') {
                                    $newPrice = round($newPrice / 5) * 5;
                                } elseif ($roundTo === '10') {
                                    $newPrice = round($newPrice / 10) * 10;
                                }

                                $record->update([
                                    'selling_price' => round($newPrice, 2),
                                ]);
                                $count++;
                            }

                            Notification::make()
                                ->title('Profit Applied')
                                ->body("Updated prices for {$count} selected store plans.")
                                ->success()
                                ->send();
                        }),

                    BulkAction::make('bulk_adjust_selling_price')
                        ->label('Adjust Store Price by +/- ₦')
                        ->icon('heroicon-o-arrows-up-down')
                        ->color('info')
                        ->form([
                            TextInput::make('adjust_amount')
                                ->label('Adjust Customer Price by (+/- ₦)')
                                ->numeric()
                                ->default(0.00)
                                ->helperText('Enter positive (e.g. 20) to increase, negative (e.g. -20) to decrease')
                                ->required(),
                        ])
                        ->action(function ($records, array $data): void {
                            $adjust = (float) $data['adjust_amount'];
                            $count = 0;

                            foreach ($records as $record) {
                                $newPrice = max(0, (float) $record->selling_price + $adjust);

                                $record->update([
                                    'selling_price' => round($newPrice, 2),
                                ]);
                                $count++;
                            }

                            Notification::make()
                                ->title('Prices Adjusted')
                                ->body("Adjusted customer prices for {$count} selected store plans.")
                                ->success()
                                ->send();
                        }),

                    BulkAction::make('mark_best_offer')
                        ->label('Set as HOT 🔥 Best Offer')
                        ->icon('heroicon-o-fire')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->action(function ($records): void {
                            foreach ($records as $record) {
                                $record->update(['is_best_offer' => true]);
                            }
                            Notification::make()->title('Selected plans set as Best Offer')->success()->send();
                        }),
                    BulkAction::make('remove_best_offer')
                        ->label('Remove Best Offer Status')
                        ->icon('heroicon-o-minus')
                        ->color('gray')
                        ->requiresConfirmation()
                        ->action(function ($records): void {
                            foreach ($records as $record) {
                                $record->update(['is_best_offer' => false]);
                            }
                            Notification::make()->title('Selected plans removed from Best Offers')->success()->send();
                        }),
                    BulkAction::make('enable')
                        ->label('Enable Selected')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function ($records): void {
                            foreach ($records as $record) {
                                $record->update(['is_enabled' => true]);
                            }
                            Notification::make()->title('Selected plans enabled')->success()->send();
                        }),
                    BulkAction::make('disable')
                        ->label('Disable Selected')
                        ->icon('heroicon-o-x-circle')
                        ->color('warning')
                        ->requiresConfirmation()
                        ->action(function ($records): void {
                            foreach ($records as $record) {
                                $record->update(['is_enabled' => false]);
                            }
                            Notification::make()->title('Selected plans disabled')->success()->send();
                        }),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListStoreDataPlans::route('/'),
        ];
    }
}
