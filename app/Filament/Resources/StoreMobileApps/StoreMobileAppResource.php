<?php

namespace App\Filament\Resources\StoreMobileApps;

use App\Filament\Resources\StoreMobileApps\Pages\CreateStoreMobileApp;
use App\Filament\Resources\StoreMobileApps\Pages\EditStoreMobileApp;
use App\Filament\Resources\StoreMobileApps\Pages\ListStoreMobileApps;
use App\Filament\Resources\StoreMobileApps\Schemas\StoreMobileAppForm;
use App\Filament\Resources\StoreMobileApps\Tables\StoreMobileAppsTable;
use App\Models\StoreMobileApp;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class StoreMobileAppResource extends Resource
{
    protected static ?string $model = StoreMobileApp::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDevicePhoneMobile;

    protected static UnitEnum|string|null $navigationGroup = '🏬 Tenancy & Stores';

    protected static ?string $navigationLabel = 'Merchant Mobile Apps';

    protected static ?int $navigationSort = 4;

    public static function getNavigationBadge(): ?string
    {
        $count = StoreMobileApp::count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'primary';
    }

    public static function form(Schema $schema): Schema
    {
        return StoreMobileAppForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StoreMobileAppsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListStoreMobileApps::route('/'),
            'create' => CreateStoreMobileApp::route('/create'),
            'edit' => EditStoreMobileApp::route('/{record}/edit'),
        ];
    }
}
