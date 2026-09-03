<?php

namespace App\Filament\Widgets;

use App\Models\Subscription;
use Filament\Widgets\ChartWidget;

class SubscriptionTrendsChart extends ChartWidget
{
    protected ?string $heading = 'Subscription Acquisition Trends';

    protected static ?int $sort = 2;

    protected static string $type = 'line';

    protected function getData(): array
    {
        $data = [];
        $labels = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $labels[] = $month->format('M Y');
            
            // Count subscriptions created in that month
            $count = Subscription::whereBetween('created_at', [
                $month->startOfMonth()->toDateTimeString(),
                $month->endOfMonth()->toDateTimeString()
            ])->count();

            $data[] = $count;
        }

        return [
            'datasets' => [
                [
                    'label' => 'New Subscriptions',
                    'data' => $data,
                    'backgroundColor' => 'rgba(245, 158, 11, 0.1)',
                    'borderColor' => '#f59e0b',
                    'borderWidth' => 3,
                    'fill' => true,
                    'tension' => 0.4,
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
