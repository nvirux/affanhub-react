<?php

namespace App\Filament\Resources\Stores\RelationManagers;

use App\Models\Domain;
use App\Services\Domain\CustomDomainOnboardingService;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DomainsRelationManager extends RelationManager
{
    protected static string $relationship = 'domains';

    protected static ?string $title = 'Domains & Routing';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('domain')
                ->required()
                ->unique(table: 'domains', column: 'domain', ignoreRecord: true)
                ->placeholder('e.g. yourbrand.com or demo.localhost'),
            Toggle::make('is_primary')
                ->label('Set as Primary Domain')
                ->default(false),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('domain')
                    ->label('Domain Hostname')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->weight('bold')
                    ->icon('heroicon-m-globe-alt'),

                TextColumn::make('is_primary')
                    ->label('Primary')
                    ->badge()
                    ->state(fn (Domain $record): string => $record->is_primary ? 'Primary' : 'Alias')
                    ->color(fn (string $state): string => $state === 'Primary' ? 'success' : 'gray'),

                TextColumn::make('type')
                    ->label('Type')
                    ->badge()
                    ->state(fn (Domain $record): string => $record->isCustom() ? 'Custom Domain' : 'Platform Subdomain')
                    ->color(fn (string $state): string => $state === 'Custom Domain' ? 'primary' : 'info'),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->state(fn (Domain $record): string => $record->isHealthy() ? 'Active / Healthy' : ($record->status === Domain::STATUS_FAILED ? 'Failed' : 'Pending'))
                    ->color(fn (Domain $record): string => $record->isHealthy() ? 'success' : ($record->status === Domain::STATUS_FAILED ? 'danger' : 'warning')),

                IconColumn::make('cloudflare_detected')
                    ->label('Cloudflare')
                    ->boolean()
                    ->trueColor('success')
                    ->falseColor('gray')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Connected')
                    ->dateTime('M j, Y')
                    ->sortable(),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                ActionGroup::make([
                    Action::make('set_primary')
                        ->label('Make Primary')
                        ->icon('heroicon-o-star')
                        ->color('success')
                        ->visible(fn (Domain $record): bool => ! $record->is_primary)
                        ->requiresConfirmation()
                        ->action(function (Domain $record) {
                            Domain::where('tenant_id', $record->tenant_id)->update(['is_primary' => false]);
                            $record->update(['is_primary' => true]);
                            Notification::make()->title("{$record->domain} is now the primary domain.")->success()->send();
                        }),

                    Action::make('verify_now')
                        ->label('Verify DNS & SSL')
                        ->icon('heroicon-o-shield-check')
                        ->color('info')
                        ->visible(fn (Domain $record): bool => $record->isCustom() && ! $record->isHealthy())
                        ->action(function (Domain $record, CustomDomainOnboardingService $service) {
                            $success = $service->verifyAndProvision($record);
                            if ($success) {
                                Notification::make()->title('Domain successfully verified and active!')->success()->send();
                            } else {
                                Notification::make()->title('Verification pending DNS propagation: '.($record->verification_error ?? 'Not resolved yet'))->warning()->send();
                            }
                        }),

                    EditAction::make(),
                    DeleteAction::make(),
                ])
                    ->label('Actions')
                    ->icon('heroicon-m-ellipsis-vertical')
                    ->color('gray'),
            ]);
    }
}
