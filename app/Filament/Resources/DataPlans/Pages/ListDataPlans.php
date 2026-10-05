<?php

namespace App\Filament\Resources\DataPlans\Pages;

use App\Filament\Resources\DataPlans\DataPlanResource;
use App\Models\DataPlan;
use App\Models\Network;
use App\Services\Vtu\DataPlanSyncService;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
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
                            'all' => '🔄 Sync All (Import new plans & update existing)',
                            'new_only' => '✨ New Plans Only (Import new plans only; preserve existing prices)',
                            'costs_only' => '💰 Provider Costs Only (Refresh wholesale costs without changing selling prices)',
                        ])
                        ->default('all')
                        ->required()
                        ->live(),

                    Toggle::make('apply_pricing')
                        ->label('⚡ Apply Automated Profit Margins to Synced Plans')
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
