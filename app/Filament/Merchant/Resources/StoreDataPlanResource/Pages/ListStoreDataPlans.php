<?php

namespace App\Filament\Merchant\Resources\StoreDataPlanResource\Pages;

use App\Filament\Merchant\Resources\StoreDataPlanResource;
use App\Models\DataPlan;
use App\Models\Network;
use App\Models\StoreDataPlan;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListStoreDataPlans extends ListRecords
{
    protected static string $resource = StoreDataPlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('bulk_set_margins')
                ->label('⚡ Set My Profit Margins (Bulk)')
                ->icon('heroicon-o-bolt')
                ->color('success')
                ->modalHeading('⚡ Set My Store Profit Margins')
                ->modalDescription('Quickly calculate and update your store selling prices across all data plans with a single profit rule.')
                ->modalSubmitActionLabel('Apply Store Prices')
                ->form([
                    Select::make('network_id')
                        ->label('Network Filter')
                        ->options(function () {
                            return ['all' => '🌐 All Networks'] + Network::where('is_active', true)->pluck('name', 'id')->toArray();
                        })
                        ->default('all')
                        ->required(),

                    Radio::make('margin_strategy')
                        ->label('Profit Strategy')
                        ->options([
                            'fixed_profit' => 'Fixed Profit (+₦ above wholesale cost)',
                            'per_gb_rate' => 'Target Selling Price per 1.0 GB (e.g. ₦280/GB)',
                            'percentage' => 'Percentage Profit (+% on wholesale cost)',
                        ])
                        ->default('fixed_profit')
                        ->live()
                        ->required(),

                    TextInput::make('profit_amount')
                        ->label('Your Profit Margin (+₦)')
                        ->numeric()
                        ->prefix('₦')
                        ->default(50.00)
                        ->visible(fn ($get) => $get('margin_strategy') === 'fixed_profit')
                        ->helperText('This amount will be added on top of your wholesale purchase cost.')
                        ->required(fn ($get) => $get('margin_strategy') === 'fixed_profit'),

                    TextInput::make('gb_rate')
                        ->label('Customer Selling Rate Per 1.0 GB (₦)')
                        ->numeric()
                        ->prefix('₦')
                        ->default(280.00)
                        ->visible(fn ($get) => $get('margin_strategy') === 'per_gb_rate')
                        ->helperText('Auto-calculates retail price for all plan sizes (500MB, 1GB, 2GB, 5GB, etc.)')
                        ->required(fn ($get) => $get('margin_strategy') === 'per_gb_rate'),

                    TextInput::make('percentage_rate')
                        ->label('Percentage Profit Margin (+%)')
                        ->numeric()
                        ->suffix('%')
                        ->default(15.0)
                        ->visible(fn ($get) => $get('margin_strategy') === 'percentage')
                        ->required(fn ($get) => $get('margin_strategy') === 'percentage'),

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
                ->action(function (array $data): void {
                    $store = Filament::getTenant();
                    if (! $store) {
                        return;
                    }

                    $query = StoreDataPlan::where('store_id', $store->id);
                    if ($data['network_id'] !== 'all') {
                        $query->whereHas('dataPlan', function ($q) use ($data) {
                            $q->where('network_id', $data['network_id']);
                        });
                    }

                    $plans = $query->with('dataPlan')->get();
                    $strategy = $data['margin_strategy'];
                    $roundTo = $data['round_to'];
                    $count = 0;

                    foreach ($plans as $storePlan) {
                        $cost = $storePlan->getWholesaleCost();
                        $sizeGb = ($storePlan->dataPlan?->size_mb > 0) ? ((float) $storePlan->dataPlan->size_mb / 1024) : 1.0;
                        $newPrice = 0.0;

                        if ($strategy === 'fixed_profit') {
                            $newPrice = $cost + (float) $data['profit_amount'];
                        } elseif ($strategy === 'per_gb_rate') {
                            $newPrice = $sizeGb * (float) $data['gb_rate'];
                        } elseif ($strategy === 'percentage') {
                            $newPrice = $cost * (1 + ((float) $data['percentage_rate'] / 100));
                        }

                        if ($roundTo === '5') {
                            $newPrice = round($newPrice / 5) * 5;
                        } elseif ($roundTo === '10') {
                            $newPrice = round($newPrice / 10) * 10;
                        }

                        $storePlan->update([
                            'selling_price' => round($newPrice, 2),
                        ]);
                        $count++;
                    }

                    Notification::make()
                        ->title('Store Prices Updated!')
                        ->body("Successfully updated customer prices for {$count} data plans.")
                        ->success()
                        ->send();
                }),
        ];
    }

    protected function getTableQuery(): ?Builder
    {
        $store = Filament::getTenant();

        if ($store) {
            $masterPlans = DataPlan::where('is_active', true)->get();
            $existingPlanIds = StoreDataPlan::where('store_id', $store->id)
                ->pluck('data_plan_id')
                ->toArray();

            foreach ($masterPlans as $masterPlan) {
                if (! in_array($masterPlan->id, $existingPlanIds, true)) {
                    StoreDataPlan::create([
                        'store_id' => $store->id,
                        'data_plan_id' => $masterPlan->id,
                        'selling_price' => $masterPlan->default_retail_price,
                        'is_enabled' => true,
                        'is_best_offer' => (bool) $masterPlan->is_best_offer,
                    ]);
                }
            }
        }

        return parent::getTableQuery();
    }

    public function getTabs(): array
    {
        $store = Filament::getTenant();
        $storeId = $store ? $store->id : null;

        $tabs = [
            'all' => Tab::make('All Networks')
                ->badge(StoreDataPlan::where('store_id', $storeId)->count()),
        ];

        $networks = Network::where('is_active', true)->get();

        foreach ($networks as $network) {
            $netId = $network->id;
            $tabs['net_'.$netId] = Tab::make($network->name)
                ->modifyQueryUsing(fn (Builder $query) => $query->whereHas('dataPlan', fn ($q) => $q->where('network_id', $netId)))
                ->badge(
                    StoreDataPlan::where('store_id', $storeId)
                        ->whereHas('dataPlan', fn ($q) => $q->where('network_id', $netId))
                        ->count()
                );
        }

        return $tabs;
    }
}
