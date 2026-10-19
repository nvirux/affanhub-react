<?php

namespace App\Filament\Resources\StoreMobileApps\Tables;

use App\Models\StoreMobileApp;
use App\Services\MobileAppBuilderService;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
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
            ->recordUrl(null)
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
                    ->fontFamily('mono')
                    ->copyable()
                    ->searchable(),

                TextColumn::make('version_name')
                    ->label('Version')
                    ->badge()
                    ->color('info')
                    ->formatStateUsing(fn ($state, StoreMobileApp $record) => "v{$state} ({$record->version_code})"),

                TextColumn::make('status')
                    ->label('Build Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'ready' => 'success',
                        'building' => 'warning',
                        'failed' => 'danger',
                        default => 'gray',
                    })
                    ->description(fn (StoreMobileApp $record): ?string => $record->status === 'failed' ? $record->failure_reason : null),

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
                ViewAction::make(),

                Action::make('download')
                    ->label('Download APK')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->visible(fn (StoreMobileApp $record): bool => $record->isReady())
                    ->url(fn (StoreMobileApp $record): ?string => $record->apk_download_url)
                    ->openUrlInNewTab(),

                Action::make('downloadAab')
                    ->label('Download AAB')
                    ->icon('heroicon-o-cloud-arrow-down')
                    ->color('primary')
                    ->visible(fn (StoreMobileApp $record): bool => ! empty($record->aab_download_url))
                    ->url(fn (StoreMobileApp $record): ?string => $record->aab_download_url)
                    ->openUrlInNewTab(),

                Action::make('cancel_build')
                    ->label('Cancel Build')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn (StoreMobileApp $record): bool => $record->status === 'building')
                    ->requiresConfirmation()
                    ->modalHeading('Cancel Cloud Build')
                    ->modalDescription('Are you sure you want to cancel this build? The merchant will be able to retry immediately.')
                    ->action(function (StoreMobileApp $record): void {
                        $record->update([
                            'status' => 'failed',
                            'failure_reason' => 'Build cancelled by administrator.',
                        ]);

                        Notification::make()
                            ->title('Build Cancelled')
                            ->body("The build for {$record->app_name} has been cancelled.")
                            ->info()
                            ->send();
                    }),

                Action::make('manage_playstore')
                    ->label('Play Store')
                    ->icon('heroicon-o-globe-alt')
                    ->color('info')
                    ->modalHeading(fn (StoreMobileApp $record): string => "Update Google Play: {$record->app_name}")
                    ->form([
                        Select::make('playstore_status')
                            ->label('Google Play Submission Status')
                            ->options([
                                'not_requested' => 'Not Requested',
                                'pending_submission' => 'Pending Submission',
                                'submitted' => 'Submitted to Google Console',
                                'published' => 'Published Live on Play Store',
                                'rejected' => 'Rejected by Google Policy',
                            ])
                            ->required(),

                        TextInput::make('playstore_url')
                            ->label('Google Play Store Live Listing URL')
                            ->url()
                            ->placeholder('https://play.google.com/store/apps/details?id=...'),
                    ])
                    ->fillForm(fn (StoreMobileApp $record): array => [
                        'playstore_status' => $record->playstore_status,
                        'playstore_url' => $record->playstore_url,
                    ])
                    ->action(function (StoreMobileApp $record, array $data): void {
                        $record->update([
                            'playstore_status' => $data['playstore_status'],
                            'playstore_url' => $data['playstore_url'],
                        ]);

                        Notification::make()
                            ->title('Play Store Status Updated')
                            ->body("Updated Google Play details for {$record->app_name}.")
                            ->success()
                            ->send();
                    }),

                Action::make('rebuild')
                    ->label('Rebuild')
                    ->icon('heroicon-o-bolt')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalHeading('Trigger Android APK Build')
                    ->modalDescription('This will increment the version code, clean old files, and dispatch a fresh cloud build.')
                    ->action(function (StoreMobileApp $record, MobileAppBuilderService $builder): void {
                        $builder->cleanupOldBuilds($record);
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
                    ->label('Sync')
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
                                ->body('Runner is still compiling.')
                                ->info()
                                ->send();
                        }
                    }),

                DeleteAction::make()
                    ->before(fn (StoreMobileApp $record, MobileAppBuilderService $builder) => $builder->cleanupOldBuilds($record)),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
