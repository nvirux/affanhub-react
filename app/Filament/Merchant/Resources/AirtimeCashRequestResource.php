<?php

namespace App\Filament\Merchant\Resources;

use App\Filament\Merchant\Resources\AirtimeCashRequestResource\Pages\ListAirtimeCashRequests;
use App\Models\AirtimeCashRequest;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use UnitEnum;

class AirtimeCashRequestResource extends Resource
{
    protected static ?string $model = AirtimeCashRequest::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArrowsRightLeft;

    protected static string|UnitEnum|null $navigationGroup = 'VTU Services';

    protected static ?string $navigationLabel = 'Airtime to Cash Orders';

    protected static ?string $modelLabel = 'Conversion Order';

    protected static ?string $tenantRelationshipName = 'airtimeCashRequests';

    protected static ?string $tenantOwnershipRelationshipName = 'store';

    protected static ?int $navigationSort = 6;

    public static function getNavigationBadge(): ?string
    {
        $store = Filament::getTenant();
        if (! $store) {
            return null;
        }

        $pending = AirtimeCashRequest::where('store_id', $store->id)
            ->where('status', 'pending')
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
                    ->label('Date & Time')
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
                    ->description(fn (AirtimeCashRequest $record) => $record->user?->phone ?? $record->user?->email),

                TextColumn::make('network.name')
                    ->label('Network')
                    ->badge()
                    ->color(fn ($record) => match (strtolower($record->network?->slug ?? '')) {
                        'mtn' => 'warning',
                        'airtel' => 'danger',
                        'glo' => 'success',
                        '9mobile', 'etisalat' => 'info',
                        default => 'gray',
                    }),

                TextColumn::make('airtime_amount')
                    ->label('Airtime Sent')
                    ->money('NGN')
                    ->weight('bold')
                    ->sortable(),

                TextColumn::make('exchange_rate')
                    ->label('Rate')
                    ->suffix('%')
                    ->badge()
                    ->color('gray'),

                TextColumn::make('cash_amount')
                    ->label('Cash Payout')
                    ->money('NGN')
                    ->weight('bold')
                    ->color('success')
                    ->sortable(),

                TextColumn::make('sender_phone')
                    ->label('Customer SIM')
                    ->copyable()
                    ->searchable()
                    ->description(fn (AirtimeCashRequest $record) => 'Sent to: '.$record->receiving_phone),

                TextColumn::make('payout_method')
                    ->label('Payout Type')
                    ->badge()
                    ->color(fn (string $state) => $state === 'wallet' ? 'info' : 'purple')
                    ->formatStateUsing(fn (string $state) => strtoupper($state))
                    ->description(function (AirtimeCashRequest $record) {
                        if ($record->payout_method === 'bank') {
                            return "{$record->bank_name} • {$record->account_number}";
                        }

                        return 'Credited to Store Wallet';
                    }),

                ImageColumn::make('proof_image')
                    ->label('Proof')
                    ->disk('public')
                    ->width(44)
                    ->height(44)
                    ->circular(false)
                    ->placeholder('None'),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'approved' => 'success',
                        'rejected' => 'danger',
                        default => 'warning',
                    })
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending Review',
                        'approved' => 'Approved & Paid',
                        'rejected' => 'Rejected',
                    ]),
                SelectFilter::make('payout_method')
                    ->options([
                        'wallet' => 'Wallet Payout',
                        'bank' => 'Bank Transfer',
                    ]),
                SelectFilter::make('network_id')
                    ->relationship('network', 'name'),
            ])
            ->actions([
                Action::make('viewProof')
                    ->label('Proof')
                    ->icon('heroicon-o-photo')
                    ->color('gray')
                    ->visible(fn (AirtimeCashRequest $record) => ! empty($record->proof_image))
                    ->modalHeading(fn (AirtimeCashRequest $record) => "Transfer Proof ({$record->reference})")
                    ->modalContent(fn (AirtimeCashRequest $record) => view('filament.merchant.components.airtime-proof-modal', ['record' => $record]))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Close'),

                Action::make('approve')
                    ->label('Approve & Pay')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (AirtimeCashRequest $record) => $record->isPending())
                    ->requiresConfirmation()
                    ->modalHeading(fn (AirtimeCashRequest $record) => "Approve Conversion ({$record->reference})")
                    ->modalDescription(function (AirtimeCashRequest $record) {
                        $proofNote = $record->proof_image ? "\n\n📸 Transfer proof screenshot is attached to this request." : '';
                        if ($record->payout_method === 'wallet') {
                            return 'Verify that you received ₦'.number_format((float) $record->airtime_amount, 2)." airtime on your SIM ({$record->receiving_phone}) from customer SIM ({$record->sender_phone}).\n\nApproving will instantly credit ₦".number_format((float) $record->cash_amount, 2)." to {$record->user?->name}'s wallet.".$proofNote;
                        }

                        return 'Please transfer ₦'.number_format((float) $record->cash_amount, 2)." to:\n• Bank: {$record->bank_name}\n• Account: {$record->account_number}\n• Name: {$record->account_name}\n\nClick confirm once payment is complete.".$proofNote;
                    })
                    ->form([
                        TextInput::make('admin_notes')
                            ->label('Merchant Reference / Notes')
                            ->placeholder('e.g. Bank session ID or airtime received reference')
                            ->nullable(),
                    ])
                    ->action(function (AirtimeCashRequest $record, array $data) {
                        try {
                            $record->approve($data['admin_notes'] ?? null);
                            Notification::make()
                                ->title('Conversion Order Approved')
                                ->body($record->payout_method === 'wallet'
                                    ? '₦'.number_format((float) $record->cash_amount, 2)." was deposited to the customer's wallet."
                                    : 'Marked as completed.')
                                ->success()
                                ->send();
                        } catch (\Throwable $e) {
                            Notification::make()
                                ->title('Action Failed')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),

                Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn (AirtimeCashRequest $record) => $record->isPending())
                    ->form([
                        Select::make('reason')
                            ->label('Rejection Reason')
                            ->options([
                                'Airtime transfer not received on merchant SIM' => 'Airtime transfer not received on merchant SIM',
                                'Incomplete airtime amount transferred' => 'Incomplete airtime amount transferred',
                                'Sender phone number does not match transfer' => 'Sender phone number does not match transfer',
                                'Invalid or unverified transaction' => 'Invalid or unverified transaction',
                                'Other' => 'Other',
                            ])
                            ->required(),
                        Textarea::make('custom_note')
                            ->label('Additional Details / Reason')
                            ->placeholder('Provide specific note to customer if needed')
                            ->nullable(),
                    ])
                    ->action(function (AirtimeCashRequest $record, array $data) {
                        $reason = $data['reason'] === 'Other' && ! empty($data['custom_note'])
                            ? $data['custom_note']
                            : ($data['reason'].(! empty($data['custom_note']) ? ' - '.$data['custom_note'] : ''));

                        $record->reject($reason);
                        Notification::make()
                            ->title('Order Rejected')
                            ->body('Order has been marked as rejected.')
                            ->warning()
                            ->send();
                    }),

                Action::make('viewDetails')
                    ->label('View Details')
                    ->icon('heroicon-o-eye')
                    ->color('gray')
                    ->modalWidth('2xl')
                    ->modalHeading(fn (AirtimeCashRequest $record) => "Airtime to Cash Order ({$record->reference})")
                    ->modalContent(fn (AirtimeCashRequest $record) => view('filament.merchant.components.airtime-cash-details-modal', ['record' => $record]))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Close'),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAirtimeCashRequests::route('/'),
        ];
    }
}
