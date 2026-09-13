<?php

namespace App\Filament\Merchant\Widgets;

use App\Models\Transaction;
use Filament\Facades\Filament;
use Filament\Widgets\ChartWidget;

class SalesPerformanceChartWidget extends ChartWidget
{
    protected ?string $heading = 'Store Vending Sales (Last 14 Days)';

    protected static ?int $sort = 2;

    protected ?string $maxHeight = '280px';

    protected int|string|array $columnSpan = 'full';

    protected function getData(): array
    {
        $store = Filament::getTenant();

        if (! $store) {
            return [];
        }

        $labels = [];
        $salesData = [];
        $profitData = [];

        for ($i = 13; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $labels[] = $date->format('M d');

            $daySales = Transaction::where('store_id', $store->id)
                ->where('status', 'success')
                ->whereDate('created_at', $date->toDateString())
                ->sum('amount_paid');

            $dayProfit = Transaction::where('store_id', $store->id)
                ->where('status', 'success')
                ->whereDate('created_at', $date->toDateString())
                ->sum('profit');

            $salesData[] = round((float) $daySales, 2);
            $profitData[] = round((float) $dayProfit, 2);
        }

        return [
            'datasets' => [
                [
                    'label' => 'Sales Volume (₦)',
                    'data' => $salesData,
                    'fill' => 'start',
                    'borderColor' => '#0ea5e9',
                    'backgroundColor' => 'rgba(14, 165, 233, 0.12)',
                    'tension' => 0.3,
                ],
                [
                    'label' => 'Net Profit (₦)',
                    'data' => $profitData,
                    'fill' => false,
                    'borderColor' => '#10b981',
                    'backgroundColor' => '#10b981',
                    'tension' => 0.3,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
