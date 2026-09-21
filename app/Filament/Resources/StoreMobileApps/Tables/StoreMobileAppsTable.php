<?php

namespace App\Filament\Resources\StoreMobileApps\Tables;

use App\Models\StoreMobileApp;
use App\Services\MobileAppBuilderService;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class StoreMobileAppsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('app_icon_path')
                    ->label('Icon')
                    ->circular()
                    ->disk('public'),

                TextColumn::make('store.name')
                    ->label('Store')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('app_name')
                    ->label('App Name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('package_id')
                    ->label('Package ID')
                    ->searchable()
                    ->copyable()
                    ->color('gray'),

                TextColumn::make('version')
                    ->label('Version')
                    ->badge()
                    ->color('info')
                    ->state(fn (StoreMobileApp $record): string => "v{$record->version_name} ({$record->version_code})"),

                TextColumn::make('status')
                    ->label('Build Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'ready' => 'success',
                        'building' => 'warning',
                        'failed' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('playstore_status')
                    ->label('Google Play')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'published' => 'success',
                        'submitted' => 'info',
                        'pending_submission' => 'warning',
                        'rejected' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => ucwords(str_replace('_', ' ', $state))),

                TextColumn::make('price_paid')
                    ->label('Paid')
                    ->money('NGN')
                    ->sortable(),

                TextColumn::make('last_built_at')
                    ->label('Last Built')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'ready' => 'Ready',
                        'building' => 'Building',
                        'failed' => 'Failed',
                    ]),

                SelectFilter::make('playstore_status')
                    ->label('Play Store Status')
                    ->options([
                        'pending_submission' => 'Pending Submission',
                        'submitted' => 'Submitted to Google',
                        'published' => 'Published Live',
                        'not_requested' => 'Not Requested',
                    ]),
            ])
            ->recordActions([
                Action::make('download')
                    ->label('Download APK')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->visible(fn (StoreMobileApp $record): bool => $record->isReady())
                    ->url(fn (StoreMobileApp $record): ?string => $record->apk_download_url)
                    ->openUrlInNewTab(),

                Action::make('downloadAab')
                    ->label('Download AAB Bundle')
                    ->icon('heroicon-o-cloud-arrow-down')
                    ->color('primary')
                    ->visible(fn (StoreMobileApp $record): bool => ! empty($record->aab_download_url))
                    ->url(fn (StoreMobileApp $record): ?string => $record->aab_download_url)
                    ->openUrlInNewTab(),

                Action::make('rebuild')
                    ->label('Trigger Build')
                    ->icon('heroicon-o-bolt')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalHeading('Trigger Android APK Build')
                    ->modalDescription('This will increment the build version and trigger a fresh GitHub Actions cloud build for this app.')
                    ->action(function (StoreMobileApp $record, MobileAppBuilderService $builder): void {
                        $record->incrementVersion();
                        $record->status = 'building';
                        $record->save();

                        $res = $builder->dispatchBuild($record);

                        if ($res['success']) {
                            Notification::make()
                                ->title('Build Dispatched!')
                                ->body("Compilation started for {$record->app_name} (v{$record->version_name}).")
                                ->success()
                                ->send();
                        } else {
                            Notification::make()
                                ->title('Build Dispatch Notice')
                                ->body($res['message'])
                                ->warning()
                                ->send();
                        }
                    }),

                Action::make('sync_status')
                    ->label('Check Status')
                    ->icon('heroicon-o-arrow-path')
                    ->color('gray')
                    ->action(function (StoreMobileApp $record, MobileAppBuilderService $builder): void {
                        $builder->syncBuildStatus($record);
                        $record->refresh();

                        if ($record->isReady()) {
                            Notification::make()
                                ->title('APK Ready!')
                                ->body("The APK for {$record->app_name} is ready for download.")
                                ->success()
                                ->send();
                        } elseif ($record->status === 'failed') {
                            Notification::make()
                                ->title('Build Failed')
                                ->body($record->failure_reason ?? 'Build failed on runner.')
                                ->danger()
                                ->send();
                        } else {
                            Notification::make()
                                ->title('Compilation In Progress')
                                ->body('GitHub Actions runner is still compiling the APK.')
                                ->info()
                                ->send();
                        }
                    }),

                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
