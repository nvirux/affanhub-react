<?php

namespace App\Filament\Resources\Services;

use App\Models\Service;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSquares2x2;

    protected static UnitEnum|string|null $navigationGroup = 'Platform Management';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->placeholder('e.g., NIN Verification'),
                TextInput::make('key')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->placeholder('e.g., nin_verification'),
                Select::make('category')
                    ->options([
                        'vtu' => 'VTU Utilities',
                        'identity' => 'Identity Services',
                    ])
                    ->required(),
                Select::make('feature_id')
                    ->label('Required Plan Feature (Gating)')
                    ->relationship('feature', 'name')
                    ->nullable()
                    ->placeholder('None (Available to all plans)'),
                TextInput::make('icon')
                    ->placeholder('e.g., Smartphone, Wifi, IdCard'),
                TextInput::make('sort_order')
                    ->numeric()
                    ->default(0),
                Toggle::make('is_active')
                    ->label('Global Active Status')
                    ->default(true),
                Textarea::make('description')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('name')->searchable()->sortable()->weight('bold'),
                TextColumn::make('key')->searchable(),
                TextColumn::make('category')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'identity' => 'purple',
                        default => 'info',
                    }),
                TextColumn::make('feature.name')
                    ->label('Plan Feature Gating')
                    ->placeholder('All Plans (Free)')
                    ->badge()
                    ->color('warning'),
                TextColumn::make('sort_order')->sortable(),
                IconColumn::make('is_active')
                    ->boolean()
                    ->label('Active'),
                TextColumn::make('created_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('sort_order', 'asc')
            ->recordActions([
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
