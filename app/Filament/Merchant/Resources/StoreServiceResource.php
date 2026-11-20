<?php

namespace App\Filament\Merchant\Resources;

use App\Filament\Merchant\Pages\Billing;
use App\Filament\Merchant\Resources\StoreServiceResource\Pages\ListStoreServices;
use App\Models\StoreService;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Components\Radio;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;
use UnitEnum;

class StoreServiceResource extends Resource
{
    protected static ?string $model = StoreService::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSquares2x2;

    protected static string|UnitEnum|null $navigationGroup = 'Services & Layout';

    protected static ?string $navigationLabel = 'Services';

    protected static ?string $modelLabel = 'Service';

    protected static ?string $pluralModelLabel = 'Services';

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
                    ->description(fn (StoreService $record) => $record->service?->description),

                TextColumn::make('service.category')
                    ->label('Category')
                    ->badge()
                    ->color(fn (StoreService $record) => $record->service?->key === 'cac_services' ? 'success' : ($record->service?->category === 'identity' ? 'purple' : 'warning'))
                    ->formatStateUsing(fn (StoreService $record) => match ($record->service?->key) {
                        'cac_services' => 'CAC & Business',
                        default => match ($record->service?->category) {
                            'identity' => 'Identity',
                            'vtu' => 'VTU',
                            default => strtoupper($record->service?->category ?? 'GENERAL'),
                        },
                    }),

                TextColumn::make('icon')
                    ->label('Storefront Icon')
                    ->html()
                    ->state(function (StoreService $record): string {
                        $resolved = $record->getResolvedIcon();
                        $hasCustom = ! empty($record->getAvailableIconOptions()) && $record->hasAccess();

                        return match ($resolved) {
                            'cac' => '<div style="display: inline-flex; align-items: center; gap: 10px; padding: 6px 12px; border-radius: 12px; background: rgba(16, 185, 129, 0.08); border: 1px solid rgba(16, 185, 129, 0.25); cursor: pointer;">
                                        <img src="'.asset('images/icons/cac.png').'" alt="CAC" style="width: 42px; height: 42px; object-fit: contain; background: #fff; border-radius: 8px; padding: 3px; box-shadow: 0 1px 3px rgba(0,0,0,0.08);" />
                                        <div>
                                            <div style="font-size: 12px; font-weight: 800; color: #047857; line-height: 1.2;">CAC Logo</div>
                                            '.($hasCustom ? '<div style="font-size: 10px; font-weight: 600; color: #059669; text-decoration: underline; margin-top: 2px;">Click to change</div>' : '').'
                                        </div>
                                      </div>',
                            'nimc' => '<div style="display: inline-flex; align-items: center; gap: 10px; padding: 6px 12px; border-radius: 12px; background: rgba(59, 130, 246, 0.08); border: 1px solid rgba(59, 130, 246, 0.25); cursor: pointer;">
                                        <img src="'.asset('images/icons/nimc.png').'" alt="NIMC" style="width: 42px; height: 42px; object-fit: contain; background: #fff; border-radius: 8px; padding: 3px; box-shadow: 0 1px 3px rgba(0,0,0,0.08);" />
                                        <div>
                                            <div style="font-size: 12px; font-weight: 800; color: #1d4ed8; line-height: 1.2;">NIMC Logo</div>
                                            '.($hasCustom ? '<div style="font-size: 10px; font-weight: 600; color: #2563eb; text-decoration: underline; margin-top: 2px;">Click to change</div>' : '').'
                                        </div>
                                      </div>',
                            'nibss' => '<div style="display: inline-flex; align-items: center; gap: 10px; padding: 6px 12px; border-radius: 12px; background: rgba(147, 51, 234, 0.08); border: 1px solid rgba(147, 51, 234, 0.25); cursor: pointer;">
                                        <img src="'.asset('images/icons/nibss.png').'" alt="NIBSS" style="width: 42px; height: 42px; object-fit: contain; background: #fff; border-radius: 8px; padding: 3px; box-shadow: 0 1px 3px rgba(0,0,0,0.08);" />
                                        <div>
                                            <div style="font-size: 12px; font-weight: 800; color: #7e22ce; line-height: 1.2;">NIBSS Logo</div>
                                            '.($hasCustom ? '<div style="font-size: 10px; font-weight: 600; color: #9333ea; text-decoration: underline; margin-top: 2px;">Click to change</div>' : '').'
                                        </div>
                                      </div>',
                            'verification' => '<div style="display: inline-flex; align-items: center; gap: 10px; padding: 6px 12px; border-radius: 12px; background: rgba(245, 158, 11, 0.08); border: 1px solid rgba(245, 158, 11, 0.25); cursor: pointer;">
                                        <img src="'.asset('images/icons/verification.png').'" alt="Verification" style="width: 42px; height: 42px; object-fit: contain; background: #fff; border-radius: 8px; padding: 3px; box-shadow: 0 1px 3px rgba(0,0,0,0.08);" />
                                        <div>
                                            <div style="font-size: 12px; font-weight: 800; color: #b45309; line-height: 1.2;">Verify Badge</div>
                                            '.($hasCustom ? '<div style="font-size: 10px; font-weight: 600; color: #d97706; text-decoration: underline; margin-top: 2px;">Click to change</div>' : '').'
                                        </div>
                                      </div>',
                            default => '<div style="display: inline-flex; align-items: center; gap: 10px; padding: 6px 12px; border-radius: 12px; background: rgba(243, 244, 246, 0.8); border: 1px solid rgba(209, 213, 219, 0.6);">
                                        <span style="width: 38px; height: 38px; border-radius: 8px; background: #e5e7eb; display: inline-flex; align-items: center; justify-content: center; font-size: 13px; font-weight: 800; color: #4b5563;">V</span>
                                        <div>
                                            <div style="font-size: 12px; font-weight: 700; color: #4b5563; line-height: 1.2;">Standard Icon</div>
                                            <div style="font-size: 10px; color: #9ca3af; margin-top: 2px;">System default</div>
                                        </div>
                                      </div>',
                        };
                    })
                    ->action(function (StoreService $record, ListStoreServices $livewire): void {
                        if (! empty($record->getAvailableIconOptions()) && $record->hasAccess()) {
                            $livewire->mountTableAction('choose_icon', $record);
                        }
                    }),

                ToggleColumn::make('is_enabled')
                    ->label('Show on Store')
                    ->disabled(fn (StoreService $record) => ! $record->hasAccess())
                    ->afterStateUpdated(function (StoreService $record, bool $state) {
                        Notification::make()
                            ->title('Storefront Updated')
                            ->body(($record->service?->name ?? 'Service').($state ? ' is now live on your storefront.' : ' is now hidden from customers.'))
                            ->success()
                            ->send();
                    }),

                TextColumn::make('display_status')
                    ->label('Status')
                    ->badge()
                    ->state(function (StoreService $record): string {
                        if (! $record->hasAccess()) {
                            return 'Locked ('.$record->getRequiredPlan().')';
                        }

                        return $record->is_enabled ? 'Live on Store' : 'Hidden';
                    })
                    ->color(function (string $state): string {
                        if (str_starts_with($state, 'Locked')) {
                            return 'danger';
                        }

                        return match ($state) {
                            'Live on Store' => 'success',
                            default => 'gray',
                        };
                    })
                    ->description(function (StoreService $record): ?string {
                        if (! $record->hasAccess()) {
                            $req = $record->getRequiredPlan();

                            return str_contains(strtolower($req), 'enterprise')
                                ? 'Contact support to activate'
                                : 'Upgrade plan to unlock';
                        }

                        return null;
                    }),
            ])
            ->filters([
                SelectFilter::make('is_enabled')
                    ->label('Storefront Visibility')
                    ->options([
                        '1' => 'Visible on Storefront',
                        '0' => 'Hidden from Storefront',
                    ]),
            ])
            ->recordActions([
                Action::make('upgrade_service')
                    ->label(function (StoreService $record): string {
                        $req = $record->getRequiredPlan();

                        return str_contains(strtolower($req), 'enterprise') ? 'Enterprise Plan' : "Unlock on {$req}";
                    })
                    ->icon('heroicon-o-lock-closed')
                    ->color(function (StoreService $record): string {
                        $req = $record->getRequiredPlan();

                        return str_contains(strtolower($req), 'enterprise') ? 'purple' : 'warning';
                    })
                    ->button()
                    ->visible(fn (StoreService $record) => ! $record->hasAccess())
                    ->modalHeading(fn (StoreService $record) => ($record->service?->name ?? 'Service').' - Subscription Upgrade')
                    ->modalDescription(function (StoreService $record): string {
                        $req = $record->getRequiredPlan();
                        $name = $record->service?->name ?? 'This service';
                        if (str_contains(strtolower($req), 'enterprise')) {
                            return "{$name} is reserved for Enterprise stores. High-volume and custom network integrations are available via dedicated enterprise onboarding. Please contact our support team to get this unlocked for your store.";
                        }

                        return "{$name} is not included in your current subscription tier. Upgrade your store subscription to {$req} to instantly unlock this service on your customer storefront.";
                    })
                    ->modalIcon('heroicon-o-sparkles')
                    ->modalIconColor('warning')
                    ->modalSubmitActionLabel(function (StoreService $record): string {
                        $req = $record->getRequiredPlan();

                        return str_contains(strtolower($req), 'enterprise') ? 'Contact Enterprise Support' : 'View Billing & Plans';
                    })
                    ->action(function (StoreService $record) {
                        $req = $record->getRequiredPlan();
                        if (str_contains(strtolower($req), 'enterprise')) {
                            $tenant = Filament::getTenant();
                            $storeName = $tenant?->name ?? 'My Store';
                            $wa = 'https://wa.me/2348000000000?text='.urlencode("Hello, I would like to inquire about Enterprise access for store: {$storeName}");

                            return redirect()->away($wa);
                        }

                        return redirect()->to(Billing::getUrl());
                    }),
                Action::make('configure_data')
                    ->label('Data Pricing')
                    ->icon('heroicon-o-signal')
                    ->color('info')
                    ->button()
                    ->outlined()
                    ->visible(fn (StoreService $record) => $record->service?->key === 'data' && $record->hasAccess())
                    ->url(fn () => StoreDataPlanResource::getUrl('index')),

                Action::make('configure_airtime')
                    ->label('Discounts')
                    ->icon('heroicon-o-device-phone-mobile')
                    ->color('success')
                    ->button()
                    ->outlined()
                    ->visible(fn (StoreService $record) => $record->service?->key === 'airtime' && $record->hasAccess())
                    ->url(fn () => StoreAirtimeDiscountResource::getUrl('index')),

                Action::make('configure_airtime_cash')
                    ->label('Rates & SIM')
                    ->icon('heroicon-o-arrow-path-rounded-square')
                    ->color('warning')
                    ->button()
                    ->outlined()
                    ->visible(fn (StoreService $record) => $record->service?->key === 'airtime_cash' && $record->hasAccess())
                    ->url(fn () => StoreAirtimeCashSettingResource::getUrl('index')),

                Action::make('configure_slips')
                    ->label('Slip Pricing')
                    ->icon('heroicon-o-identification')
                    ->color('purple')
                    ->button()
                    ->outlined()
                    ->visible(fn (StoreService $record) => in_array($record->service?->key, ['nin_verification', 'bvn_verification']) && $record->hasAccess())
                    ->url(fn () => StoreSlipResource::getUrl('index')),

                Action::make('configure_modifications')
                    ->label('Mod Pricing')
                    ->icon('heroicon-o-pencil-square')
                    ->color('warning')
                    ->button()
                    ->outlined()
                    ->visible(fn (StoreService $record) => in_array($record->service?->key, ['nin_modification', 'bvn_modification']) && $record->hasAccess())
                    ->url(fn () => StoreModificationServiceResource::getUrl('index')),

                Action::make('configure_cac')
                    ->label('CAC Pricing')
                    ->icon('heroicon-o-briefcase')
                    ->color('success')
                    ->button()
                    ->outlined()
                    ->visible(fn (StoreService $record) => $record->service?->key === 'cac_services' && $record->hasAccess())
                    ->url(fn () => StoreCacServiceResource::getUrl('index')),

                Action::make('choose_icon')
                    ->label(fn (StoreService $record) => 'Icon: '.match ($record->getResolvedIcon()) {
                        'cac' => 'CAC',
                        'nimc' => 'NIMC',
                        'nibss' => 'NIBSS',
                        'verification' => 'Verify',
                        default => 'Default',
                    })
                    ->icon('heroicon-o-photo')
                    ->color('gray')
                    ->button()
                    ->outlined()
                    ->visible(fn (StoreService $record) => ! empty($record->getAvailableIconOptions()) && $record->hasAccess())
                    ->modalHeading(fn (StoreService $record) => 'Choose Storefront Icon: '.($record->service?->name ?? 'Service'))
                    ->modalDescription('Select which official icon badge appears for this service on your customer storefront. No custom upload is permitted.')
                    ->modalSubmitActionLabel('Save Icon')
                    ->form(fn (StoreService $record) => [
                        Radio::make('icon')
                            ->label('Select Storefront Icon Badge')
                            ->options(function (StoreService $record): array {
                                $options = [];
                                foreach ($record->getAvailableIconOptions() as $key => $title) {
                                    $options[$key] = match ($key) {
                                        'cac' => new HtmlString('
                                            <div style="display: flex; align-items: center; gap: 14px; padding: 10px 14px; background: #ecfdf5; border: 1.5px solid #a7f3d0; border-radius: 12px; margin: 4px 0;">
                                                <img src="'.asset('images/icons/cac.png').'" alt="CAC" style="width: 48px; height: 48px; object-fit: contain; background: #ffffff; border-radius: 10px; padding: 4px; box-shadow: 0 2px 4px rgba(0,0,0,0.08);" />
                                                <div style="text-align: left;">
                                                    <div style="font-size: 14px; font-weight: 800; color: #065f46;">Official CAC Logo Badge</div>
                                                    <div style="font-size: 12px; color: #047857;">Authentic Corporate Affairs Commission green emblem</div>
                                                </div>
                                            </div>
                                        '),
                                        'nimc' => new HtmlString('
                                            <div style="display: flex; align-items: center; gap: 14px; padding: 10px 14px; background: #eff6ff; border: 1.5px solid #bfdbfe; border-radius: 12px; margin: 4px 0;">
                                                <img src="'.asset('images/icons/nimc.png').'" alt="NIMC" style="width: 48px; height: 48px; object-fit: contain; background: #ffffff; border-radius: 10px; padding: 4px; box-shadow: 0 2px 4px rgba(0,0,0,0.08);" />
                                                <div style="text-align: left;">
                                                    <div style="font-size: 14px; font-weight: 800; color: #1e40af;">Official NIMC Logo Badge</div>
                                                    <div style="font-size: 12px; color: #1d4ed8;">Authentic National Identity Management Commission logo</div>
                                                </div>
                                            </div>
                                        '),
                                        'nibss' => new HtmlString('
                                            <div style="display: flex; align-items: center; gap: 14px; padding: 10px 14px; background: #faf5ff; border: 1.5px solid #d8b4fe; border-radius: 12px; margin: 4px 0;">
                                                <img src="'.asset('images/icons/nibss.png').'" alt="NIBSS" style="width: 48px; height: 48px; object-fit: contain; background: #ffffff; border-radius: 10px; padding: 4px; box-shadow: 0 2px 4px rgba(0,0,0,0.08);" />
                                                <div style="text-align: left;">
                                                    <div style="font-size: 14px; font-weight: 800; color: #6b21a8;">Official NIBSS Logo Badge</div>
                                                    <div style="font-size: 12px; color: #7e22ce;">Authentic Nigeria Inter-Bank Settlement System logo</div>
                                                </div>
                                            </div>
                                        '),
                                        'verification' => new HtmlString('
                                            <div style="display: flex; align-items: center; gap: 14px; padding: 10px 14px; background: #fffbeb; border: 1.5px solid #fde68a; border-radius: 12px; margin: 4px 0;">
                                                <img src="'.asset('images/icons/verification.png').'" alt="Verification" style="width: 48px; height: 48px; object-fit: contain; background: #ffffff; border-radius: 10px; padding: 4px; box-shadow: 0 2px 4px rgba(0,0,0,0.08);" />
                                                <div style="text-align: left;">
                                                    <div style="font-size: 14px; font-weight: 800; color: #92400e;">Official Verification Shield Badge</div>
                                                    <div style="font-size: 12px; color: #b45309;">Golden verification security badge emblem</div>
                                                </div>
                                            </div>
                                        '),
                                        default => new HtmlString('
                                            <div style="display: flex; align-items: center; gap: 14px; padding: 10px 14px; background: #f9fafb; border: 1.5px solid #e5e7eb; border-radius: 12px; margin: 4px 0;">
                                                <div style="width: 48px; height: 48px; background: #e5e7eb; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 18px; color: #4b5563;">V</div>
                                                <div style="text-align: left;">
                                                    <div style="font-size: 14px; font-weight: 800; color: #374151;">System Vector Icon</div>
                                                    <div style="font-size: 12px; color: #6b7280;">Standard system storefront icon</div>
                                                </div>
                                            </div>
                                        '),
                                    };
                                }

                                return $options;
                            })
                            ->default($record->icon ?? match (true) {
                                str_contains($record->service?->key ?? '', 'cac') => 'cac',
                                str_contains($record->service?->key ?? '', 'nin') => 'nimc',
                                str_contains($record->service?->key ?? '', 'bvn') => 'nibss',
                                default => 'default',
                            })
                            ->required(),
                    ])
                    ->action(function (StoreService $record, array $data) {
                        $allowed = array_keys($record->getAvailableIconOptions());
                        if (! in_array($data['icon'], $allowed, true)) {
                            Notification::make()
                                ->title('Invalid icon selection')
                                ->danger()
                                ->send();

                            return;
                        }

                        $record->update(['icon' => $data['icon']]);

                        Notification::make()
                            ->title('Storefront Icon Updated')
                            ->body(($record->service?->name ?? 'Service').' icon updated successfully.')
                            ->success()
                            ->send();
                    }),

                Action::make('upgrade_plan')
                    ->label('Upgrade Plan')
                    ->icon('heroicon-o-lock-closed')
                    ->color('danger')
                    ->button()
                    ->outlined()
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
