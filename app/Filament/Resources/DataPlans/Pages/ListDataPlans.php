<?php

namespace App\Filament\Resources\DataPlans\Pages;

use App\Filament\Resources\DataPlans\DataPlanResource;
use App\Models\DataPlan;
use App\Models\DataType;
use App\Models\Network;
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
                        if ($roundTo === '5') {
                            $newWholesale = round($newWholesale / 5) * 5;
                            $newRetail = round($newRetail / 5) * 5;
                        } elseif ($roundTo === '10') {
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
