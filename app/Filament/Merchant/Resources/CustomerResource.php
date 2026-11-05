<?php

namespace App\Filament\Merchant\Resources;

use App\Filament\Merchant\Resources\CustomerResource\Pages\CreateCustomer;
use App\Filament\Merchant\Resources\CustomerResource\Pages\EditCustomer;
use App\Filament\Merchant\Resources\CustomerResource\Pages\ListCustomers;
use App\Mail\CustomerDirectMessageMail;
use App\Models\ActivityLog;
use App\Models\User;
use App\Services\Communication\MerchantOutreachService;
use App\Services\WalletService;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Facades\Filament;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
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
            Section::make('Customer Profile')
                ->description('Basic customer contact information')
                ->schema([
                    TextInput::make('name')
                        ->label('Full Name')
                        ->required()
                        ->maxLength(255)
                        ->placeholder('e.g. John Doe'),

                    TextInput::make('email')
                        ->label('Email Address')
                        ->email()
                        ->required()
                        ->maxLength(255)
                        ->placeholder('e.g. john@example.com'),

                    TextInput::make('phone')
                        ->label('Phone Number')
                        ->tel()
                        ->maxLength(20)
                        ->placeholder('e.g. 08012345678'),

                    TextInput::make('referral_code')
                        ->label('Referral Code')
                        ->maxLength(50)
                        ->placeholder('Auto-generated if blank'),
                ])
                ->columns(2),

            Section::make('KYC & Verification')
                ->description('Regulatory identification info')
                ->schema([
                    TextInput::make('bvn')
                        ->label('BVN')
                        ->password()
                        ->revealable()
                        ->maxLength(11),

                    TextInput::make('nin')
                        ->label('NIN')
                        ->password()
                        ->revealable()
                        ->maxLength(11),
                ])
                ->columns(2),

            Section::make('Security & Access')
                ->description('Manage credentials and login state')
                ->schema([
                    TextInput::make('password')
                        ->label('Password')
                        ->password()
                        ->revealable()
                        ->formatStateUsing(fn () => '')
                        ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                        ->dehydrated(fn ($state) => filled($state))
                        ->required(fn (string $operation): bool => $operation === 'create')
                        ->placeholder('Leave blank to keep current password'),

                    Toggle::make('is_active')
                        ->label('Account Active')
                        ->helperText('Allow customer to sign in and make purchases')
                        ->default(true),

                    Toggle::make('login_pin_enabled')
                        ->label('Require Login PIN')
                        ->default(false),
                ])
                ->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->with(['wallets', 'virtualAccounts'])->withCount(['transactions', 'virtualAccounts']))
            ->columns([
                TextColumn::make('name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(function (User $record): ?string {
                        $parts = [];
                        if ($record->phone) {
                            $parts[] = $record->phone;
                        }
                        if ($record->referral_code) {
                            $parts[] = "Ref: {$record->referral_code}";
                        }

                        return implode(' • ', $parts);
                    }),

                TextColumn::make('email')
                    ->label('Email Address')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->copyMessage('Email copied')
                    ->icon('heroicon-m-envelope')
                    ->color('gray'),

                TextColumn::make('wallet_balance')
                    ->label('Wallet Balance')
                    ->badge()
                    ->color(function (User $record): string {
                        $bal = (float) ($record->wallets->firstWhere('type', 'main')?->balance ?? 0);

                        return $bal > 0 ? 'success' : 'gray';
                    })
                    ->state(fn (User $record) => '₦'.number_format((float) ($record->wallets->firstWhere('type', 'main')?->balance ?? 0), 2)),

                TextColumn::make('virtual_account')
                    ->label('Virtual Bank Acct')
                    ->state(function (User $record): string {
                        $primary = $record->virtualAccounts->where('status', 'active')->first();
                        if (! $primary) {
                            return 'None';
                        }
                        $count = $record->virtualAccounts->where('status', 'active')->count();
                        $text = "{$primary->bank_name}: {$primary->account_number}";
                        if ($count > 1) {
                            $text .= ' (+'.($count - 1).')';
                        }

                        return $text;
                    })
                    ->badge(fn (string $state) => $state !== 'None')
                    ->color(fn (string $state) => $state !== 'None' ? 'info' : 'gray')
                    ->copyable(fn (User $record): ?string => $record->virtualAccounts->where('status', 'active')->first()?->account_number)
                    ->copyMessage('Account number copied')
                    ->tooltip(function (User $record): ?string {
                        if ($record->virtualAccounts->isEmpty()) {
                            return 'No virtual accounts provisioned yet.';
                        }

                        return $record->virtualAccounts->map(fn ($va) => "{$va->bank_name} ({$va->account_number}) - {$va->account_name}")->join(' | ');
                    }),

                TextColumn::make('transactions_count')
                    ->label('Orders')
                    ->counts('transactions')
                    ->badge()
                    ->color('warning')
                    ->alignCenter()
                    ->sortable(),

                TextColumn::make('kyc_status')
                    ->label('KYC Status')
                    ->badge()
                    ->state(function (User $record): string {
                        if (! empty($record->bvn)) {
                            return 'BVN Verified';
                        }
                        if (! empty($record->nin)) {
                            return 'NIN Verified';
                        }

                        return 'Unverified';
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'BVN Verified' => 'success',
                        'NIN Verified' => 'info',
                        default => 'gray',
                    }),

                TextColumn::make('is_active')
                    ->label('Status')
                    ->badge()
                    ->state(fn (User $record): string => $record->isActive() ? 'Active' : 'Suspended')
                    ->color(fn (string $state): string => match ($state) {
                        'Active' => 'success',
                        'Suspended' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('created_at')
                    ->label('Joined')
                    ->dateTime('M d, Y H:i')
                    ->description(fn (User $record): string => $record->created_at?->diffForHumans() ?? '')
                    ->sortable(),
            ])
            ->filters([
                TernaryFilter::make('is_active')
                    ->label('Account Status')
                    ->trueLabel('Active Only')
                    ->falseLabel('Suspended Only')
                    ->queries(
                        true: fn (Builder $query) => $query->where('is_active', true),
                        false: fn (Builder $query) => $query->where('is_active', false),
                    ),

                Filter::make('has_balance')
                    ->label('Funded (> ₦0 Balance)')
                    ->query(fn (Builder $query) => $query->whereHas('wallets', fn ($q) => $q->where('type', 'main')->where('balance', '>', 0))),

                TernaryFilter::make('has_bvn')
                    ->label('BVN / KYC Verified')
                    ->queries(
                        true: fn (Builder $query) => $query->whereNotNull('bvn')->where('bvn', '!=', ''),
                        false: fn (Builder $query) => $query->whereNull('bvn')->orWhere('bvn', ''),
                    ),

                Filter::make('has_virtual_account')
                    ->label('Has Dedicated Virtual Account')
                    ->query(fn (Builder $query) => $query->has('virtualAccounts')),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make()
                        ->slideOver(),

                    EditAction::make(),

                    Action::make('whatsapp_customer')
                        ->label('Chat on WhatsApp')
                        ->icon('heroicon-o-chat-bubble-left-right')
                        ->color('success')
                        ->modalHeading(fn (User $record): string => "WhatsApp Customer: {$record->name}")
                        ->modalDescription('Choose a customer service template or enter your custom message to open WhatsApp.')
                        ->modalSubmitActionLabel('Open WhatsApp Chat ↗')
                        ->form(function (User $record) {
                            $store = Filament::getTenant();
                            $templates = MerchantOutreachService::getCustomerTemplates($record->name, $store?->name);
                            $options = collect($templates)->mapWithKeys(fn ($item, $key) => [$key => $item['label']])->toArray();
                            $options['custom'] = '✏️ Custom Message';

                            return [
                                TextInput::make('phone')
                                    ->label('WhatsApp Phone Number')
                                    ->default(fn () => $record->phone)
                                    ->required()
                                    ->helperText('Formatted automatically for WhatsApp (+234...).'),

                                Select::make('template')
                                    ->label('Choose Message Template')
                                    ->options($options)
                                    ->default(empty($record->bvn) ? 'kyc_verification' : 'low_balance_topup')
                                    ->live()
                                    ->afterStateUpdated(function ($state, $set) use ($templates) {
                                        if (isset($templates[$state])) {
                                            $set('message', $templates[$state]['message']);
                                        }
                                    }),

                                Textarea::make('message')
                                    ->label('Message Content')
                                    ->default(function () use ($record, $templates) {
                                        $defKey = empty($record->bvn) ? 'kyc_verification' : 'low_balance_topup';

                                        return $templates[$defKey]['message'] ?? '';
                                    })
                                    ->rows(4)
                                    ->required(),
                            ];
                        })
                        ->action(function (array $data) {
                            $url = MerchantOutreachService::buildWhatsAppUrl($data['phone'], $data['message']);

                            return redirect()->away($url);
                        }),

                    Action::make('send_email')
                        ->label('Send Direct Email')
                        ->icon('heroicon-o-envelope')
                        ->color('info')
                        ->modalHeading(fn (User $record): string => "Email {$record->name}")
                        ->modalSubmitActionLabel('Send Email Now')
                        ->form([
                            TextInput::make('subject')
                                ->label('Email Subject')
                                ->default('Notice Regarding Your Store Account')
                                ->required(),
                            Textarea::make('body')
                                ->label('Message Body')
                                ->placeholder('Write your message to your customer here...')
                                ->rows(6)
                                ->required(),
                        ])
                        ->action(function (User $record, array $data) {
                            $store = Filament::getTenant();

                            try {
                                Mail::to($record->email)->send(
                                    new CustomerDirectMessageMail($record, $data['subject'], $data['body'])
                                );

                                if ($store) {
                                    ActivityLog::create([
                                        'tenant_id' => $store->id,
                                        'causer_type' => Auth::user() ? get_class(Auth::user()) : null,
                                        'causer_id' => Auth::id(),
                                        'event' => 'merchant_sent_customer_email',
                                        'description' => "Merchant sent direct email to customer {$record->name} ({$record->email}): \"{$data['subject']}\"",
                                        'properties' => [
                                            'customer_id' => $record->id,
                                            'email' => $record->email,
                                            'subject' => $data['subject'],
                                        ],
                                    ]);
                                }

                                Notification::make()
                                    ->title("Email sent to {$record->email} successfully.")
                                    ->success()
                                    ->send();
                            } catch (\Throwable $e) {
                                Notification::make()
                                    ->title('Failed to send email: '.$e->getMessage())
                                    ->danger()
                                    ->send();
                            }
                        }),

                    Action::make('fund_wallet')
                        ->label('Credit / Debit Wallet')
                        ->icon('heroicon-o-banknotes')
                        ->color('warning')
                        ->modalHeading(fn (User $record): string => "Adjust Wallet: {$record->name}")
                        ->modalDescription(function (User $record): string {
                            $bal = $record->wallet('main')->balance ?? 0.00;

                            return 'Current Customer Wallet Balance: ₦'.number_format((float) $bal, 2);
                        })
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

                    Action::make('reset_security')
                        ->label('Security & Password')
                        ->icon('heroicon-o-key')
                        ->color('gray')
                        ->modalHeading(fn (User $record): string => "Security Controls: {$record->name}")
                        ->form([
                            Select::make('action_type')
                                ->label('Select Action')
                                ->options([
                                    'send_password_reset' => 'Send Password Reset Email Link',
                                    'set_password_manual' => 'Set New Password Manually',
                                    'reset_transaction_pin' => 'Reset Transaction PIN (Clear PIN)',
                                    'reset_login_pin' => 'Reset / Disable Login PIN',
                                ])
                                ->default('send_password_reset')
                                ->live()
                                ->required(),

                            TextInput::make('new_password')
                                ->label('New Password')
                                ->password()
                                ->revealable()
                                ->visible(fn ($get) => $get('action_type') === 'set_password_manual')
                                ->required(fn ($get) => $get('action_type') === 'set_password_manual')
                                ->minLength(8),
                        ])
                        ->action(function (User $record, array $data) {
                            $store = Filament::getTenant();
                            $actionType = $data['action_type'];

                            if ($actionType === 'send_password_reset') {
                                try {
                                    $token = Password::broker()->createToken($record);
                                    $record->sendPasswordResetNotification($token);

                                    Notification::make()
                                        ->title("Password reset email sent to {$record->email}")
                                        ->success()
                                        ->send();
                                } catch (\Throwable $e) {
                                    Notification::make()
                                        ->title('Failed to send reset link: '.$e->getMessage())
                                        ->danger()
                                        ->send();
                                }
                            } elseif ($actionType === 'set_password_manual') {
                                $record->update(['password' => Hash::make($data['new_password'])]);

                                if ($store) {
                                    ActivityLog::create([
                                        'tenant_id' => $store->id,
                                        'causer_type' => Auth::user() ? get_class(Auth::user()) : null,
                                        'causer_id' => Auth::id(),
                                        'event' => 'merchant_set_customer_password',
                                        'description' => "Merchant updated password for customer {$record->name}",
                                        'properties' => ['customer_id' => $record->id],
                                    ]);
                                }

                                Notification::make()
                                    ->title("Password successfully updated for {$record->name}")
                                    ->success()
                                    ->send();
                            } elseif ($actionType === 'reset_transaction_pin') {
                                $record->update(['transaction_pin_hash' => null]);

                                Notification::make()
                                    ->title("Transaction PIN cleared for {$record->name}. The customer can now set a new PIN.")
                                    ->success()
                                    ->send();
                            } elseif ($actionType === 'reset_login_pin') {
                                $record->update([
                                    'login_pin_hash' => null,
                                    'login_pin_enabled' => false,
                                ]);

                                Notification::make()
                                    ->title("Login PIN cleared and disabled for {$record->name}.")
                                    ->success()
                                    ->send();
                            }
                        }),

                    Action::make('toggle_ban')
                        ->label(fn (User $record): string => $record->isActive() ? 'Ban Customer' : 'Reactivate Customer')
                        ->icon(fn (User $record): string => $record->isActive() ? 'heroicon-o-no-symbol' : 'heroicon-o-check-circle')
                        ->color(fn (User $record): string => $record->isActive() ? 'danger' : 'success')
                        ->requiresConfirmation()
                        ->modalHeading(fn (User $record): string => $record->isActive() ? "Suspend {$record->name}" : "Reactivate {$record->name}")
                        ->modalDescription(fn (User $record): string => $record->isActive()
                            ? 'Are you sure you want to suspend this customer? They will be immediately logged out and blocked from logging in or making any purchases.'
                            : 'Are you sure you want to restore access for this customer?'
                        )
                        ->action(function (User $record): void {
                            if ($record->isActive()) {
                                $record->ban('Suspended by Store Merchant');

                                Notification::make()
                                    ->title("Customer {$record->name} has been suspended.")
                                    ->danger()
                                    ->send();
                            } else {
                                $record->activate();

                                Notification::make()
                                    ->title("Customer {$record->name} has been reactivated.")
                                    ->success()
                                    ->send();
                            }
                        }),

                    DeleteAction::make(),
                ])
                    ->label('More Actions')
                    ->icon('heroicon-m-ellipsis-vertical')
                    ->color('primary')
                    ->button(),
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
