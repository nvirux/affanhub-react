<?php

namespace App\Filament\Resources\Subscriptions\Pages;

use App\Filament\Resources\Subscriptions\SubscriptionResource;
use App\Models\Subscription;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

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
        return [
            'all' => Tab::make('All Subscriptions')
                ->badge(Subscription::count()),

            'active' => Tab::make('Active')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'active'))
                ->badge(Subscription::where('status', 'active')->count())
                ->badgeColor('success'),

            'trialing' => Tab::make('Trialing')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'trialing'))
                ->badge(Subscription::where('status', 'trialing')->count())
                ->badgeColor('info'),

            'expired' => Tab::make('Expired')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'expired'))
                ->badge(Subscription::where('status', 'expired')->count())
                ->badgeColor('danger'),

            'cancelled' => Tab::make('Cancelled')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'cancelled'))
                ->badge(Subscription::where('status', 'cancelled')->count())
                ->badgeColor('warning'),
        ];
    }
}
