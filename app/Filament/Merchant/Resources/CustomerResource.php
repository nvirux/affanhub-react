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
            TextInput::make('phone')
                ->tel()
                ->maxLength(20)
                ->placeholder('e.g. 08012345678'),
            TextInput::make('password')
                ->password()
                ->revealable()
                ->formatStateUsing(fn () => '')
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
                TextColumn::make('phone')
                    ->searchable()
                    ->copyable()
                    ->placeholder('No phone'),
                TextColumn::make('email')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('wallet_balance')
                    ->label('Wallet Balance')
                    ->badge()
                    ->color('success')
                    ->state(fn (User $record) => '₦'.number_format((float) ($record->wallet('main')->balance ?? 0), 2)),
                TextColumn::make('virtual_account')
                    ->label('Dedicated Virtual Account')
                    ->state(function (User $record): string {
                        $va = $record->virtualAccounts()->where('status', 'active')->first();

                        return $va ? "{$va->account_number} ({$va->bank_name})" : 'None';
                    })
                    ->badge(fn (string $state) => $state !== 'None')
                    ->color(fn (string $state) => $state !== 'None' ? 'info' : 'gray'),
                TextColumn::make('transactions_count')
                    ->label('Orders')
                    ->counts('transactions')
                    ->badge()
                    ->color('warning'),
                TextColumn::make('is_active')
                    ->label('Status')
                    ->badge()
                    ->state(fn (User $record): string => $record->is_active !== false ? 'Active' : 'Suspended')
                    ->color(fn (string $state): string => match ($state) {
                        'Active' => 'success',
                        'Suspended' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('created_at')
                    ->label('Joined')
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
                Action::make('suspend')
                    ->label('Ban Customer')
                    ->icon('heroicon-o-no-symbol')
                    ->color('danger')
                    ->visible(fn (User $record): bool => $record->is_active !== false)
                    ->requiresConfirmation()
                    ->modalHeading(fn (User $record): string => "Suspend {$record->name}")
                    ->modalDescription('Are you sure you want to suspend this customer? They will be immediately logged out and blocked from logging in or making any purchases.')
                    ->action(function (User $record): void {
                        $record->ban('Suspended by Store Merchant');

                        Notification::make()
                            ->title("Customer {$record->name} has been suspended.")
                            ->danger()
                            ->send();
                    }),
                Action::make('activate')
                    ->label('Reactivate')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (User $record): bool => $record->is_active === false)
                    ->requiresConfirmation()
                    ->modalHeading(fn (User $record): string => "Reactivate {$record->name}")
                    ->modalDescription('Are you sure you want to restore access for this customer?')
                    ->action(function (User $record): void {
                        $record->activate();

                        Notification::make()
                            ->title("Customer {$record->name} has been reactivated.")
                            ->success()
                            ->send();
                    }),
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            CustomerResource\RelationManagers\TransactionsRelationManager::class,
            CustomerResource\RelationManagers\VirtualAccountsRelationManager::class,
            CustomerResource\RelationManagers\WalletsRelationManager::class,
        ];
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
