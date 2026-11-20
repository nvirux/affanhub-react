<?php

namespace App\Filament\Resources\Features\Pages;

use App\Filament\Resources\Features\FeatureResource;
use App\Models\Feature;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListFeatures extends ListRecords
{
    protected static string $resource = FeatureResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),

            Action::make('sync_standard_features')
                ->label('Sync Standard Features')
                ->icon('heroicon-o-arrow-path')
                ->color('gray')
                ->requiresConfirmation()
                ->modalHeading('Sync Standard Platform Features')
                ->modalDescription('This will verify all standard platform capabilities (Staff Limit, Custom Domain, API Access, etc.) are present and properly sorted.')
                ->action(function () {
                    $presets = Feature::getStandardFeaturePresets();
                    $order = 1;
                    foreach ($presets as $slug => $data) {
                        Feature::updateOrCreate(
                            ['slug' => $slug],
                            array_merge($data, ['sort_order' => $order++])
                        );
                    }

                    Notification::make()
                        ->title('Standard platform features synchronized successfully.')
                        ->success()
                        ->send();
                }),
        ];
    }
}
