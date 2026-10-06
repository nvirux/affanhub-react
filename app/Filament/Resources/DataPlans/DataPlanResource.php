<?php

namespace App\Filament\Resources\DataPlans;

use App\Models\DataPlan;
use App\Models\DataType;
use App\Models\Network;
use App\Models\Plan;
use App\Models\PlanDataPrice;
use BackedEnum;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use UnitEnum;

class DataPlanResource extends Resource
{
    protected static ?string $model = DataPlan::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCircleStack;

    protected static UnitEnum|string|null $navigationGroup = '⚡ VTU & Services';

    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('network_id')
                    ->label('Network')
                    ->options(Network::pluck('name', 'id'))
                    ->required()
                    ->searchable(),
                Select::make('data_type_id')
                    ->label('Data Type')
                    ->options(DataType::pluck('name', 'id'))
                    ->required()
                    ->searchable(),
                TextInput::make('name')
                    ->label('Plan Name')
                    ->required()
                    ->placeholder('e.g., 1.0 GB'),
                TextInput::make('size_mb')
                    ->label('Size (MB)')
                    ->numeric()
                    ->required()
                    ->placeholder('1024'),
                TextInput::make('validity')
                    ->label('Validity')
                    ->required()
                    ->placeholder('e.g., 30 Days, 24 Hours'),
                TextInput::make('cost_price')
                    ->label('Upstream Provider Cost (₦)')
                    ->numeric()
                    ->prefix('₦')
                    ->nullable()
                    ->placeholder('e.g., 210.00'),
                TextInput::make('selling_price')
                    ->label('Base Wholesale Price (₦)')
                    ->numeric()
                    ->prefix('₦')
                    ->nullable()
                    ->placeholder('e.g., 220.00'),
                TextInput::make('default_retail_price')
                    ->label('Default Customer Retail Price (₦)')
                    ->numeric()
                    ->prefix('₦')
                    ->required()
                    ->placeholder('e.g., 250.00'),
                TextInput::make('plan_code')
                    ->label('API Plan Code')
                    ->nullable()
                    ->placeholder('e.g., 1001'),
                Toggle::make('is_best_offer')
                    ->label('HOT 🔥 Best Offer / Featured')
                    ->default(false),
                Toggle::make('is_active')
                    ->label('Global Active Status')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('network.name')
                    ->label('Network')
                    ->badge()
                    ->color('info')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('name')
                    ->label('Plan Name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('dataType.name')
                    ->label('Type')
                    ->badge()
                    ->color('warning')
                    ->searchable(),
                TextColumn::make('cost_price')
                    ->label('Provider Cost (₦)')
                    ->state(fn (DataPlan $record) => $record->cost_price !== null ? '₦'.number_format((float) $record->cost_price, 2) : '—')
                    ->sortable(),
                TextInputColumn::make('selling_price')
                    ->label('Wholesale Price (₦)')
                    ->rules(['nullable', 'numeric', 'min:0'])
                    ->sortable(),
                TextInputColumn::make('default_retail_price')
                    ->label('Default Retail Price (₦)')
                    ->rules(['required', 'numeric', 'min:0'])
                    ->sortable(),
                TextColumn::make('validity')
                    ->label('Validity')
                    ->searchable(),
                ToggleColumn::make('is_best_offer')
                    ->label('HOT 🔥 Best Offer'),
                ToggleColumn::make('is_active')
                    ->label('Active'),
                TextColumn::make('created_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('network_id')
                    ->label('Network')
                    ->relationship('network', 'name'),
                SelectFilter::make('data_type_id')
                    ->label('Data Type')
                    ->relationship('dataType', 'name'),
                TernaryFilter::make('is_best_offer')
                    ->label('Best Offer / HOT 🔥'),
                TernaryFilter::make('is_active')
                    ->label('Active Status'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('bulk_set_tier_margins')
                        ->label('⚡ Set Tier Margins on Selected')
                        ->icon('heroicon-o-calculator')
                        ->color('warning')
                        ->form([
                            Radio::make('margin_strategy')
                                ->label('Profit Strategy')
                                ->options([
                                    'fixed' => 'Fixed Profit (+₦ above provider cost)',
                                    'percentage' => 'Percentage Profit (+% above provider cost)',
                                ])
                                ->default('fixed')
                                ->inline()
                                ->live()
                                ->required(),

                            Grid::make(3)
                                ->schema([
                                    TextInput::make('starter_margin')
                                        ->label('Starter Tier Margin')
                                        ->numeric()
                                        ->prefix(fn ($get) => $get('margin_strategy') === 'percentage' ? null : '₦')
                                        ->suffix(fn ($get) => $get('margin_strategy') === 'percentage' ? '%' : null)
                                        ->default(fn ($get) => $get('margin_strategy') === 'percentage' ? 8.0 : 15.00)
                                        ->helperText('Wholesale price for Starter merchants (and default DataPlan selling_price)')
                                        ->required(),

                                    TextInput::make('pro_margin')
                                        ->label('Pro Tier Margin')
                                        ->numeric()
                                        ->prefix(fn ($get) => $get('margin_strategy') === 'percentage' ? null : '₦')
                                        ->suffix(fn ($get) => $get('margin_strategy') === 'percentage' ? '%' : null)
                                        ->default(fn ($get) => $get('margin_strategy') === 'percentage' ? 5.0 : 10.00)
                                        ->helperText('Wholesale price for Pro merchants')
                                        ->required(),

                                    TextInput::make('enterprise_margin')
                                        ->label('Enterprise Tier Margin')
                                        ->numeric()
                                        ->prefix(fn ($get) => $get('margin_strategy') === 'percentage' ? null : '₦')
                                        ->suffix(fn ($get) => $get('margin_strategy') === 'percentage' ? '%' : null)
                                        ->default(fn ($get) => $get('margin_strategy') === 'percentage' ? 2.5 : 5.00)
                                        ->helperText('Wholesale price for Enterprise merchants')
                                        ->required(),
                                ]),

                            Grid::make(2)
                                ->schema([
                                    TextInput::make('retail_margin')
                                        ->label('Customer Retail Margin')
                                        ->numeric()
                                        ->prefix(fn ($get) => $get('margin_strategy') === 'percentage' ? null : '₦')
                                        ->suffix(fn ($get) => $get('margin_strategy') === 'percentage' ? '%' : null)
                                        ->default(fn ($get) => $get('margin_strategy') === 'percentage' ? 20.0 : 40.00)
                                        ->helperText('Default storefront selling price for end-users')
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
                                ]),
                        ])
                        ->action(function ($records, array $data): void {
                            $roundTo = $data['round_to'];
                            $strategy = $data['margin_strategy'];
                            $starterMargin = (float) $data['starter_margin'];
                            $proMargin = (float) $data['pro_margin'];
                            $enterpriseMargin = (float) $data['enterprise_margin'];
                            $retailMargin = (float) $data['retail_margin'];

                            $plansBySlug = Plan::whereIn('slug', ['starter', 'pro', 'enterprise'])->get()->keyBy('slug');
                            $count = 0;

                            foreach ($records as $record) {
                                $cost = (float) $record->cost_price;
                                if ($cost <= 0) {
                                    continue;
                                }

                                if ($strategy === 'percentage') {
                                    $newStarter = $cost * (1 + ($starterMargin / 100));
                                    $newPro = $cost * (1 + ($proMargin / 100));
                                    $newEnterprise = $cost * (1 + ($enterpriseMargin / 100));
                                    $newRetail = $cost * (1 + ($retailMargin / 100));
                                } else {
                                    $newStarter = $cost + $starterMargin;
                                    $newPro = $cost + $proMargin;
                                    $newEnterprise = $cost + $enterpriseMargin;
                                    $newRetail = $cost + $retailMargin;
                                }

                                if ($roundTo === '5') {
                                    $newStarter = round($newStarter / 5) * 5;
                                    $newPro = round($newPro / 5) * 5;
                                    $newEnterprise = round($newEnterprise / 5) * 5;
                                    $newRetail = round($newRetail / 5) * 5;
                                } elseif ($roundTo === '10') {
                                    $newStarter = round($newStarter / 10) * 10;
                                    $newPro = round($newPro / 10) * 10;
                                    $newEnterprise = round($newEnterprise / 10) * 10;
                                    $newRetail = round($newRetail / 10) * 10;
                                }

                                // Update DataPlan wholesale price (Starter rate) & default retail price
                                $record->update([
                                    'selling_price' => round($newStarter, 2),
                                    'default_retail_price' => round($newRetail, 2),
                                ]);

                                // Update PlanDataPrice for each tier
                                $tiers = [
                                    'starter' => $newStarter,
                                    'pro' => $newPro,
                                    'enterprise' => $newEnterprise,
                                ];

                                foreach ($tiers as $slug => $price) {
                                    $subPlan = $plansBySlug->get($slug);
                                    if ($subPlan) {
                                        PlanDataPrice::updateOrCreate(
                                            [
                                                'plan_id' => $subPlan->id,
                                                'data_plan_id' => $record->id,
                                            ],
                                            [
                                                'wholesale_price' => round($price, 2),
                                            ]
                                        );
                                    }
                                }

                                $count++;
                            }

                            Notification::make()
                                ->title('Tier Margins Applied!')
                                ->body("Updated wholesale tiers and retail prices for {$count} selected data plans.")
                                ->success()
                                ->send();
                        }),

                    BulkAction::make('bulk_adjust_prices')
                        ->label('Adjust Prices by +/- ₦')
                        ->icon('heroicon-o-arrows-up-down')
                        ->color('info')
                        ->form([
                            TextInput::make('wholesale_adjust')
                                ->label('Adjust Wholesale Price by (+/- ₦)')
                                ->numeric()
                                ->default(0.00)
                                ->helperText('Enter positive (e.g. 10) to increase, negative (e.g. -10) to decrease')
                                ->required(),
                            TextInput::make('retail_adjust')
                                ->label('Adjust Retail Price by (+/- ₦)')
                                ->numeric()
                                ->default(0.00)
                                ->helperText('Enter positive (e.g. 20) to increase, negative (e.g. -20) to decrease')
                                ->required(),
                        ])
                        ->action(function ($records, array $data): void {
                            $wAdjust = (float) $data['wholesale_adjust'];
                            $rAdjust = (float) $data['retail_adjust'];
                            $count = 0;

                            foreach ($records as $record) {
                                $newWholesale = max(0, (float) $record->selling_price + $wAdjust);
                                $newRetail = max(0, (float) $record->default_retail_price + $rAdjust);

                                $record->update([
                                    'selling_price' => round($newWholesale, 2),
                                    'default_retail_price' => round($newRetail, 2),
                                ]);
                                $count++;
                            }

                            Notification::make()
                                ->title('Prices Adjusted')
                                ->body("Adjusted prices for {$count} selected data plans.")
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
                            Notification::make()->title('Selected plans marked as Best Offer')->success()->send();
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
                    BulkAction::make('activate')
                        ->label('Activate Selected')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function ($records): void {
                            foreach ($records as $record) {
                                $record->update(['is_active' => true]);
                            }
                            Notification::make()->title('Selected plans activated')->success()->send();
                        }),
                    BulkAction::make('deactivate')
                        ->label('Deactivate Selected')
                        ->icon('heroicon-o-x-circle')
                        ->color('warning')
                        ->requiresConfirmation()
                        ->action(function ($records): void {
                            foreach ($records as $record) {
                                $record->update(['is_active' => false]);
                            }
                            Notification::make()->title('Selected plans deactivated')->success()->send();
                        }),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\PlanPricesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDataPlans::route('/'),
            'edit' => Pages\EditDataPlan::route('/{record}/edit'),
        ];
    }
}
