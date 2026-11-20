<?php

namespace App\Filament\Resources\Stores\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ServiceOverridesRelationManager extends RelationManager
{
    protected static string $relationship = 'serviceOverrides';

    protected static ?string $title = 'Custom Service Overrides';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('service_id')
                ->relationship('service', 'name')
                ->searchable()
                ->preload()
                ->required()
                ->label('Service'),
            Toggle::make('is_allowed')
                ->label('Access Allowed')
                ->default(true)
                ->helperText('Grant access even if not in the store plan, or turn off to explicitly revoke access.'),
            TextInput::make('reason')
                ->placeholder('e.g. VIP client bonus or early beta access')
                ->maxLength(255),
            Hidden::make('granted_by')
                ->default(fn () => auth()->id()),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('service.name')
                    ->label('Service')
                    ->sortable()
                    ->searchable()
                    ->weight('bold'),
                IconColumn::make('is_allowed')
                    ->label('Access')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
                TextColumn::make('reason')
                    ->limit(50)
                    ->placeholder('None specified'),
                TextColumn::make('admin.name')
                    ->label('Granted By')
                    ->placeholder('System')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->headerActions([
                CreateAction::make()->label('Add Service Override'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
