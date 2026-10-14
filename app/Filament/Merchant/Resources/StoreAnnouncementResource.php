<?php

namespace App\Filament\Merchant\Resources;

use App\Filament\Merchant\Resources\StoreAnnouncementResource\Pages\ListStoreAnnouncements;
use App\Models\StoreAnnouncement;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use UnitEnum;

class StoreAnnouncementResource extends Resource
{
    protected static ?string $model = StoreAnnouncement::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMegaphone;

    protected static string|UnitEnum|null $navigationGroup = 'Customers & Growth';

    protected static ?string $navigationLabel = 'Announcements';

    protected static ?string $modelLabel = 'Announcement';

    protected static ?string $pluralModelLabel = 'Announcements';

    protected static ?string $tenantRelationshipName = 'announcements';

    protected static ?int $navigationSort = 3;

    public static function getNavigationBadge(): ?string
    {
        return 'New';
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'success';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('title')
                ->label('Announcement Title / Headline')
                ->required()
                ->maxLength(120)
                ->placeholder('e.g. ⚠️ MTN Network Delay or 🎉 Weekend Double Cashback!')
                ->columnSpanFull(),

            Textarea::make('message')
                ->label('Announcement Message')
                ->required()
                ->rows(4)
                ->placeholder('Provide full details for your customers...')
                ->columnSpanFull(),

            Grid::make(2)->schema([
                Select::make('display_style')
                    ->label('Display Format')
                    ->options([
                        'modal' => '🪟 Popup Modal (Recommended - Clean dialog shown on visit)',
                        'banner' => '📌 Slim Notification Pill (Ultra-compact, subtle one-line notice)',
                        'ticker' => '⚡ Scrolling Ticker (Compact moving marquee text)',
                    ])
                    ->default('modal')
                    ->required(),

                Select::make('type')
                    ->label('Notice Mood / Type')
                    ->options([
                        'info' => 'ℹ️ Information (General Update)',
                        'warning' => '⚠️ Warning (Network Slowdown / Delay)',
                        'danger' => '🚨 Urgent (Downtime / Outage)',
                        'promo' => '🎉 Promotion (Special Bonus / Deal)',
                    ])
                    ->default('info')
                    ->required(),
            ]),

            Grid::make(2)->schema([
                TextInput::make('action_label')
                    ->label('Action Button Label (Optional)')
                    ->placeholder('e.g. Try Now, Buy Corporate, Convert Airtime')
                    ->maxLength(50),

                TextInput::make('action_url')
                    ->label('Action Button Link (Optional)')
                    ->placeholder('e.g. /vtu/data, /vtu/airtime-to-cash, or https://...'),
            ]),

            Grid::make(2)->schema([
                Toggle::make('is_active')
                    ->label('Live Active')
                    ->default(true)
                    ->helperText('Enable or pause this announcement on your storefront'),

                Toggle::make('is_dismissible')
                    ->label('Allow Customer to Dismiss')
                    ->default(true)
                    ->helperText('Customers can dismiss with a close button'),
            ]),

            Grid::make(2)->schema([
                DateTimePicker::make('starts_at')
                    ->label('Start Date (Optional)')
                    ->helperText('Leave empty to start immediately'),

                DateTimePicker::make('expires_at')
                    ->label('Expiry Date (Optional)')
                    ->helperText('Automatically hides after this date and time'),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Announcement')
                    ->searchable()
                    ->weight('bold')
                    ->description(fn (StoreAnnouncement $record): string => Str::limit($record->message, 60))
                    ->wrap(),

                TextColumn::make('display_style')
                    ->label('Format')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'modal' => '🪟 Popup Modal',
                        'ticker' => '⚡ Scrolling Ticker',
                        default => '📌 Top Banner',
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'modal' => 'primary',
                        'ticker' => 'warning',
                        default => 'gray',
                    }),

                TextColumn::make('type')
                    ->label('Type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'warning' => 'warning',
                        'danger' => 'danger',
                        'promo' => 'success',
                        default => 'info',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'warning' => '⚠️ Warning',
                        'danger' => '🚨 Urgent',
                        'promo' => '🎉 Promo',
                        default => 'ℹ️ Info',
                    }),

                ToggleColumn::make('is_active')
                    ->label('Active'),

                TextColumn::make('action_label')
                    ->label('Button')
                    ->placeholder('None')
                    ->badge()
                    ->color('gray'),

                TextColumn::make('expires_at')
                    ->label('Expires')
                    ->dateTime('M d, Y h:i A')
                    ->placeholder('Never')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('M d, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([
                EditAction::make()
                    ->modalHeading('Edit Announcement')
                    ->modalWidth('2xl'),
                DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListStoreAnnouncements::route('/'),
        ];
    }
}
