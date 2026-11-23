<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class PlatformProfitChartWidget extends ChartWidget
{
    protected static ?int $sort = 2;

    protected ?string $heading = 'Daily Platform Profit & Sales (Last 14 Days)';

    protected ?string $maxHeight = '280px';

    protected int|string|array $columnSpan = 'full';

    protected function getData(): array
    {
        $labels = [];
        $salesData = [];
        $profitData = [];

        for ($i = 13; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $labels[] = $date->format('M d');

            $daySales = Transaction::whereIn('status', ['successful', 'success', 'completed'])
                ->whereDate('created_at', $date)
                ->sum('amount');

            $dayProfit = Transaction::whereIn('status', ['successful', 'success', 'completed'])
                ->whereDate('created_at', $date)
                ->sum('platform_profit');

            $salesData[] = round((float) $daySales, 2);
            $profitData[] = round((float) $dayProfit, 2);
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Vending Volume (₦)',
                    'data' => $salesData,
                    'fill' => 'start',
                    'borderColor' => '#0ea5e9',
                    'backgroundColor' => 'rgba(14, 165, 233, 0.08)',
                    'tension' => 0.3,
                ],
                [
                    'label' => 'Platform Net Profit (₦)',
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

