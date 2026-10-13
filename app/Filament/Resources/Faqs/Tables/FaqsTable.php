<?php

namespace App\Filament\Resources\Faqs\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class FaqsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('question')
                    ->label('Question')
                    ->searchable()
                    ->sortable()
                    ->weight('semibold')
                    ->limit(60),

                TextColumn::make('category')
                    ->label('Category')
                    ->badge()
                    ->colors([
                        'primary' => 'capital',
                        'warning' => 'operations',
                        'success' => 'pricing',
                        'info' => 'branding',
                        'gray' => 'general',
                    ])
                    ->sortable(),

                TextColumn::make('audience')
                    ->label('Audience')
                    ->badge()
                    ->colors([
                        'success' => 'all',
                        'info' => 'merchant',
                        'warning' => 'public',
                    ])
                    ->sortable(),

                TextColumn::make('sort_order')
                    ->label('Sort')
                    ->sortable(),

                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->sortable(),

                TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->dateTime('M j, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('sort_order', 'asc')
            ->filters([
                SelectFilter::make('category')
                    ->options([
                        'capital' => '₦0 Capital & Float',
                        'operations' => 'Operations & Failover',
                        'pricing' => 'Pricing & Wholesale',
                        'branding' => 'Domains & Branding',
                        'general' => 'General / Account',
                    ]),
                SelectFilter::make('audience')
                    ->options([
                        'all' => 'Both',
                        'merchant' => 'Merchant Only',
                        'public' => 'Public Only',
                    ]),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
