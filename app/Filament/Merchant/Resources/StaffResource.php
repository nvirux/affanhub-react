<?php

namespace App\Filament\Merchant\Resources;

use App\Filament\Merchant\Resources\StaffResource\Pages\CreateStaff;
use App\Filament\Merchant\Resources\StaffResource\Pages\EditStaff;
use App\Filament\Merchant\Resources\StaffResource\Pages\ListStaff;
use App\Models\Owner;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Illuminate\Support\Facades\Hash;
use UnitEnum;

class StaffResource extends Resource
{
    protected static ?string $model = Owner::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShieldCheck;

    protected static string|UnitEnum|null $navigationGroup = 'Store Management';

    protected static ?string $navigationLabel = 'Staff Members';

    protected static ?string $modelLabel = 'Staff Member';

    protected static ?string $tenantRelationshipName = 'members';

    protected static ?string $tenantOwnershipRelationshipName = 'stores';

    protected static ?int $navigationSort = 2;

    public static function getNavigationBadge(): ?string
    {
        $store = \Filament\Facades\Filament::getTenant();
        return $store ? (string) $store->members()->count() : null;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')
                ->required()
                ->maxLength(255)
                ->placeholder('e.g. Jane Smith'),
            TextInput::make('email')
                ->email()
                ->required()
                ->maxLength(255)
                ->placeholder('e.g. jane@example.com'),
            TextInput::make('phone')
                ->tel()
                ->maxLength(255)
                ->placeholder('e.g. 08012345678'),
            Select::make('role')
                ->options([
                    'manager' => 'Manager',
                    'staff' => 'Staff',
                ])
                ->required()
                ->default('staff'),
            TextInput::make('password')
                ->password()
                ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                ->dehydrated(fn ($state) => filled($state))
                ->required(fn (string $operation): bool => $operation === 'create')
                ->placeholder('Leave blank to keep current password'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('phone')
                    ->searchable(),
                TextColumn::make('pivot.role')
                    ->label('Role')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'owner' => 'success',
                        'manager' => 'warning',
                        'staff' => 'info',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListStaff::route('/'),
            'create' => CreateStaff::route('/create'),
            'edit' => EditStaff::route('/{record}/edit'),
        ];
    }
}
