<?php

namespace App\Filament\Resources\Subscriptions\Pages;

use App\Filament\Resources\Subscriptions\SubscriptionResource;
use App\Models\Plan;
use App\Models\Subscription;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class ListSubscriptions extends ListRecords
{
    protected static string $resource = SubscriptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        $tabs = [
            'all' => Tab::make('All Plans')
                ->badge(Subscription::count()),
        ];

        $plans = Plan::orderBy('id')->get();

        foreach ($plans as $plan) {
            $planId = $plan->id;
            $slug = strtolower($plan->slug ?? Str::slug($plan->name));
            $badgeColor = match ($slug) {
                'starter' => 'gray',
                'pro' => 'primary',
                'enterprise' => 'success',
                default => 'info',
            };

            $tabs[$slug] = Tab::make($plan->name)
                ->modifyQueryUsing(fn (Builder $query) => $query->where('plan_id', $planId))
                ->badge(Subscription::where('plan_id', $planId)->count())
                ->badgeColor($badgeColor);
        }

        return $tabs;
    }
}
