<?php

namespace App\Filament\Resources\Services;

use App\Models\Feature;
use App\Models\Plan;
use App\Models\Service;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use UnitEnum;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSquares2x2;

    protected static UnitEnum|string|null $navigationGroup = '⚡ VTU & Services';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Service Details')
                    ->description('General configuration, categorization, and display properties.')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->placeholder('e.g., Airtime to Cash, NIN Verification'),
                        TextInput::make('key')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->placeholder('e.g., airtime_cash, nin_verification'),
                        Select::make('category')
                            ->options([
                                'vtu' => '⚡ VTU Utilities',
                                'identity' => '🪪 Identity Services',
                            ])
                            ->required(),
                        TextInput::make('icon')
                            ->placeholder('e.g., Smartphone, Wifi, IdCard, ArrowRightLeft'),
                        TextInput::make('sort_order')
                            ->numeric()
                            ->default(0),
                        Toggle::make('is_active')
                            ->label('Globally Enabled')
                            ->helperText('If turned off, this service is disabled platform-wide across all stores.')
                            ->default(true),
                        Textarea::make('description')
                            ->columnSpanFull()
                            ->rows(3)
                            ->placeholder('Brief overview of this service...'),
                    ])
                    ->columns(2),

                Section::make('Subscription Plan Access & Feature Gating')
                    ->description('Define which plans can access this service, or unlock it temporarily with promotions and trials.')
                    ->schema([
                        Select::make('feature_id')
                            ->label('Linked Plan Feature')
                            ->relationship('feature', 'name')
                            ->nullable()
                            ->placeholder('None (100% Free - Available to all plans)')
                            ->searchable()
                            ->preload()
                            ->createOptionForm([
                                TextInput::make('name')
                                    ->required()
                                    ->placeholder('e.g., Airtime to Cash Access'),
                                TextInput::make('slug')
                                    ->required()
                                    ->unique(Feature::class, 'slug')
                                    ->placeholder('e.g., vtu_airtime_cash'),
                                Select::make('type')
                                    ->options([
                                        'boolean' => 'Boolean (On / Off)',
                                        'integer' => 'Integer (Limit / Quota)',
                                    ])
                                    ->default('boolean')
                                    ->required(),
                                TextInput::make('default_value')
                                    ->default('false')
                                    ->helperText('Default value for plans without this feature.'),
                                Textarea::make('description')
                                    ->placeholder('Feature description...'),
                            ])
                            ->helperText('Select the Feature that subscription plans must have to access this service. Leave blank to make it free for everyone.'),

                        DateTimePicker::make('promo_until')
                            ->label('🔥 Promotional Global Free Access Until')
                            ->placeholder('No active promotion')
                            ->helperText('If set to a future date, ALL stores (even on Starter) can use this service for free until this date.'),

                        TextInput::make('trial_days')
                            ->label('⏱️ New Store Free Trial Days')
                            ->numeric()
                            ->minValue(0)
                            ->suffix('days')
                            ->placeholder('e.g., 14')
                            ->helperText('Newly created stores get free trial access to this service for this many days after registration.'),
                    ])
                    ->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn (Service $record) => $record->key),
                TextColumn::make('category')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'identity' => 'purple',
                        default => 'info',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'identity' => '🪪 Identity',
                        default => '⚡ VTU',
                    }),
                TextColumn::make('feature.name')
                    ->label('Linked Feature')
                    ->placeholder('None (Unlocked)')
                    ->badge()
                    ->color('warning'),
                TextColumn::make('allowed_plans')
                    ->label('Plan Access')
                    ->state(fn (Service $record) => $record->getAllowedPlansSummary())
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Free (All Plans)', 'All Plans' => 'success',
                        'None / Locked' => 'danger',
                        default => 'primary',
                    }),
                TextColumn::make('special_status')
                    ->label('Promo / Trial')
                    ->state(function (Service $record): string {
                        if ($record->isInPromo()) {
                            return '🔥 Promo until '.$record->promo_until->format('M d, Y');
                        }
                        if ($record->trial_days && $record->trial_days > 0) {
                            return '⏱️ '.$record->trial_days.'d store trial';
                        }

                        return 'Standard';
                    })
                    ->badge()
                    ->color(fn (string $state): string => match (true) {
                        str_starts_with($state, '🔥') => 'danger',
                        str_starts_with($state, '⏱️') => 'info',
                        default => 'gray',
                    }),
                ToggleColumn::make('is_active')
                    ->label('Active'),
                TextColumn::make('sort_order')->sortable(),
                TextColumn::make('created_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('sort_order', 'asc')
            ->recordActions([
                Action::make('manage_plan_access')
                    ->label('⚡ Plan Access')
                    ->icon('heroicon-o-key')
                    ->color('warning')
                    ->modalHeading(fn (Service $record) => "⚡ Manage Plan Access: {$record->name}")
                    ->modalDescription(fn (Service $record) => "Control which subscription tiers can use {$record->name}, set promotional free dates, or offer new-store trials.")
                    ->modalSubmitActionLabel('Save Access Rules')
                    ->form([
                        Radio::make('access_mode')
                            ->label('Access Policy')
                            ->options([
                                'free' => '🔓 Free for All Plans (Available to all stores without restrictions)',
                                'plans' => '🔒 Restricted to Specific Plans (Select which plans unlock this service)',
                            ])
                            ->default(fn (Service $record) => $record->feature_id ? 'plans' : 'free')
                            ->live()
                            ->required(),

                        CheckboxList::make('allowed_plans')
                            ->label('Eligible Plans (Check all tiers that unlock this service)')
                            ->options(Plan::where('is_active', true)->pluck('name', 'id'))
                            ->visible(fn ($get) => $get('access_mode') === 'plans')
                            ->columns(3)
                            ->helperText('Stores on checked plans will have this service enabled. Stores on unchecked plans will be locked.'),

                        DateTimePicker::make('promo_until')
                            ->label('🔥 Promotional Free Window (Optional)')
                            ->helperText('Make this service free for ALL stores (even Starter) until this date and time.'),

                        TextInput::make('trial_days')
                            ->label('⏱️ New Store Trial Days (Optional)')
                            ->numeric()
                            ->minValue(0)
                            ->suffix('days')
                            ->helperText('All new stores get free access to this service for this many days after signup.'),

                        Toggle::make('sync_all_stores')
                            ->label('🔄 Immediately sync StoreService across all stores')
                            ->default(true)
                            ->helperText('Ensures all store service entries are created and updated right away.'),
                    ])
                    ->fillForm(function (Service $record): array {
                        return [
                            'access_mode' => $record->feature_id ? 'plans' : 'free',
                            'allowed_plans' => $record->getAllowedPlanIds(),
                            'promo_until' => $record->promo_until,
                            'trial_days' => $record->trial_days,
                            'sync_all_stores' => true,
                        ];
                    })
                    ->action(function (Service $record, array $data): void {
                        if ($data['access_mode'] === 'free') {
                            $record->feature_id = null;
                        } else {
                            $record->syncPlansAccess($data['allowed_plans'] ?? []);
                        }

                        $record->promo_until = ! empty($data['promo_until']) ? $data['promo_until'] : null;
                        $record->trial_days = ! empty($data['trial_days']) ? (int) $data['trial_days'] : null;
                        $record->save();

                        $syncedCount = 0;
                        if (! empty($data['sync_all_stores'])) {
                            $syncedCount = $record->syncToAllStores();
                        }

                        Notification::make()
                            ->title("Plan access rules updated for {$record->name}")
                            ->body($syncedCount > 0 ? "Synchronized {$syncedCount} store records." : 'All stores already up to date.')
                            ->success()
                            ->send();
                    }),

                Action::make('sync_stores')
                    ->label('Sync Stores')
                    ->icon('heroicon-o-arrow-path')
                    ->color('gray')
                    ->requiresConfirmation()
                    ->modalHeading(fn (Service $record) => "Sync {$record->name} to All Stores")
                    ->modalDescription('This will populate or update the StoreService table for all existing stores based on current plan access.')
                    ->action(function (Service $record): void {
                        $count = $record->syncToAllStores();
                        Notification::make()
                            ->title("Synced {$record->name}")
                            ->body("Processed all stores ({$count} entries updated/created).")
                            ->success()
                            ->send();
                    }),

                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListServices::route('/'),
        ];
    }
}
