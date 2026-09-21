<?php

namespace App\Filament\Resources\DataPlans\Pages;

use App\Filament\Resources\DataPlans\DataPlanResource;
use App\Models\DataPlan;
use App\Models\DataType;
use App\Models\Network;
use App\Models\Plan;
use App\Models\PlanDataPrice;
use App\Services\Vtu\DataPlanSyncService;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListDataPlans extends ListRecords
{
    protected static string $resource = DataPlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('bulk_price_generator')
                ->label('⚡ Quick Price Generator')
                ->icon('heroicon-o-bolt')
                ->color('warning')
                ->modalHeading('⚡ Bulk Data Plan Price Generator')
                ->modalDescription('Calculate and update wholesale and retail prices across data plans automatically without manual typing.')
                ->modalSubmitActionLabel('Apply Prices')
                ->form([
                    Select::make('network_id')
                        ->label('Target Network')
                        ->options(function () {
                            return ['all' => '🌐 All Networks'] + Network::where('is_active', true)->pluck('name', 'id')->toArray();
                        })
                        ->default('all')
                        ->required(),

                    Select::make('data_type_id')
                        ->label('Target Data Type')
                        ->options(function () {
                            return ['all' => '📁 All Data Types'] + DataType::where('is_active', true)->pluck('name', 'id')->toArray();
                        })
                        ->default('all')
                        ->required(),

                    Radio::make('pricing_mode')
                        ->label('Pricing Strategy')
                        ->options([
                            'margin_on_cost' => 'Fixed Profit Margin on Provider Cost (+₦)',
                            'per_gb_rate' => 'Target Rate Per 1.0 GB (e.g. ₦235/GB)',
                            'percentage_margin' => 'Percentage Markup on Provider Cost (+%)',
                        ])
                        ->default('margin_on_cost')
                        ->live()
                        ->required(),

                    // Margin on Cost Inputs
                    TextInput::make('wholesale_margin')
                        ->label('Wholesale Margin (+₦ on Provider Cost)')
                        ->numeric()
                        ->prefix('₦')
                        ->default(10.00)
                        ->visible(fn ($get) => $get('pricing_mode') === 'margin_on_cost')
                        ->helperText('Your profit when selling to merchants (Wholesale = Cost + Margin)')
                        ->required(fn ($get) => $get('pricing_mode') === 'margin_on_cost'),

                    TextInput::make('retail_margin')
                        ->label('Default Retail Margin (+₦ on Provider Cost)')
                        ->numeric()
                        ->prefix('₦')
                        ->default(35.00)
                        ->visible(fn ($get) => $get('pricing_mode') === 'margin_on_cost')
                        ->helperText('Default suggested price for merchant end-users (Retail = Cost + Margin)')
                        ->required(fn ($get) => $get('pricing_mode') === 'margin_on_cost'),

                    // Per GB Rate Inputs
                    TextInput::make('wholesale_rate_per_gb')
                        ->label('Wholesale Rate Per 1.0 GB (₦)')
                        ->numeric()
                        ->prefix('₦')
                        ->default(235.00)
                        ->visible(fn ($get) => $get('pricing_mode') === 'per_gb_rate')
                        ->helperText('Auto-calculates all sizes: 500MB, 1GB, 2GB, 5GB, 10GB proportionally')
                        ->required(fn ($get) => $get('pricing_mode') === 'per_gb_rate'),

                    TextInput::make('retail_rate_per_gb')
                        ->label('Default Retail Rate Per 1.0 GB (₦)')
                        ->numeric()
                        ->prefix('₦')
                        ->default(270.00)
                        ->visible(fn ($get) => $get('pricing_mode') === 'per_gb_rate')
                        ->helperText('Auto-calculates customer retail prices proportionally')
                        ->required(fn ($get) => $get('pricing_mode') === 'per_gb_rate'),

                    // Percentage Markup Inputs
                    TextInput::make('wholesale_percent')
                        ->label('Wholesale Markup (+%)')
                        ->numeric()
                        ->suffix('%')
                        ->default(5.0)
                        ->visible(fn ($get) => $get('pricing_mode') === 'percentage_margin')
                        ->required(fn ($get) => $get('pricing_mode') === 'percentage_margin'),

                    TextInput::make('retail_percent')
                        ->label('Default Retail Markup (+%)')
                        ->numeric()
                        ->suffix('%')
                        ->default(15.0)
                        ->visible(fn ($get) => $get('pricing_mode') === 'percentage_margin')
                        ->required(fn ($get) => $get('pricing_mode') === 'percentage_margin'),

                    Select::make('round_to')
                        ->label('Price Rounding')
                        ->options([
                            'none' => 'Exact Amount (No Rounding)',
                            '5' => 'Round to Nearest ₦5 (e.g. ₦235, ₦240)',
                            '10' => 'Round to Nearest ₦10 (e.g. ₦240, ₦250)',
                        ])
                        ->default('5')
                        ->required(),
                ])
                ->action(function (array $data): void {
                    $query = DataPlan::query();

                    if ($data['network_id'] !== 'all') {
                        $query->where('network_id', $data['network_id']);
                    }

                    if ($data['data_type_id'] !== 'all') {
                        $query->where('data_type_id', $data['data_type_id']);
                    }

                    $plans = $query->get();
                    $mode = $data['pricing_mode'];
                    $roundTo = $data['round_to'];
                    $count = 0;

                    foreach ($plans as $plan) {
                        $cost = (float) $plan->cost_price;
                        $sizeGb = $plan->size_mb > 0 ? ((float) $plan->size_mb / 1024) : 1.0;

                        $newWholesale = 0.0;
                        $newRetail = 0.0;

                        if ($mode === 'margin_on_cost') {
                            $newWholesale = $cost + (float) $data['wholesale_margin'];
                            $newRetail = $cost + (float) $data['retail_margin'];
                        } elseif ($mode === 'per_gb_rate') {
                            $newWholesale = $sizeGb * (float) $data['wholesale_rate_per_gb'];
                            $newRetail = $sizeGb * (float) $data['retail_rate_per_gb'];
                        } elseif ($mode === 'percentage_margin') {
                            $newWholesale = $cost * (1 + ((float) $data['wholesale_percent'] / 100));
                            $newRetail = $cost * (1 + ((float) $data['retail_percent'] / 100));
                        }

                        // Rounding
                        if ((string) $roundTo === '5') {
                            $newWholesale = round($newWholesale / 5) * 5;
                            $newRetail = round($newRetail / 5) * 5;
                        } elseif ((string) $roundTo === '10') {
                            $newWholesale = round($newWholesale / 10) * 10;
                            $newRetail = round($newRetail / 10) * 10;
                        }

                        $plan->update([
                            'selling_price' => round($newWholesale, 2),
                            'default_retail_price' => round($newRetail, 2),
                        ]);

                        $count++;
                    }

                    Notification::make()
                        ->title('Bulk Pricing Applied!')
                        ->body("Successfully updated prices across {$count} data plans.")
                        ->success()
                        ->send();
                }),

            Action::make('bulk_tier_pricing_generator')
                ->label('⚡ Tier Pricing Matrix (Starter / Pro / Enterprise)')
                ->icon('heroicon-o-table-cells')
                ->color('info')
                ->modalHeading('⚡ Subscription Tier Wholesale Price Matrix')
                ->modalDescription('Automatically generate wholesale prices for Starter, Pro, Enterprise, and all membership tiers across data plans in 1 click.')
                ->modalSubmitActionLabel('Apply All Tier Prices')
                ->form(function () {
                    $activePlans = Plan::where('is_active', true)->orderBy('price_monthly', 'asc')->get();

                    $fields = [
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

                        Radio::make('pricing_strategy')
                            ->label('Tier Pricing Strategy')
                            ->options([
                                'per_gb_rate' => 'Target Rate Per 1.0 GB by Tier (e.g. Starter: ₦230, Pro: ₦224, Enterprise: ₦218)',
                                'margin_on_cost' => 'Profit Margin on Provider Cost by Tier (+₦ on Cost)',
                                'discount_from_base' => 'Discount off Base Wholesale Price by Tier (-₦ from Base)',
                            ])
                            ->default('per_gb_rate')
                            ->live()
                            ->required(),
                    ];

                    foreach ($activePlans as $index => $plan) {
                        $defaultGbRate = match ($index) {
                            0 => 230.00,
                            1 => 224.00,
                            default => 218.00,
                        };
                        $defaultMargin = match ($index) {
                            0 => 15.00,
                            1 => 10.00,
                            default => 5.00,
                        };
                        $defaultDiscount = match ($index) {
                            0 => 0.00,
                            1 => 3.00,
                            default => 6.00,
                        };

                        $fields[] = TextInput::make("rate_plan_{$plan->id}")
                            ->label("{$plan->name} Tier Rate Per 1.0 GB (₦)")
                            ->numeric()
                            ->prefix('₦')
                            ->default($defaultGbRate)
                            ->visible(fn ($get) => $get('pricing_strategy') === 'per_gb_rate')
                            ->helperText("Stores subscribed to {$plan->name} will purchase at this rate scaled by plan size")
                            ->required(fn ($get) => $get('pricing_strategy') === 'per_gb_rate');

                        $fields[] = TextInput::make("margin_plan_{$plan->id}")
                            ->label("{$plan->name} Margin (+₦ on Provider Cost)")
                            ->numeric()
                            ->prefix('₦')
                            ->default($defaultMargin)
                            ->visible(fn ($get) => $get('pricing_strategy') === 'margin_on_cost')
                            ->required(fn ($get) => $get('pricing_strategy') === 'margin_on_cost');

                        $fields[] = TextInput::make("discount_plan_{$plan->id}")
                            ->label("{$plan->name} Discount off Base Wholesale (-₦)")
                            ->numeric()
                            ->prefix('₦')
                            ->default($defaultDiscount)
                            ->visible(fn ($get) => $get('pricing_strategy') === 'discount_from_base')
                            ->required(fn ($get) => $get('pricing_strategy') === 'discount_from_base');
                    }

                    $fields[] = Select::make('round_to')
                        ->label('Price Rounding')
                        ->options([
                            'none' => 'Exact Amount (No Rounding)',
                            '5' => 'Round to Nearest ₦5 (e.g. ₦220, ₦225)',
                            '10' => 'Round to Nearest ₦10 (e.g. ₦220, ₦230)',
                        ])
                        ->default('5')
                        ->required();

                    return $fields;
                })
                ->action(function (array $data): void {
                    $activePlans = Plan::where('is_active', true)->get();
                    $query = DataPlan::query();

                    if ($data['network_id'] !== 'all') {
                        $query->where('network_id', $data['network_id']);
                    }

                    if ($data['data_type_id'] !== 'all') {
                        $query->where('data_type_id', $data['data_type_id']);
                    }

                    $dataPlans = $query->get();
                    $strategy = $data['pricing_strategy'];
                    $roundTo = $data['round_to'];
                    $updatedCount = 0;

                    foreach ($dataPlans as $dataPlan) {
                        $sizeGb = $dataPlan->size_mb > 0 ? ((float) $dataPlan->size_mb / 1024) : 1.0;
                        $cost = (float) $dataPlan->cost_price;
                        $baseWholesale = (float) ($dataPlan->selling_price ?? $dataPlan->default_retail_price ?? 0.0);

                        foreach ($activePlans as $plan) {
                            $tierPrice = 0.0;

                            if ($strategy === 'per_gb_rate') {
                                $rate = (float) ($data["rate_plan_{$plan->id}"] ?? 230.00);
                                $tierPrice = $sizeGb * $rate;
                            } elseif ($strategy === 'margin_on_cost') {
                                $margin = (float) ($data["margin_plan_{$plan->id}"] ?? 10.00);
                                $tierPrice = $cost + $margin;
                            } elseif ($strategy === 'discount_from_base') {
                                $discount = (float) ($data["discount_plan_{$plan->id}"] ?? 0.00);
                                $tierPrice = max($cost, $baseWholesale - $discount);
                            }

                            if ((string) $roundTo === '5') {
                                $tierPrice = round($tierPrice / 5) * 5;
                            } elseif ((string) $roundTo === '10') {
                                $tierPrice = round($tierPrice / 10) * 10;
                            }

                            PlanDataPrice::updateOrCreate(
                                [
                                    'plan_id' => $plan->id,
                                    'data_plan_id' => $dataPlan->id,
                                ],
                                [
                                    'wholesale_price' => round($tierPrice, 2),
                                ]
                            );

                            $updatedCount++;
                        }
                    }

                    Notification::make()
                        ->title('⚡ Tier Wholesale Prices Generated!')
                        ->body("Successfully updated {$updatedCount} tier prices across {$dataPlans->count()} data plans and {$activePlans->count()} membership tiers.")
                        ->success()
                        ->send();
                }),

            Action::make('sync_provider')
                ->label('Sync Plans from Provider')
                ->icon('heroicon-o-arrow-path')
                ->color('primary')
                ->requiresConfirmation()
                ->modalHeading('Sync Data Plans from VTU Provider')
                ->modalDescription('This will query the provider API (GET /data/plans) to fetch and update all data plans, wholesale costs, validity, and availability. Continue?')
                ->modalSubmitActionLabel('Sync Now')
                ->action(function (DataPlanSyncService $syncService) {
                    $result = $syncService->sync();

                    if ($result['success']) {
                        Notification::make()
                            ->title('Sync Complete')
                            ->body($result['message'])
                            ->success()
                            ->send();
                    } else {
                        Notification::make()
                            ->title('Sync Failed')
                            ->body($result['message'])
                            ->danger()
                            ->persistent()
                            ->send();
                    }
                }),
            CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        $tabs = [
            'all' => Tab::make('All Networks')
                ->badge(DataPlan::count()),
        ];

        $networks = Network::where('is_active', true)->get();

        foreach ($networks as $network) {
            $netId = $network->id;
            $tabs['net_'.$netId] = Tab::make($network->name)
                ->modifyQueryUsing(fn (Builder $query) => $query->where('network_id', $netId))
                ->badge(DataPlan::where('network_id', $netId)->count());
        }

        return $tabs;
    }
}
