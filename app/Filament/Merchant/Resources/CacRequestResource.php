<?php

namespace App\Filament\Merchant\Resources;

use App\Filament\Merchant\Resources\CacRequestResource\Pages\ListCacRequests;
use App\Models\CacRequest;
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

class CacRequestResource extends Resource
{
    protected static ?string $model = CacRequest::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static string|UnitEnum|null $navigationGroup = 'CAC Services';

    protected static ?string $slug = 'cac-requests';

    protected static ?string $navigationLabel = 'CAC Applications & Orders';

    protected static ?string $modelLabel = 'CAC Order';

    protected static ?string $pluralModelLabel = 'CAC Orders';

    protected static ?string $tenantRelationshipName = 'cacRequests';

    protected static ?string $tenantOwnershipRelationshipName = 'store';

    protected static ?int $navigationSort = 2;

    public static function getNavigationBadge(): ?string
    {
        $store = Filament::getTenant();
        if (! $store) {
            return null;
        }

        try {
            $pending = CacRequest::where('store_id', $store->id)
                ->where('status', 'pending')
                ->count();

            return $pending > 0 ? (string) $pending : null;
        } catch (\Throwable) {
            return null;
        }
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('reference')
                    ->label('Reference')
                    ->searchable()
                    ->copyable()
                    ->fontFamily('mono')
                    ->weight('bold')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Submitted')
                    ->dateTime('M d, Y · h:i A')
                    ->sortable(),

                TextColumn::make('user.name')
                    ->label('Customer')
                    ->searchable()
                    ->description(fn (CacRequest $record) => $record->user?->phone ?? $record->user?->email),

                TextColumn::make('service_name')
                    ->label('Package')
                    ->searchable()
                    ->description(fn (CacRequest $record) => $record->business_name ? 'Name: '.$record->business_name : null)
                    ->weight('semibold'),

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
                        'processing' => 'Processing at CAC',
                        'completed' => 'Completed / Approved',
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
                    ->modalHeading(fn (CacRequest $record) => "CAC Application: {$record->reference}")
                    ->modalContent(fn (CacRequest $record) => view('filament.merchant.components.cac-request-modal', ['record' => $record]))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Close'),

                Action::make('markProcessing')
                    ->label('Start Processing')
                    ->icon('heroicon-o-arrow-path')
                    ->color('info')
                    ->visible(fn (CacRequest $record) => $record->isPending())
                    ->requiresConfirmation()
                    ->modalHeading('Mark as In Progress')
                    ->modalDescription('Notify customer that CAC registration filing has begun on the CAC portal.')
                    ->form([
                        TextInput::make('notes')
                            ->label('Internal Note / CAC Filing Reference (Optional)')
                            ->placeholder('e.g. Filed on CAC CRP portal under filing #12345'),
                    ])
                    ->action(function (CacRequest $record, array $data) {
                        $record->markProcessing($data['notes'] ?? null);
                        Notification::make()
                            ->title('Order Marked as Processing')
                            ->body("Customer notified that CAC application #{$record->reference} is in progress.")
                            ->info()
                            ->send();
                    }),

                Action::make('complete')
                    ->label('Approve & Complete')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (CacRequest $record) => in_array($record->status, ['pending', 'processing']))
                    ->requiresConfirmation()
                    ->modalHeading(fn (CacRequest $record) => "Complete CAC Application ({$record->reference})")
                    ->modalDescription(function (CacRequest $record) {
                        return 'Completing this order will mark it finished and settle ₦'.number_format((float) $record->amount_paid, 2).' to your Merchant Profit Wallet.';
                    })
                    ->form([
                        FileUpload::make('result_file')
                            ->label('Official CAC Certificate / Status Report (PDF or Image)')
                            ->disk('public')
                            ->directory('cac/certificates')
                            ->visibility('public')
                            ->acceptedFileTypes(['application/pdf', 'image/*'])
                            ->maxSize(10240)
                            ->helperText('Upload the final issued CAC Certificate, Status Report, or TIN document for customer download.')
                            ->nullable(),

                        TextInput::make('notes')
                            ->label('Completion Note for Customer')
                            ->placeholder('e.g. CAC registration approved. Official certificate and status report attached.')
                            ->nullable(),
                    ])
                    ->action(function (CacRequest $record, array $data) {
                        try {
                            $record->complete(
                                resultFilePath: $data['result_file'] ?? null,
                                notes: $data['notes'] ?? null
                            );

                            Notification::make()
                                ->title('CAC Registration Completed Successfully!')
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
                    ->visible(fn (CacRequest $record) => in_array($record->status, ['pending', 'processing']))
                    ->requiresConfirmation()
                    ->modalHeading(fn (CacRequest $record) => "Reject CAC Application ({$record->reference})")
                    ->modalDescription(fn (CacRequest $record) => 'Rejecting will immediately refund ₦'.number_format((float) $record->amount_paid, 2).' back to the customer\'s wallet balance.')
                    ->form([
                        Select::make('reason_preset')
                            ->label('Rejection Reason')
                            ->options([
                                'Proposed business names denied or already registered by someone else' => 'Proposed business names denied or already registered by someone else',
                                'Uploaded ID or signature is blurry or unreadable' => 'Uploaded ID or signature is blurry or unreadable',
                                'Name on ID does not match proprietor information' => 'Name on ID does not match proprietor information',
                                'Additional statutory documentation required by CAC' => 'Additional statutory documentation required by CAC',
                                'Other' => 'Other / Custom Reason',
                            ])
                            ->required()
                            ->reactive(),

                        Textarea::make('reason_custom')
                            ->label('Specific Explanation for Customer')
                            ->placeholder('Explain clearly what needs correction...')
                            ->visible(fn ($get) => $get('reason_preset') === 'Other')
                            ->required(fn ($get) => $get('reason_preset') === 'Other'),

                        TextInput::make('notes')
                            ->label('Internal Notes (Optional)')
                            ->nullable(),
                    ])
                    ->action(function (CacRequest $record, array $data) {
                        try {
                            $reason = $data['reason_preset'] === 'Other'
                                ? ($data['reason_custom'] ?? 'Application rejected by merchant.')
                                : $data['reason_preset'];

                            $record->reject($reason, $data['notes'] ?? null);

                            Notification::make()
                                ->title('CAC Order Rejected & Refunded')
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
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCacRequests::route('/'),
        ];
    }
}
