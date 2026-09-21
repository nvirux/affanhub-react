<?php

namespace App\Filament\Resources\Plans\Tables;

use App\Models\DataPlan;
use App\Models\DataType;
use App\Models\Network;
use App\Models\Plan;
use App\Models\PlanDataPrice;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PlansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('slug')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('price_monthly')
                    ->label('Monthly Price')
                    ->money('NGN')
                    ->placeholder('Custom/Contract')
                    ->sortable(),

                TextColumn::make('price_yearly')
                    ->label('Yearly Price')
                    ->money('NGN')
                    ->placeholder('Custom/Contract')
                    ->sortable(),

                TextColumn::make('trial_days')
                    ->label('Trial (Days)')
                    ->suffix(' days')
                    ->sortable(),

                IconColumn::make('is_active')
                    ->label('Active Status')
                    ->boolean()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('is_active')
                    ->label('Status')
                    ->options([
                        '1' => 'Active',
                        '0' => 'Inactive',
                    ]),
            ])
            ->recordActions([
                Action::make('set_data_prices')
                    ->label('⚡ Set Data Prices')
                    ->icon('heroicon-o-bolt')
                    ->color('warning')
                    ->modalHeading(fn (Plan $record) => "⚡ Set Data Wholesale Rates for {$record->name} Tier")
                    ->modalDescription(fn (Plan $record) => "Configure wholesale purchase prices for stores subscribed to {$record->name}.")
                    ->modalSubmitActionLabel('Apply Rates')
                    ->form([
                        Select::make('network_id')
                            ->label('Target Network')
                            ->options(['all' => '🌐 All Networks'] + Network::where('is_active', true)->pluck('name', 'id')->toArray())
                            ->default('all')
                            ->required(),

                        Select::make('data_type_id')
                            ->label('Target Data Type')
                            ->options(['all' => '📁 All Data Types'] + DataType::where('is_active', true)->pluck('name', 'id')->toArray())
                            ->default('all')
                            ->required(),

                        Radio::make('pricing_mode')
                            ->label('Pricing Strategy')
                            ->options([
                                'per_gb_rate' => 'Target Rate Per 1.0 GB (e.g. ₦225/GB)',
                                'margin_on_cost' => 'Profit Margin on Provider Cost (+₦)',
                                'discount_from_base' => 'Discount off Base Wholesale Price (-₦)',
                            ])
                            ->default('per_gb_rate')
                            ->live()
                            ->required(),

                        TextInput::make('rate_per_gb')
                            ->label('Rate Per 1.0 GB (₦)')
                            ->numeric()
                            ->prefix('₦')
                            ->default(225.00)
                            ->visible(fn ($get) => $get('pricing_mode') === 'per_gb_rate')
                            ->helperText('Scales all sizes (500MB, 1GB, 2GB, 5GB, 10GB) proportionally')
                            ->required(fn ($get) => $get('pricing_mode') === 'per_gb_rate'),

                        TextInput::make('margin_on_cost')
                            ->label('Wholesale Margin (+₦ on Provider Cost)')
                            ->numeric()
                            ->prefix('₦')
                            ->default(10.00)
                            ->visible(fn ($get) => $get('pricing_mode') === 'margin_on_cost')
                            ->required(fn ($get) => $get('pricing_mode') === 'margin_on_cost'),

                        TextInput::make('discount_from_base')
                            ->label('Discount off Base Wholesale (-₦)')
                            ->numeric()
                            ->prefix('₦')
                            ->default(4.00)
                            ->visible(fn ($get) => $get('pricing_mode') === 'discount_from_base')
                            ->required(fn ($get) => $get('pricing_mode') === 'discount_from_base'),

                        Select::make('round_to')
                            ->label('Price Rounding')
                            ->options([
                                'none' => 'Exact Amount (No Rounding)',
                                '5' => 'Round to Nearest ₦5 (e.g. ₦220, ₦225)',
                                '10' => 'Round to Nearest ₦10 (e.g. ₦220, ₦230)',
                            ])
                            ->default('5')
                            ->required(),
                    ])
                    ->action(function (Plan $record, array $data): void {
                        $query = DataPlan::query();

                        if ($data['network_id'] !== 'all') {
                            $query->where('network_id', $data['network_id']);
                        }

                        if ($data['data_type_id'] !== 'all') {
                            $query->where('data_type_id', $data['data_type_id']);
                        }

                        $dataPlans = $query->get();
                        $mode = $data['pricing_mode'];
                        $roundTo = $data['round_to'];
                        $count = 0;

                        foreach ($dataPlans as $dataPlan) {
                            $sizeGb = $dataPlan->size_mb > 0 ? ((float) $dataPlan->size_mb / 1024) : 1.0;
                            $cost = (float) $dataPlan->cost_price;
                            $baseWholesale = (float) ($dataPlan->selling_price ?? $dataPlan->default_retail_price ?? 0.0);
                            $tierPrice = 0.0;

                            if ($mode === 'per_gb_rate') {
                                $tierPrice = $sizeGb * (float) $data['rate_per_gb'];
                            } elseif ($mode === 'margin_on_cost') {
                                $tierPrice = $cost + (float) $data['margin_on_cost'];
                            } elseif ($mode === 'discount_from_base') {
                                $tierPrice = max($cost, $baseWholesale - (float) $data['discount_from_base']);
                            }

                            if ((string) $roundTo === '5') {
                                $tierPrice = round($tierPrice / 5) * 5;
                            } elseif ((string) $roundTo === '10') {
                                $tierPrice = round($tierPrice / 10) * 10;
                            }

                            PlanDataPrice::updateOrCreate(
                                [
                                    'plan_id' => $record->id,
                                    'data_plan_id' => $dataPlan->id,
                                ],
                                [
                                    'wholesale_price' => round($tierPrice, 2),
                                ]
                            );

                            $count++;
                        }

                        Notification::make()
                            ->title('Wholesale Prices Updated!')
                            ->body("Successfully configured {$count} data plan prices for {$record->name} tier.")
                            ->success()
                            ->send();
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
