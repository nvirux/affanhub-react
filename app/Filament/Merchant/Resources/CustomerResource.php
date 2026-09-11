<?php

namespace App\Filament\Merchant\Resources;

use App\Filament\Merchant\Resources\CustomerResource\Pages\CreateCustomer;
use App\Filament\Merchant\Resources\CustomerResource\Pages\EditCustomer;
use App\Filament\Merchant\Resources\CustomerResource\Pages\ListCustomers;
use App\Models\ActivityLog;
use App\Models\User;
use App\Services\WalletService;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use UnitEnum;

class CustomerResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

    protected static string|UnitEnum|null $navigationGroup = 'Customers & Growth';

    protected static ?string $navigationLabel = 'Customers';

    protected static ?string $modelLabel = 'Customer';

    protected static ?string $tenantRelationshipName = 'users';

    protected static ?string $tenantOwnershipRelationshipName = 'store';

    protected static ?int $navigationSort = 1;

    public static function getNavigationBadge(): ?string
    {
        $store = Filament::getTenant();

        return $store ? (string) $store->users()->count() : null;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')
                ->required()
                ->maxLength(255)
                ->placeholder('e.g. John Doe'),
            TextInput::make('email')
                ->email()
                ->required()
                ->maxLength(255)
                ->placeholder('e.g. john@example.com'),
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
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('email')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('wallet_balance')
                    ->label('Wallet Balance (₦)')
                    ->badge()
                    ->color('success')
                    ->state(fn (User $record) => '₦'.number_format((float) ($record->wallet('main')->balance ?? 0), 2)),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->recordActions([
                Action::make('fund_wallet')
                    ->label('Credit/Debit Wallet')
                    ->icon('heroicon-o-banknotes')
                    ->color('warning')
                    ->form([
                        Select::make('operation')
                            ->label('Action Type')
                            ->options([
                                'credit' => 'Credit (+ Add Money)',
                                'debit' => 'Debit (- Remove Money)',
                            ])
                            ->default('credit')
                            ->required(),
                        TextInput::make('amount')
                            ->label('Amount (₦)')
                            ->numeric()
                            ->prefix('₦')
                            ->rules(['required', 'numeric', 'gt:0']),
                        TextInput::make('reason')
                            ->label('Reason / Reference Note')
                            ->required()
                            ->placeholder('e.g., Cash deposit received via POS / WhatsApp'),
                    ])
                    ->action(function (User $record, array $data, WalletService $walletService): void {
                        $store = Filament::getTenant();
                        $userWallet = $record->wallet('main');
                        $amount = (float) $data['amount'];
                        $reason = $data['reason'];
                        $causer = Auth::user();

                        try {
                            if ($data['operation'] === 'credit') {
                                $walletService->credit(
                                    $userWallet,
                                    $amount,
                                    'manual_merchant_credit',
                                    $reason,
                                    [
                                        'channel' => 'manual',
                                        'funded_by' => 'Merchant',
                                        'user_name' => $record->name,
                                    ]
                                );

                                if ($store) {
                                    ActivityLog::create([
                                        'tenant_id' => $store->id,
                                        'causer_type' => $causer ? get_class($causer) : null,
                                        'causer_id' => $causer?->id,
                                        'event' => 'manual_wallet_credit',
                                        'description' => sprintf('Credited ₦%s to customer %s (%s). Reason: %s', number_format($amount, 2), $record->name, $record->email, $reason),
                                        'properties' => [
                                            'amount' => $amount,
                                            'customer_id' => $record->id,
                                            'customer_name' => $record->name,
                                            'reason' => $reason,
                                        ],
                                    ]);
                                }

                                Notification::make()
                                    ->title(sprintf('Successfully credited ₦%s to %s', number_format($amount, 2), $record->name))
                                    ->success()
                                    ->send();
                            } else {
                                $walletService->debit(
                                    $userWallet,
                                    $amount,
                                    'manual_merchant_debit',
                                    $reason,
                                    [
                                        'channel' => 'manual',
                                        'funded_by' => 'Merchant',
                                        'user_name' => $record->name,
                                    ]
                                );

                                if ($store) {
                                    ActivityLog::create([
                                        'tenant_id' => $store->id,
                                        'causer_type' => $causer ? get_class($causer) : null,
                                        'causer_id' => $causer?->id,
                                        'event' => 'manual_wallet_debit',
                                        'description' => sprintf('Debited ₦%s from customer %s (%s). Reason: %s', number_format($amount, 2), $record->name, $record->email, $reason),
                                        'properties' => [
                                            'amount' => $amount,
                                            'customer_id' => $record->id,
                                            'customer_name' => $record->name,
                                            'reason' => $reason,
                                        ],
                                    ]);
                                }

                                Notification::make()
                                    ->title(sprintf('Successfully debited ₦%s from %s', number_format($amount, 2), $record->name))
                                    ->warning()
                                    ->send();
                            }
                        } catch (\Throwable $e) {
                            Notification::make()
                                ->title('Operation Failed: '.$e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCustomers::route('/'),
            'create' => CreateCustomer::route('/create'),
            'edit' => EditCustomer::route('/{record}/edit'),
        ];
    }
}
