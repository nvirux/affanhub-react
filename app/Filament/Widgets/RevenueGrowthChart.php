<?php

namespace App\Filament\Widgets;

use App\Models\Subscription;
use Filament\Widgets\ChartWidget;

class RevenueGrowthChart extends ChartWidget
{
    protected ?string $heading = 'Monthly Revenue Acquisition';

    protected static ?int $sort = 3;

    protected static string $type = 'bar';

    protected function getData(): array
    {
        $data = [];
        $labels = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $labels[] = $month->format('M Y');

            // Sum prices of subscriptions created in that month
            $revenue = Subscription::whereBetween('created_at', [
                $month->startOfMonth()->toDateTimeString(),
                $month->endOfMonth()->toDateTimeString(),
            ])->sum('price');

            $data[] = floatval($revenue);
        }

        return [
            'datasets' => [
                [
                    'label' => 'New Revenue (₦)',
                    'data' => $data,
                    'backgroundColor' => 'rgba(16, 185, 129, 0.85)',
                    'borderColor' => '#10b981',
                    'borderWidth' => 1,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
