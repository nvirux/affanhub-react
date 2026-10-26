<?php

namespace App\Filament\Merchant\Resources;

use App\Filament\Merchant\Resources\StoreModificationServiceResource\Pages\EditStoreModificationService;
use App\Filament\Merchant\Resources\StoreModificationServiceResource\Pages\ListStoreModificationServices;
use App\Models\Service;
use App\Models\StoreModificationService;
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
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use UnitEnum;

class StoreModificationServiceResource extends Resource
{
    protected static ?string $model = StoreModificationService::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPencilSquare;

    protected static string|UnitEnum|null $navigationGroup = 'Identity Services';

    protected static ?string $slug = 'modification-services';

    protected static ?string $navigationLabel = 'Modification Pricing';

    protected static ?string $modelLabel = 'Modification Service';

    protected static ?string $pluralModelLabel = 'Modification Services';

    protected static ?string $tenantRelationshipName = 'modificationServices';

    protected static ?int $navigationSort = 5;

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
            Section::make('Basic Pricing & Details')
                ->description('Set your selling price, active status, and turnaround time for this identity modification service.')
                ->schema([
                    Select::make('service_id')
                        ->label('Service Category')
                        ->options(
                            Service::whereIn('key', ['nin_modification', 'bvn_modification'])
                                ->pluck('name', 'id')
                        )
                        ->required(),

                    TextInput::make('name')
                        ->label('Service Title')
                        ->placeholder('e.g. Change of Name, Date of Birth Correction')
                        ->required()
                        ->maxLength(150),

                    TextInput::make('price')
                        ->label('Customer Selling Price (₦)')
                        ->numeric()
                        ->prefix('₦')
                        ->required()
                        ->minValue(0),

                    TextInput::make('processing_time')
                        ->label('Estimated Processing Time')
                        ->placeholder('e.g. 24 - 48 Hours, 2 - 3 Days')
                        ->default('24 - 48 Hours')
                        ->required(),

                    Toggle::make('is_active')
                        ->label('Offer on Storefront')
                        ->helperText('Enable or disable this service for your storefront customers.')
                        ->default(true),
                ])
                ->columns(2),

            Section::make('Customer Information Fields')
                ->description('Customize the exact fields and questions the customer must or can fill when submitting this modification.')
                ->columnSpanFull()
                ->schema([
                    Repeater::make('fields')
                        ->label('')
                        ->helperText('Add questions such as "Previous Full Name", "New Active Phone", "State of Origin", etc.')
                        ->schema([
                            TextInput::make('label')
                                ->label('Field Label / Question')
                                ->placeholder('e.g. New Legal Name')
                                ->required(),

                            TextInput::make('placeholder')
                                ->label('Input Placeholder')
                                ->placeholder('e.g. Enter your exact new legal name')
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

            Section::make('Document Upload Checklist')
                ->description('Configure the documents or evidence the customer needs to upload.')
                ->columnSpanFull()
                ->schema([
                    Repeater::make('required_documents')
                        ->label('')
                        ->helperText('Specify required documents like Court Affidavit, Newspaper Cut-out, NIN Slip, etc.')
                        ->schema([
                            TextInput::make('name')
                                ->label('Document Name')
                                ->placeholder('e.g. Court Affidavit, Newspaper Publication')
                                ->required(),

                            TextInput::make('description')
                                ->label('Customer Guidance Note (Optional)')
                                ->placeholder('e.g. Must be signed and stamped by High Court')
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

            Section::make('Customer Instructions & Description')
                ->description('Provide clear instructions and guidelines shown to the customer before submitting.')
                ->schema([
                    Textarea::make('description')
                        ->label('Short Description')
                        ->placeholder('Explain what this modification covers...')
                        ->rows(2)
                        ->nullable(),

                    Textarea::make('instructions')
                        ->label('Customer Submission Guidelines / Instructions')
                        ->placeholder('e.g. Affidavit must be signed by High Court. Name on document must match exactly.')
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
                TextColumn::make('service.name')
                    ->label('Category')
                    ->badge()
                    ->color(fn (?string $state) => str_contains(strtolower($state ?? ''), 'nin') ? 'success' : 'info')
                    ->icon(fn (?string $state) => str_contains(strtolower($state ?? ''), 'nin') ? 'heroicon-o-identification' : 'heroicon-o-user')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('name')
                    ->label('Modification Type')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn (StoreModificationService $record) => $record->processing_time ? "⏱ {$record->processing_time}" : null),

                TextInputColumn::make('price')
                    ->label('Your Store Price (₦)')
                    ->rules(['required', 'numeric', 'min:0'])
                    ->sortable(),

                TextColumn::make('required_documents')
                    ->label('Required Documents')
                    ->badge()
                    ->color('gray')
                    ->state(function (StoreModificationService $record): array {
                        $docs = $record->getNormalizedDocuments();
                        if (empty($docs)) {
                            return ['None'];
                        }

                        return array_map(fn ($d) => $d['name'].($d['is_required'] ? '' : ' (Optional)'), $docs);
                    }),

                ToggleColumn::make('is_active')
                    ->label('Storefront Status')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('service_id')
                    ->label('Category')
                    ->options(
                        Service::whereIn('key', ['nin_modification', 'bvn_modification'])
                            ->pluck('name', 'id')
                    ),
                TernaryFilter::make('is_active')
                    ->label('Enabled Status'),
            ])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    BulkAction::make('enable_all')
                        ->label('Enable Selected')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(fn ($records) => $records->each->update(['is_active' => true])),

                    BulkAction::make('disable_all')
                        ->label('Disable Selected')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->action(fn ($records) => $records->each->update(['is_active' => false])),
                ]),
            ])
            ->defaultSort('sort_order', 'asc')
            ->emptyStateHeading('No Modification Services Configured')
            ->emptyStateDescription('Your default NIN and BVN modification services will be loaded automatically.');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListStoreModificationServices::route('/'),
            'edit' => EditStoreModificationService::route('/{record}/edit'),
        ];
    }
}
