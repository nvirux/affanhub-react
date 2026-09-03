<?php

namespace App\Filament\Resources\Stores\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Hidden;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;

class FeatureOverridesRelationManager extends RelationManager
{
    protected static string $relationship = 'featureOverrides';

    protected static ?string $title = 'Custom Feature Overrides';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('feature_id')
                ->relationship('feature', 'name')
                ->required(),
            TextInput::make('value')
                ->required()
                ->placeholder('e.g. 50 or true'),
            TextInput::make('reason')
                ->required()
                ->placeholder('Why is this override granted?'),
            Hidden::make('granted_by')
                ->default(fn () => auth()->id()),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('feature.name')
                    ->label('Feature')
                    ->sortable(),
                TextColumn::make('value')
                    ->badge()
                    ->color('warning'),
                TextColumn::make('reason')
                    ->limit(50),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->headerActions([
                CreateAction::make()->label('Add Feature Override'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
