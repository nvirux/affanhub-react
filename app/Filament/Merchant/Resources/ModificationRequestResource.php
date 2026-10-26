<?php

namespace App\Filament\Merchant\Resources;

use App\Filament\Merchant\Resources\ModificationRequestResource\Pages\ListModificationRequests;
use App\Models\ModificationRequest;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use UnitEnum;

class ModificationRequestResource extends Resource
{
    protected static ?string $model = ModificationRequest::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedInboxStack;

    protected static string|UnitEnum|null $navigationGroup = 'Identity Services';

    protected static ?string $navigationLabel = 'Modification Orders';

    protected static ?string $modelLabel = 'Modification Order';

    protected static ?string $pluralModelLabel = 'Modification Orders';

    protected static ?string $tenantRelationshipName = 'modificationRequests';

    protected static ?int $navigationSort = 6;

    public static function getNavigationBadge(): ?string
    {
        $store = Filament::getTenant();
        if (! $store) {
            return null;
        }

        $pending = ModificationRequest::where('store_id', $store->id)
            ->whereIn('status', ['pending', 'processing'])
            ->count();

        return $pending > 0 ? (string) $pending : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')
                    ->label('Submitted')
                    ->dateTime('M d, Y H:i')
                    ->sortable(),

                TextColumn::make('reference')
                    ->label('Reference')
                    ->searchable()
                    ->copyable()
                    ->weight('bold'),

                TextColumn::make('user.name')
                    ->label('Customer')
                    ->searchable()
                    ->description(fn (ModificationRequest $record) => $record->user?->phone ?? $record->user?->email),

                TextColumn::make('service_name')
                    ->label('Modification Service')
                    ->badge()
                    ->color(fn ($record) => str_contains(strtolower($record->service_name ?? ''), 'nin') ? 'success' : 'info')
                    ->searchable(),

                TextColumn::make('identifier')
                    ->label('Target ID')
                    ->fontFamily('mono')
                    ->searchable(),

                TextColumn::make('amount_paid')
                    ->label('Paid (₦)')
                    ->money('NGN')
                    ->weight('bold')
                    ->sortable(),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'completed' => 'success',
                        'processing' => 'info',
                        'rejected' => 'danger',
                        default => 'warning',
                    })
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending Review',
                        'processing' => 'In Progress / Processing',
                        'completed' => 'Completed',
                        'rejected' => 'Rejected',
                    ]),
            ])
            ->actions([
                Action::make('viewDetails')
                    ->label('View Order')
                    ->icon('heroicon-o-document-magnifying-glass')
                    ->color('gray')
                    ->slideOver()
                    ->modalWidth('4xl')
                    ->modalHeading(fn (ModificationRequest $record) => "Modification Order: {$record->reference}")
                    ->modalContent(fn (ModificationRequest $record) => view('filament.merchant.components.modification-request-modal', ['record' => $record]))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Close'),

                Action::make('markProcessing')
                    ->label('Start Processing')
                    ->icon('heroicon-o-arrow-path')
                    ->color('info')
                    ->visible(fn (ModificationRequest $record) => $record->isPending())
                    ->requiresConfirmation()
                    ->modalHeading('Mark as In Progress')
                    ->modalDescription('Notify the customer that processing has begun on NIMC / BVN systems.')
                    ->form([
                        TextInput::make('notes')
                            ->label('Internal Note / Batch Reference (Optional)')
                            ->placeholder('e.g. Sent to NIMC processing agent'),
                    ])
                    ->action(function (ModificationRequest $record, array $data) {
                        $record->markProcessing($data['notes'] ?? null);
                        Notification::make()
                            ->title('Order Marked as Processing')
                            ->body("Customer notified that order #{$record->reference} is in progress.")
                            ->info()
                            ->send();
                    }),

                Action::make('complete')
                    ->label('Approve & Complete')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (ModificationRequest $record) => in_array($record->status, ['pending', 'processing']))
                    ->requiresConfirmation()
                    ->modalHeading(fn (ModificationRequest $record) => "Complete Modification ({$record->reference})")
                    ->modalDescription(function (ModificationRequest $record) {
                        return 'Completing this order will mark it finished and settle ₦'.number_format((float) $record->amount_paid, 2).' to your Merchant Profit Wallet.';
                    })
                    ->form([
                        FileUpload::make('result_file')
                            ->label('Result Slip / Modified Identity Document (PDF or Image)')
                            ->disk('central_public')
                            ->directory('modifications/results')
                            ->visibility('public')
                            ->acceptedFileTypes(['application/pdf', 'image/*'])
                            ->maxSize(5120)
                            ->helperText('Upload the updated NIN or BVN confirmation slip for the customer to download.')
                            ->nullable(),

                        TextInput::make('notes')
                            ->label('Completion Note for Customer')
                            ->placeholder('e.g. Successfully updated on NIMC database. Slip attached.')
                            ->nullable(),
                    ])
                    ->action(function (ModificationRequest $record, array $data) {
                        try {
                            $record->complete(
                                resultFilePath: $data['result_file'] ?? null,
                                notes: $data['notes'] ?? null
                            );

                            Notification::make()
                                ->title('Modification Completed Successfully!')
                                ->body('Profit of ₦'.number_format((float) $record->amount_paid, 2).' has been credited to your Profit Wallet.')
                                ->success()
                                ->send();
                        } catch (\Throwable $e) {
                            Notification::make()
                                ->title('Failed to complete order')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),

                Action::make('reject')
                    ->label('Reject & Refund')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn (ModificationRequest $record) => in_array($record->status, ['pending', 'processing']))
                    ->requiresConfirmation()
                    ->modalHeading(fn (ModificationRequest $record) => "Reject Modification ({$record->reference})")
                    ->modalDescription(fn (ModificationRequest $record) => 'Rejecting will immediately refund ₦'.number_format((float) $record->amount_paid, 2).' back to the customer\'s wallet balance.')
                    ->form([
                        Select::make('reason_preset')
                            ->label('Rejection Reason')
                            ->options([
                                'Uploaded documents are blurry or unreadable' => 'Uploaded documents are blurry or unreadable',
                                'Court affidavit missing or invalid date/stamp' => 'Court affidavit missing or invalid date/stamp',
                                'Birth certificate attestation not recognized' => 'Birth certificate attestation not recognized',
                                'Name or details mismatch on supporting documents' => 'Name or details mismatch on supporting documents',
                                'NIMC database duplicate record detected' => 'NIMC database duplicate record detected',
                                'Other' => 'Other / Specific Reason',
                            ])
                            ->required()
                            ->reactive(),

                        Textarea::make('reason_custom')
                            ->label('Specific Explanation')
                            ->placeholder('Provide clear instructions on what the customer needs to fix...')
                            ->visible(fn ($get) => $get('reason_preset') === 'Other')
                            ->required(fn ($get) => $get('reason_preset') === 'Other'),

                        TextInput::make('notes')
                            ->label('Internal Notes (Optional)')
                            ->nullable(),
                    ])
                    ->action(function (ModificationRequest $record, array $data) {
                        try {
                            $reason = $data['reason_preset'] === 'Other'
                                ? ($data['reason_custom'] ?? 'Request rejected by merchant.')
                                : $data['reason_preset'];

                            $record->reject($reason, $data['notes'] ?? null);

                            Notification::make()
                                ->title('Order Rejected & Refunded')
                                ->body('₦'.number_format((float) $record->amount_paid, 2)." has been refunded to customer {$record->user?->name}.")
                                ->warning()
                                ->send();
                        } catch (\Throwable $e) {
                            Notification::make()
                                ->title('Failed to reject order')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),
            ])
            ->defaultSort('created_at', 'desc')
            ->emptyStateHeading('No Modification Orders Yet')
            ->emptyStateDescription('When customers submit NIN or BVN modification requests on your storefront, they will appear here.');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListModificationRequests::route('/'),
        ];
    }
}
