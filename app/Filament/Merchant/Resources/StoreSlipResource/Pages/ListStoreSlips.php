<?php

namespace App\Filament\Merchant\Resources\StoreSlipResource\Pages;

use App\Filament\Merchant\Resources\StoreSlipResource;
use App\Models\Service;
use App\Models\Slip;
use App\Models\StoreSlip;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListStoreSlips extends ListRecords
{
    protected static string $resource = StoreSlipResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('bulk_set_margins')
                ->label('⚡ Set My Profit Margins (Bulk)')
                ->icon('heroicon-o-bolt')
                ->color('success')
                ->modalHeading('⚡ Set My Identity Slips Profit Margins')
                ->modalDescription('Quickly calculate and update your store selling prices across identity slips with a profit rule.')
                ->modalSubmitActionLabel('Apply Store Prices')
                ->form([
                    Select::make('service_key')
                        ->label('Service Filter')
                        ->options([
                            'all' => '🌐 All Identity Services',
                            'nin_verification' => '🆔 NIN Verification',
                            'bvn_verification' => '🏦 BVN Verification',
                        ])
                        ->default('all')
                        ->required(),

                    Radio::make('margin_strategy')
                        ->label('Profit Strategy')
                        ->options([
                            'fixed_profit' => 'Fixed Profit (+₦ above wholesale cost)',
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
                        ->helperText('This amount will be added on top of your wholesale cost (what you pay).')
                        ->required(fn ($get) => $get('margin_strategy') === 'fixed_profit'),

                    TextInput::make('profit_percent')
                        ->label('Profit Percentage (+%)')
                        ->numeric()
                        ->suffix('%')
                        ->default(25)
                        ->visible(fn ($get) => $get('margin_strategy') === 'percentage')
                        ->helperText('e.g. 25% profit on top of your wholesale cost.')
                        ->required(fn ($get) => $get('margin_strategy') === 'percentage'),
                ])
                ->action(function (array $data): void {
                    $store = Filament::getTenant();
                    if (! $store) {
                        return;
                    }

                    $query = StoreSlip::where('store_id', $store->id)->with('slip.service');

                    if ($data['service_key'] !== 'all') {
                        $query->whereHas('slip.service', fn ($q) => $q->where('key', $data['service_key']));
                    }

                    $storeSlips = $query->get();
                    $count = 0;

                    foreach ($storeSlips as $storeSlip) {
                        $cost = $storeSlip->getWholesaleCost();
                        if ($cost <= 0) {
                            continue;
                        }

                        if ($data['margin_strategy'] === 'fixed_profit') {
                            $newPrice = $cost + (float) $data['profit_amount'];
                        } else {
                            $percent = (float) $data['profit_percent'];
                            $newPrice = $cost + ($cost * ($percent / 100));
                        }

                        // Round to nearest 5 for clean customer pricing
                        $newPrice = round($newPrice / 5) * 5;

                        $storeSlip->update([
                            'selling_price' => round($newPrice, 2),
                        ]);
                        $count++;
                    }

                    Notification::make()
                        ->title('Slip Prices Updated!')
                        ->body("Successfully updated customer prices for {$count} identity slips.")
                        ->success()
                        ->send();
                }),
        ];
    }

    protected function getTableQuery(): ?Builder
    {
        $store = Filament::getTenant();

        if ($store) {
            $masterSlips = Slip::where('is_active', true)->get();
            $existingSlipIds = StoreSlip::where('store_id', $store->id)
                ->pluck('slip_id')
                ->toArray();

            foreach ($masterSlips as $masterSlip) {
                if (! in_array($masterSlip->id, $existingSlipIds, true)) {
                    StoreSlip::create([
                        'store_id' => $store->id,
                        'slip_id' => $masterSlip->id,
                        'selling_price' => $masterSlip->selling_price,
                        'is_enabled' => true,
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
            'all' => Tab::make('All Slips')
                ->badge(StoreSlip::where('store_id', $storeId)->count()),
        ];

        $ninService = Service::where('key', 'nin_verification')->first();
        if ($ninService) {
            $tabs['nin'] = Tab::make('NIN Slips')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereHas('slip', fn ($q) => $q->where('service_id', $ninService->id)))
                ->badge(
                    StoreSlip::where('store_id', $storeId)
                        ->whereHas('slip', fn ($q) => $q->where('service_id', $ninService->id))
                        ->count()
                )
                ->badgeColor('success');
        }

        $bvnService = Service::where('key', 'bvn_verification')->first();
        if ($bvnService) {
            $tabs['bvn'] = Tab::make('BVN Slips')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereHas('slip', fn ($q) => $q->where('service_id', $bvnService->id)))
                ->badge(
                    StoreSlip::where('store_id', $storeId)
                        ->whereHas('slip', fn ($q) => $q->where('service_id', $bvnService->id))
                        ->count()
                )
                ->badgeColor('info');
        }

        return $tabs;
    }
}
