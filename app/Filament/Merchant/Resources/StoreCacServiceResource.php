<?php

namespace App\Filament\Merchant\Resources;

use App\Filament\Merchant\Resources\StoreCacServiceResource\Pages\EditStoreCacService;
use App\Filament\Merchant\Resources\StoreCacServiceResource\Pages\ListStoreCacServices;
use App\Models\StoreCacService;
use BackedEnum;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use UnitEnum;

class StoreCacServiceResource extends Resource
{
    protected static ?string $model = StoreCacService::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice;

    protected static string|UnitEnum|null $navigationGroup = 'CAC Services';

    protected static ?string $slug = 'cac-services';

    protected static ?string $navigationLabel = 'CAC Packages & Pricing';

    protected static ?string $modelLabel = 'CAC Service Package';

    protected static ?string $pluralModelLabel = 'CAC Service Packages';

    protected static ?string $tenantRelationshipName = 'cacServices';

    protected static ?string $tenantOwnershipRelationshipName = 'store';

    protected static ?int $navigationSort = 1;

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
            Section::make('Package Pricing & Turnaround')
                ->description('Set your retail selling price, estimated processing turnaround, and storefront visibility for this CAC service.')
                ->schema([
                    TextInput::make('name')
                        ->label('Package Title')
                        ->placeholder('e.g. Business Name Registration, Company (Ltd) Registration')
                        ->required()
                        ->maxLength(150),

                    TextInput::make('price')
                        ->label('Customer Selling Price (₦)')
                        ->numeric()
                        ->prefix('₦')
                        ->required()
                        ->minValue(0),

                    TextInput::make('processing_time')
                        ->label('Estimated Turnaround Time')
                        ->placeholder('e.g. 3 - 5 Days, 24 - 48 Hours')
                        ->default('3 - 5 Days')
                        ->required(),

                    Toggle::make('is_active')
                        ->label('Offer on Storefront')
                        ->helperText('Enable or disable this package for your storefront customers.')
                        ->default(true),
                ])
                ->columns(2),

            Section::make('Customer Information Questions / Fields')
                ->description('Customize the exact information fields your customer must or can fill when submitting this CAC registration.')
                ->columnSpanFull()
                ->schema([
                    Repeater::make('fields')
                        ->label('')
                        ->helperText('Add fields such as "1st Proposed Business Name", "Nature of Business", "Director 1 Full Name & NIN", etc.')
                        ->schema([
                            TextInput::make('label')
                                ->label('Field Label / Question')
                                ->placeholder('e.g. 1st Proposed Business Name')
                                ->required(),

                            TextInput::make('placeholder')
                                ->label('Input Placeholder')
                                ->placeholder('e.g. Enter your primary choice of business name')
                                ->nullable(),

                            Select::make('type')
                                ->label('Input Type')
                                ->options([
                                    'text' => 'Short Text',
                                    'date' => 'Date (YYYY-MM-DD)',
                                    'number' => 'Number',
                                    'textarea' => 'Paragraph / Note',
                                ])
                                ->default('text')
                                ->required(),

                            Toggle::make('is_required')
                                ->label('Required Field')
                                ->default(true),
                        ])
                        ->columns(2)
                        ->columnSpanFull()
                        ->collapsible()
                        ->default([])
                        ->itemLabel(fn (array $state): ?string => ($state['label'] ?? null) ? ($state['label'].(($state['is_required'] ?? true) ? ' *' : ' (Optional)')) : null),
                ]),

            Section::make('Required Document Upload Checklist')
                ->description('Configure the documents, identification cards, or signatures the customer needs to upload.')
                ->columnSpanFull()
                ->schema([
                    Repeater::make('required_documents')
                        ->label('')
                        ->helperText('Specify required documents like Valid ID Card, Passport Photo, Specimen Signature, etc.')
                        ->schema([
                            TextInput::make('name')
                                ->label('Document Name')
                                ->placeholder('e.g. Valid ID Card, Passport Photograph, Specimen Signature')
                                ->required(),

                            TextInput::make('description')
                                ->label('Customer Guidance Note (Optional)')
                                ->placeholder('e.g. Clear NIN slip or voter\'s card. Signed on plain white paper.')
                                ->nullable(),

                            Toggle::make('is_required')
                                ->label('Required Document')
                                ->columnSpanFull()
                                ->default(true),
                        ])
                        ->columns(2)
                        ->columnSpanFull()
                        ->collapsible()
                        ->default([])
                        ->itemLabel(fn (array $state): ?string => ($state['name'] ?? null) ? ($state['name'].(($state['is_required'] ?? true) ? ' *' : ' (Optional)')) : null),
                ]),

            Section::make('Customer Instructions & Guidelines')
                ->description('Provide clear instructions and guidelines displayed to customers before they submit.')
                ->schema([
                    Textarea::make('description')
                        ->label('Short Description')
                        ->placeholder('Explain what this CAC package includes (Certificate, Status Report, TIN, etc.)...')
                        ->rows(2)
                        ->nullable(),

                    Textarea::make('instructions')
                        ->label('Customer Submission Guidelines / Instructions')
                        ->placeholder('e.g. Provide two distinct names. Ensure IDs match the proprietor\'s legal name.')
                        ->rows(2)
                        ->nullable(),
                ])
                ->columns(1),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Package Title')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextInputColumn::make('price')
                    ->label('Selling Price (₦)')
                    ->type('number')
                    ->rules(['required', 'numeric', 'min:0'])
                    ->sortable(),

                TextInputColumn::make('processing_time')
                    ->label('Turnaround Time')
                    ->rules(['required', 'string', 'max:50']),

                TextColumn::make('fields_count')
                    ->label('Questions')
                    ->state(fn (StoreCacService $record) => count($record->fields ?? []).' fields')
                    ->badge()
                    ->color('gray'),

                TextColumn::make('documents_count')
                    ->label('Documents')
                    ->state(fn (StoreCacService $record) => count($record->required_documents ?? []).' uploads')
                    ->badge()
                    ->color('info'),

                ToggleColumn::make('is_active')
                    ->label('Active on Store')
                    ->sortable(),
            ])
            ->filters([
                TernaryFilter::make('is_active')
                    ->label('Storefront Status')
                    ->boolean()
                    ->trueLabel('Active on Storefront')
                    ->falseLabel('Disabled / Hidden'),
            ])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    BulkAction::make('activate')
                        ->label('Enable Selected')
                        ->icon('heroicon-o-check')
                        ->action(fn ($records) => $records->each->update(['is_active' => true])),
                    BulkAction::make('deactivate')
                        ->label('Disable Selected')
                        ->icon('heroicon-o-x-mark')
                        ->action(fn ($records) => $records->each->update(['is_active' => false])),
                ]),
            ])
            ->defaultSort('sort_order', 'asc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListStoreCacServices::route('/'),
            'edit' => EditStoreCacService::route('/{record}/edit'),
        ];
    }
}
