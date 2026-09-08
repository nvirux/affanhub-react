<?php

namespace App\Filament\Resources\PlanAirtimeDiscounts\Pages;

use App\Filament\Resources\PlanAirtimeDiscounts\PlanAirtimeDiscountResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPlanAirtimeDiscounts extends ListRecords
{
    protected static string $resource = PlanAirtimeDiscountResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
