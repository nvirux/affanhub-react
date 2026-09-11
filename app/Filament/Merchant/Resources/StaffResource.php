<?php

namespace App\Filament\Merchant\Resources;

use App\Filament\Merchant\Resources\StaffResource\Pages\CreateStaff;
use App\Filament\Merchant\Resources\StaffResource\Pages\EditStaff;
use App\Filament\Merchant\Resources\StaffResource\Pages\ListStaff;
use App\Models\Owner;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Facades\Filament;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;
use UnitEnum;

class StaffResource extends Resource
{
    protected static ?string $model = Owner::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShieldCheck;

    protected static string|UnitEnum|null $navigationGroup = 'Customers & Growth';

    protected static ?string $navigationLabel = 'Staff Members';

    protected static ?string $modelLabel = 'Staff Member';

    protected static ?string $tenantRelationshipName = 'members';

    protected static ?string $tenantOwnershipRelationshipName = 'stores';

    protected static ?int $navigationSort = 2;

    public static function canViewAny(): bool
    {
        $tenant = Filament::getTenant();
        $user = auth()->user();
        if (! $tenant || ! $user) {
            return false;
        }

        $role = $tenant->members()->where('owner_id', $user->id)->first()?->pivot?->role;

        return in_array($role, ['owner', 'manager']);
    }

    public static function getNavigationBadge(): ?string
    {
        $store = Filament::getTenant();

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
                    'owner' => 'Owner',
                    'manager' => 'Manager',
                    'staff' => 'Staff',
                ])
                ->disabled(fn (?Owner $record) => $record?->pivot?->role === 'owner' || $record?->id === auth()->id())
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
                EditAction::make()
                    ->hidden(fn (Owner $record) => $record->pivot?->role === 'owner' && $record->id !== auth()->id()),
                Action::make('remove')
                    ->label('Remove')
                    ->icon('heroicon-o-user-minus')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Remove Staff Member')
                    ->modalDescription('Are you sure you want to remove this staff member from this store? Their user account will remain intact, but they will lose access to this store.')
                    ->action(function (Owner $record) {
                        $tenant = Filament::getTenant();
                        if ($tenant) {
                            $tenant->members()->detach($record->id);
                        }

                        Notification::make()
                            ->title('Staff member removed from store')
                            ->success()
                            ->send();
                    })
                    ->hidden(fn (Owner $record) => $record->id === auth()->id() || $record->pivot?->role === 'owner'),
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
