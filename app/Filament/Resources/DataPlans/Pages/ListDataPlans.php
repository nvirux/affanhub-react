<?php

namespace App\Filament\Resources\DataPlans\Pages;

use App\Filament\Resources\DataPlans\DataPlanResource;
use App\Models\DataPlan;
use App\Models\Network;
use App\Models\Plan;
use App\Models\PlanDataPrice;
use App\Services\Vtu\DataPlanSyncService;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListDataPlans extends ListRecords
{
    protected static string $resource = DataPlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('sync_provider')
                ->label('Sync Plans from Provider')
                ->icon('heroicon-o-arrow-path')
                ->color('primary')
                ->modalHeading('Sync Data Plans from VTU Provider')
                ->modalDescription('Configure what to sync from the upstream provider and how wholesale and retail prices should be calculated.')
                ->modalSubmitActionLabel('Sync Now')
                ->form([
                    Select::make('scope')
                        ->label('Sync Scope')
                        ->options([
                            'all' => 'Sync All (Import new plans & update existing)',
                            'new_only' => 'New Plans Only (Import new plans only; preserve existing prices)',
                            'costs_only' => 'Provider Costs Only (Refresh wholesale costs without changing selling prices)',
                        ])
                        ->default('all')
                        ->required()
                        ->live(),

                    Toggle::make('apply_pricing')
                        ->label('Apply Automated Profit Margins to Synced Plans')
                        ->helperText('Automatically calculate wholesale tier rates and customer retail prices from the provider cost.')
                        ->default(true)
                        ->live()
                        ->visible(fn ($get) => $get('scope') !== 'costs_only'),

                    Radio::make('margin_type')
                        ->label('Profit Strategy')
                        ->options([
                            'fixed' => 'Fixed Profit (+₦ above provider cost)',
                            'percentage' => 'Percentage Profit (+% above provider cost)',
                        ])
                        ->default('fixed')
                        ->inline()
                        ->live()
                        ->visible(fn ($get) => $get('scope') !== 'costs_only' && $get('apply_pricing')),

                    Grid::make(3)
                        ->schema([
                            TextInput::make('starter_margin')
                                ->label('Starter Tier Margin')
                                ->numeric()
                                ->prefix(fn ($get) => $get('margin_type') === 'percentage' ? null : '₦')
                                ->suffix(fn ($get) => $get('margin_type') === 'percentage' ? '%' : null)
                                ->default(fn ($get) => $get('margin_type') === 'percentage' ? 8.0 : 15.00)
                                ->helperText('Wholesale price for Starter merchants (and default DataPlan selling_price)')
                                ->required(),

                            TextInput::make('pro_margin')
                                ->label('Pro Tier Margin')
                                ->numeric()
                                ->prefix(fn ($get) => $get('margin_type') === 'percentage' ? null : '₦')
                                ->suffix(fn ($get) => $get('margin_type') === 'percentage' ? '%' : null)
                                ->default(fn ($get) => $get('margin_type') === 'percentage' ? 5.0 : 10.00)
                                ->helperText('Wholesale price for Pro merchants')
                                ->required(),

                            TextInput::make('enterprise_margin')
                                ->label('Enterprise Tier Margin')
                                ->numeric()
                                ->prefix(fn ($get) => $get('margin_type') === 'percentage' ? null : '₦')
                                ->suffix(fn ($get) => $get('margin_type') === 'percentage' ? '%' : null)
                                ->default(fn ($get) => $get('margin_type') === 'percentage' ? 2.5 : 5.00)
                                ->helperText('Wholesale price for Enterprise merchants')
                                ->required(),
                        ])
                        ->visible(fn ($get) => $get('scope') !== 'costs_only' && $get('apply_pricing')),

                    Grid::make(2)
                        ->schema([
                            TextInput::make('retail_margin')
                                ->label('Customer Retail Margin')
                                ->numeric()
                                ->prefix(fn ($get) => $get('margin_type') === 'percentage' ? null : '₦')
                                ->suffix(fn ($get) => $get('margin_type') === 'percentage' ? '%' : null)
                                ->default(fn ($get) => $get('margin_type') === 'percentage' ? 20.0 : 40.00)
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
                        ])
                        ->visible(fn ($get) => $get('scope') !== 'costs_only' && $get('apply_pricing')),

                    Toggle::make('delete_stale')
                        ->label('Remove Stale Plans')
                        ->helperText('Automatically delete plans that the provider no longer offers.')
                        ->default(true),
                ])
                ->action(function (DataPlanSyncService $syncService, array $data) {
                    $result = $syncService->sync(null, $data);

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
            Action::make('bulk_reprice_plans')
                ->label('Batch Apply Margins to Plans')
                ->icon('heroicon-o-calculator')
                ->color('warning')
                ->modalHeading('Batch Apply Profit Margins to Database Plans')
                ->modalDescription('Automatically recalculate wholesale prices (Starter, Pro, Enterprise) and customer retail prices across your existing plans based on their provider cost.')
                ->modalSubmitActionLabel('Recalculate & Apply Margins')
                ->form([
                    Select::make('target_scope')
                        ->label('Which Plans to Update')
                        ->options([
                            'zero_profit' => 'Only Plans with ₦0 or Negative Profit (Recommended)',
                            'all' => 'All Active Plans in the Database',
                        ])
                        ->default('zero_profit')
                        ->required(),

                    Select::make('network_id')
                        ->label('Network Filter')
                        ->options(function () {
                            $nets = Network::pluck('name', 'id')->toArray();

                            return ['all' => 'All Networks'] + $nets;
                        })
                        ->default('all')
                        ->required(),

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
                                ->helperText('Wholesale price for Starter merchants (and base selling_price)')
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
                ->action(function (array $data): void {
                    $targetScope = $data['target_scope'];
                    $networkId = $data['network_id'];
                    $strategy = $data['margin_strategy'];
                    $starterMargin = (float) $data['starter_margin'];
                    $proMargin = (float) $data['pro_margin'];
                    $enterpriseMargin = (float) $data['enterprise_margin'];
                    $retailMargin = (float) $data['retail_margin'];
                    $roundTo = $data['round_to'];

                    $query = DataPlan::where('cost_price', '>', 0);
                    if ($networkId !== 'all') {
                        $query->where('network_id', $networkId);
                    }

                    if ($targetScope === 'zero_profit') {
                        $query->whereRaw('COALESCE(selling_price, 0) <= COALESCE(cost_price, 0)');
                    }

                    $plans = $query->get();
                    $plansBySlug = Plan::whereIn('slug', ['starter', 'pro', 'enterprise'])->get()->keyBy('slug');
                    $count = 0;

                    foreach ($plans as $record) {
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

                        $record->update([
                            'selling_price' => round($newStarter, 2),
                            'default_retail_price' => round($newRetail, 2),
                        ]);

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
                        ->title('Profit Margins Applied!')
                        ->body("Successfully updated {$count} data plans with your profit margins.")
                        ->success()
                        ->send();
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
