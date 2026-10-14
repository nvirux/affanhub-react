<?php

namespace App\Filament\Merchant\Resources;

use App\Filament\Merchant\Pages\Billing;
use App\Filament\Merchant\Resources\StoreServiceResource\Pages\ListStoreServices;
use App\Models\StoreService;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class StoreServiceResource extends Resource
{
    protected static ?string $model = StoreService::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSquares2x2;

    protected static string|UnitEnum|null $navigationGroup = null;

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
            ->modifyQueryUsing(fn (Builder $query) => $query->with(['service.feature', 'store.activeSubscription.plan']))
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
                        'airtime_cash' => 'heroicon-o-arrow-path-rounded-square',
                        'bulk_sms' => 'heroicon-o-chat-bubble-bottom-center-text',
                        'nin_verification' => 'heroicon-o-identification',
                        'bvn_verification' => 'heroicon-o-user',
                        'nin_retrieval' => 'heroicon-o-magnifying-glass',
                        'nin_modification' => 'heroicon-o-pencil-square',
                        'ipe_clearance' => 'heroicon-o-document-check',
                        default => 'heroicon-o-sparkles',
                    })
                    ->iconColor(fn (StoreService $record) => match ($record->service?->category) {
                        'identity' => 'purple',
                        default => 'warning',
                    })
                    ->description(fn (StoreService $record) => $record->service?->description),

                TextColumn::make('service.category')
                    ->label('Category')
                    ->badge()
                    ->color(fn (string $state) => $state === 'identity' ? 'purple' : 'warning')
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'identity' => '🪪 Identity Services',
                        'vtu' => '⚡ VTU Utilities',
                        default => strtoupper($state),
                    }),

                TextColumn::make('plan_access')
                    ->label('Plan Entitlement')
                    ->badge()
                    ->state(fn (StoreService $record) => $record->getAccessBadgeDetails()['label'])
                    ->color(fn (StoreService $record) => $record->getAccessBadgeDetails()['color'])
                    ->description(fn (StoreService $record) => $record->getAccessBadgeDetails()['description']),

                ToggleColumn::make('is_enabled')
                    ->label('Storefront Active')
                    ->disabled(fn (StoreService $record) => ! $record->hasAccess())
                    ->afterStateUpdated(function (StoreService $record, bool $state) {
                        Notification::make()
                            ->title('Storefront Display Updated')
                            ->body(($record->service?->name ?? 'Service').($state ? ' is now visible to customers on your storefront.' : ' is now hidden from customers.'))
                            ->success()
                            ->send();
                    }),

                TextColumn::make('display_status')
                    ->label('Live Status')
                    ->badge()
                    ->state(function (StoreService $record): string {
                        if (! $record->hasAccess()) {
                            return '🔒 Locked';
                        }

                        return $record->is_enabled ? '🟢 Active' : '⚪ Hidden';
                    })
                    ->color(function (string $state): string {
                        return match ($state) {
                            '🟢 Active' => 'success',
                            '🔒 Locked' => 'danger',
                            default => 'gray',
                        };
                    }),
            ])
            ->filters([
                SelectFilter::make('is_enabled')
                    ->label('Storefront Visibility')
                    ->options([
                        '1' => '🟢 Visible on Storefront',
                        '0' => '⚪ Hidden from Storefront',
                    ]),
            ])
            ->recordActions([
                Action::make('configure_airtime_cash')
                    ->label('Rates & SIM')
                    ->icon('heroicon-o-arrow-path-rounded-square')
                    ->color('warning')
                    ->visible(fn (StoreService $record) => $record->service?->key === 'airtime_cash' && $record->hasAccess())
                    ->url(fn () => StoreAirtimeCashSettingResource::getUrl('index')),

                Action::make('configure_data')
                    ->label('Data Pricing')
                    ->icon('heroicon-o-signal')
                    ->color('info')
                    ->visible(fn (StoreService $record) => $record->service?->key === 'data' && $record->hasAccess())
                    ->url(fn () => StoreDataPlanResource::getUrl('index')),

                Action::make('configure_airtime')
                    ->label('Airtime Discounts')
                    ->icon('heroicon-o-device-phone-mobile')
                    ->color('success')
                    ->visible(fn (StoreService $record) => $record->service?->key === 'airtime' && $record->hasAccess())
                    ->url(fn () => StoreAirtimeDiscountResource::getUrl('index')),

                Action::make('configure_slips')
                    ->label('Slip Pricing')
                    ->icon('heroicon-o-identification')
                    ->color('purple')
                    ->visible(fn (StoreService $record) => in_array($record->service?->key, ['nin_verification', 'bvn_verification']) && $record->hasAccess())
                    ->url(fn () => StoreSlipResource::getUrl('index')),

                Action::make('upgrade_plan')
                    ->label('Upgrade Plan')
                    ->icon('heroicon-o-lock-closed')
                    ->color('danger')
                    ->visible(fn (StoreService $record) => ! $record->hasAccess())
                    ->url(fn () => Billing::getUrl()),
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
