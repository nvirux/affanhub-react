<?php

namespace App\Filament\Merchant\Resources;

use App\Filament\Merchant\Resources\StoreServiceResource\Pages\ListStoreServices;
use App\Models\StoreService;
use BackedEnum;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class StoreServiceResource extends Resource
{
    protected static ?string $model = StoreService::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSquares2x2;

    protected static string|UnitEnum|null $navigationGroup = 'Products & Pricing';

    protected static ?string $navigationLabel = 'Services & Layout';

    protected static ?string $modelLabel = 'Storefront Service';

    protected static ?string $pluralModelLabel = 'Services & Display Layout';

    protected static ?string $slug = 'manage-services';

    protected static ?string $tenantRelationshipName = 'storeServices';

    protected static ?int $navigationSort = 1;

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->reorderable('sort_order')
            ->defaultSort('sort_order', 'asc')
            ->modifyQueryUsing(fn (Builder $query) => $query->with(['service.feature']))
            ->columns([
                TextColumn::make('service.name')
                    ->label('Service')
                    ->searchable()
                    ->weight('bold')
                    ->icon(fn (StoreService $record) => match ($record->service?->key) {
                        'airtime' => 'heroicon-o-device-phone-mobile',
                        'data' => 'heroicon-o-signal',
                        'cable' => 'heroicon-o-tv',
                        'electricity' => 'heroicon-o-bolt',
                        'education' => 'heroicon-o-academic-cap',
                        'airtime_cash' => 'heroicon-o-arrow-path',
                        'bulk_sms' => 'heroicon-o-chat-bubble-bottom-center-text',
                        'nin_verification' => 'heroicon-o-identification',
                        'bvn_verification' => 'heroicon-o-user',
                        'nin_retrieval' => 'heroicon-o-magnifying-glass',
                        'nin_modification' => 'heroicon-o-pencil-square',
                        'ipe_clearance' => 'heroicon-o-document-check',
                        default => 'heroicon-o-sparkles',
                    })
                    ->description(fn (StoreService $record) => $record->service?->description),

                TextColumn::make('service.category')
                    ->label('Category')
                    ->badge()
                    ->color(fn (string $state) => $state === 'identity' ? 'purple' : 'warning')
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'identity' => 'Identity Services',
                        'vtu' => 'VTU Utilities',
                        default => strtoupper($state),
                    }),

                TextColumn::make('access_status')
                    ->label('Plan Access')
                    ->badge()
                    ->state(fn (StoreService $record) => $record->hasAccess() ? 'Available' : '🔒 '.$record->getRequiredPlan())
                    ->color(fn (StoreService $record) => $record->hasAccess() ? 'success' : 'danger'),

                ToggleColumn::make('is_enabled')
                    ->label('Storefront Display')
                    ->disabled(fn (StoreService $record) => ! $record->hasAccess())
                    ->afterStateUpdated(function (StoreService $record, bool $state) {
                        Notification::make()
                            ->title('Storefront Updated')
                            ->body(($record->service?->name ?? 'Service').($state ? ' is now active on your storefront.' : ' is now hidden from your storefront.'))
                            ->success()
                            ->send();
                    }),
            ])
            ->emptyStateHeading('No services found')
            ->emptyStateDescription('Your store services will automatically appear here.');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListStoreServices::route('/'),
        ];
    }
}
