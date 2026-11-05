<?php

namespace App\Filament\Resources\Domains\Pages;

use App\Filament\Resources\Domains\DomainResource;
use App\Models\Domain;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListDomains extends ListRecords
{
    protected static string $resource = DomainResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Connect Domain'),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All Domains')
                ->badge(Domain::count()),

            'subdomains' => Tab::make('Subdomains (Platform)')
                ->modifyQueryUsing(fn (Builder $query) => $query->subdomains())
                ->badge(Domain::subdomains()->count())
                ->badgeColor('info'),

            'custom' => Tab::make('Custom Domains')
                ->modifyQueryUsing(fn (Builder $query) => $query->custom())
                ->badge(Domain::custom()->count())
                ->badgeColor('purple'),

            'issues' => Tab::make('Pending / Issues')
                ->modifyQueryUsing(fn (Builder $query) => $query->custom()->where(function ($q) {
                    $q->where('is_verified', false)
                        ->orWhere('status', Domain::STATUS_FAILED)
                        ->orWhere('verification_status', Domain::VERIFY_PENDING_DNS)
                        ->orWhere('is_routing_enabled', false);
                }))
                ->badge(Domain::custom()->where(function ($q) {
                    $q->where('is_verified', false)
                        ->orWhere('status', Domain::STATUS_FAILED)
                        ->orWhere('verification_status', Domain::VERIFY_PENDING_DNS)
                        ->orWhere('is_routing_enabled', false);
                })->count())
                ->badgeColor('warning'),

            'active' => Tab::make('Active Routing')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('is_routing_enabled', true))
                ->badge(Domain::where('is_routing_enabled', true)->count())
                ->badgeColor('success'),
        ];
    }
}
