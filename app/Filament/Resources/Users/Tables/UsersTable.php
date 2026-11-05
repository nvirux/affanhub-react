<?php

namespace App\Filament\Resources\Users\Tables;

use App\Mail\CustomerDirectMessageMail;
use App\Models\ActivityLog;
use App\Models\User;
use App\Services\Communication\MerchantOutreachService;
use App\Services\WalletService;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->with(['store', 'wallets', 'virtualAccounts'])->withCount(['transactions', 'virtualAccounts']))
            ->columns([
                TextColumn::make('name')
                    ->label('Customer')
                    ->description(function (User $record): ?string {
                        $parts = [];
                        if ($record->phone) {
                            $parts[] = $record->phone;
                        }
                        if ($record->referral_code) {
                            $parts[] = "Ref: {$record->referral_code}";
                        }

                        return implode(' • ', $parts);
                    })
                    ->searchable(['name', 'phone', 'email'])
                    ->sortable()
                    ->weight('semibold'),

                TextColumn::make('email')
                    ->label('Email Address')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Email copied')
                    ->icon('heroicon-m-envelope')
                    ->color('gray'),

                TextColumn::make('store.name')
                    ->label('Store Tenant')
                    ->badge()
                    ->color('amber')
                    ->placeholder('AffanHub Direct')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('wallet_balance')
                    ->label('Wallet (₦)')
                    ->state(function (User $record): string {
                        $bal = $record->wallets->firstWhere('type', 'main')?->balance ?? 0.00;

                        return '₦'.number_format((float) $bal, 2);
                    })
                    ->badge()
                    ->color(function (User $record): string {
                        $bal = (float) ($record->wallets->firstWhere('type', 'main')?->balance ?? 0.00);

                        return $bal > 0 ? 'success' : 'gray';
                    }),

                TextColumn::make('virtual_accounts_display')
                    ->label('Virtual Bank Acct')
                    ->state(function (User $record): string {
                        $primary = $record->virtualAccounts->first();
                        if (! $primary) {
                            return 'None';
                        }
                        $count = $record->virtualAccounts->count();
                        $text = "{$primary->bank_name}: {$primary->account_number}";
                        if ($count > 1) {
                            $text .= ' (+'.($count - 1).')';
                        }

                        return $text;
                    })
                    ->badge()
                    ->color(fn (User $record): string => $record->virtualAccounts->isNotEmpty() ? 'info' : 'gray')
                    ->copyable(fn (User $record): ?string => $record->virtualAccounts->first()?->account_number)
                    ->copyMessage('Virtual account number copied')
                    ->tooltip(function (User $record): ?string {
                        if ($record->virtualAccounts->isEmpty()) {
                            return 'No virtual bank accounts provisioned yet.';
                        }

                        return $record->virtualAccounts->map(fn ($va) => "{$va->bank_name} ({$va->account_number}) - {$va->account_name}")->join(' | ');
                    }),

                TextColumn::make('transactions_count')
                    ->label('Orders')
                    ->counts('transactions')
                    ->badge()
                    ->color('purple')
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

                TextColumn::make('security_status')
                    ->label('Security')
                    ->badge()
                    ->state(function (User $record): string {
                        if ($record->two_factor_confirmed_at) {
                            return '2FA Active';
                        }
                        if ($record->hasLoginPin() || $record->hasTransactionPin()) {
                            return 'PIN Set';
                        }

                        return 'Basic Password';
                    })
                    ->color(fn (string $state): string => match ($state) {
                        '2FA Active' => 'success',
                        'PIN Set' => 'info',
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
                    ->label('Joined Date')
                    ->dateTime('M d, Y H:i')
                    ->description(fn (User $record): string => $record->created_at?->diffForHumans() ?? '')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                TernaryFilter::make('is_active')
                    ->label('Account Status')
                    ->trueLabel('Active Customers Only')
                    ->falseLabel('Suspended Customers Only')
                    ->queries(
                        true: fn (Builder $query) => $query->where('is_active', true),
                        false: fn (Builder $query) => $query->where('is_active', false),
                    ),

                SelectFilter::make('store_id')
                    ->label('Filter by Store')
                    ->relationship('store', 'name')
                    ->searchable()
                    ->preload(),

                TernaryFilter::make('has_bvn')
                    ->label('BVN / KYC Status')
                    ->queries(
                        true: fn (Builder $query) => $query->whereNotNull('bvn')->where('bvn', '!=', ''),
                        false: fn (Builder $query) => $query->whereNull('bvn')->orWhere('bvn', ''),
                    ),

                Filter::make('has_balance')
                    ->label('Funded (> ₦0 Balance)')
                    ->query(fn (Builder $query) => $query->whereHas('wallets', fn ($q) => $q->where('type', 'main')->where('balance', '>', 0))),

                Filter::make('has_virtual_accounts')
                    ->label('Has Dedicated Virtual Bank Account')
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
                        ->modalDescription('Select a customer service template or enter a custom message to contact via WhatsApp.')
                        ->modalSubmitActionLabel('Open WhatsApp Chat ↗')
                        ->form(function (User $record) {
                            $templates = MerchantOutreachService::getCustomerTemplates($record->name, $record->store?->name);
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
                                    ->default(empty($record->bvn) ? 'kyc_verification' : 'support_checkin')
                                    ->live()
                                    ->afterStateUpdated(function ($state, $set) use ($templates) {
                                        if (isset($templates[$state])) {
                                            $set('message', $templates[$state]['message']);
                                        }
                                    }),

                                Textarea::make('message')
                                    ->label('Message Content')
                                    ->default(function () use ($record, $templates) {
                                        $defKey = empty($record->bvn) ? 'kyc_verification' : 'support_checkin';

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
                        ->modalHeading(fn (User $record): string => "Send Email to {$record->name}")
                        ->modalSubmitActionLabel('Send Email Now')
                        ->form([
                            TextInput::make('subject')
                                ->label('Email Subject')
                                ->default('Notice Regarding Your Account')
                                ->required(),
                            Textarea::make('body')
                                ->label('Message Content')
                                ->placeholder('Write your message to the customer here...')
                                ->rows(6)
                                ->required(),
                        ])
                        ->action(function (User $record, array $data) {
                            try {
                                Mail::to($record->email)->send(
                                    new CustomerDirectMessageMail($record, $data['subject'], $data['body'])
                                );

                                ActivityLog::create([
                                    'tenant_id' => $record->store_id,
                                    'causer_type' => Auth::user() ? get_class(Auth::user()) : null,
                                    'causer_id' => Auth::id(),
                                    'event' => 'admin_sent_customer_email',
                                    'description' => "Admin sent direct email to customer {$record->name} ({$record->email}): \"{$data['subject']}\"",
                                    'properties' => [
                                        'user_id' => $record->id,
                                        'email' => $record->email,
                                        'subject' => $data['subject'],
                                    ],
                                ]);

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

                    Action::make('adjust_balance')
                        ->label('Fund / Debit Wallet')
                        ->icon('heroicon-o-banknotes')
                        ->color('warning')
                        ->modalHeading(fn (User $record): string => "Adjust Wallet Balance: {$record->name}")
                        ->modalDescription(function (User $record): string {
                            $bal = $record->wallet('main')->balance ?? 0.00;

                            return 'Current Main Wallet Balance: ₦'.number_format((float) $bal, 2);
                        })
                        ->form([
                            Select::make('action')
                                ->label('Transaction Type')
                                ->options([
                                    'credit' => 'Credit (+ Add Money to Wallet)',
                                    'debit' => 'Debit (- Deduct Money from Wallet)',
                                ])
                                ->default('credit')
                                ->required(),

                            TextInput::make('amount')
                                ->label('Amount (₦)')
                                ->numeric()
                                ->prefix('₦')
                                ->rules(['required', 'numeric', 'gt:0']),

                            TextInput::make('reason')
                                ->label('Reason / Description')
                                ->required()
                                ->placeholder('e.g. Manual top-up / Reversal / System refund'),
                        ])
                        ->action(function (User $record, array $data, WalletService $walletService) {
                            $wallet = $record->wallet('main');
                            $amount = (float) $data['amount'];
                            $reason = $data['reason'];

                            if ($data['action'] === 'credit') {
                                $walletService->credit($wallet, $amount, 'admin_manual_adjustment', $reason, ['by' => 'Super Admin', 'admin_id' => Auth::id()]);

                                ActivityLog::create([
                                    'tenant_id' => $record->store_id,
                                    'causer_type' => Auth::user() ? get_class(Auth::user()) : null,
                                    'causer_id' => Auth::id(),
                                    'event' => 'admin_credited_customer_wallet',
                                    'description' => 'Super Admin credited ₦'.number_format($amount, 2)." to customer {$record->name}. Reason: {$reason}",
                                    'properties' => ['user_id' => $record->id, 'amount' => $amount, 'reason' => $reason],
                                ]);

                                Notification::make()
                                    ->title(sprintf('Credited ₦%s to %s', number_format($amount, 2), $record->name))
                                    ->success()
                                    ->send();
                            } else {
                                $walletService->debit($wallet, $amount, 'admin_manual_adjustment', $reason, ['by' => 'Super Admin', 'admin_id' => Auth::id()]);

                                ActivityLog::create([
                                    'tenant_id' => $record->store_id,
                                    'causer_type' => Auth::user() ? get_class(Auth::user()) : null,
                                    'causer_id' => Auth::id(),
                                    'event' => 'admin_debited_customer_wallet',
                                    'description' => 'Super Admin debited ₦'.number_format($amount, 2)." from customer {$record->name}. Reason: {$reason}",
                                    'properties' => ['user_id' => $record->id, 'amount' => $amount, 'reason' => $reason],
                                ]);

                                Notification::make()
                                    ->title(sprintf('Debited ₦%s from %s', number_format($amount, 2), $record->name))
                                    ->warning()
                                    ->send();
                            }
                        }),

                    Action::make('reset_security')
                        ->label('Security & Credentials')
                        ->icon('heroicon-o-key')
                        ->color('gray')
                        ->modalHeading(fn (User $record): string => "Security Controls: {$record->name}")
                        ->form([
                            Select::make('action_type')
                                ->label('Select Security Action')
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

                                ActivityLog::create([
                                    'tenant_id' => $record->store_id,
                                    'causer_type' => Auth::user() ? get_class(Auth::user()) : null,
                                    'causer_id' => Auth::id(),
                                    'event' => 'admin_set_customer_password',
                                    'description' => "Admin manually updated password for customer {$record->name}",
                                    'properties' => ['user_id' => $record->id],
                                ]);

                                Notification::make()
                                    ->title("Password successfully updated for {$record->name}")
                                    ->success()
                                    ->send();
                            } elseif ($actionType === 'reset_transaction_pin') {
                                $record->update(['transaction_pin_hash' => null]);

                                ActivityLog::create([
                                    'tenant_id' => $record->store_id,
                                    'causer_type' => Auth::user() ? get_class(Auth::user()) : null,
                                    'causer_id' => Auth::id(),
                                    'event' => 'admin_reset_customer_transaction_pin',
                                    'description' => "Admin reset transaction PIN for customer {$record->name}",
                                    'properties' => ['user_id' => $record->id],
                                ]);

                                Notification::make()
                                    ->title("Transaction PIN cleared for {$record->name}. The customer can now set a new PIN.")
                                    ->success()
                                    ->send();
                            } elseif ($actionType === 'reset_login_pin') {
                                $record->update([
                                    'login_pin_hash' => null,
                                    'login_pin_enabled' => false,
                                ]);

                                ActivityLog::create([
                                    'tenant_id' => $record->store_id,
                                    'causer_type' => Auth::user() ? get_class(Auth::user()) : null,
                                    'causer_id' => Auth::id(),
                                    'event' => 'admin_reset_customer_login_pin',
                                    'description' => "Admin reset and disabled login PIN for customer {$record->name}",
                                    'properties' => ['user_id' => $record->id],
                                ]);

                                Notification::make()
                                    ->title("Login PIN cleared and disabled for {$record->name}.")
                                    ->success()
                                    ->send();
                            }
                        }),

                    Action::make('toggle_ban')
                        ->label(fn (User $record): string => $record->isActive() ? 'Ban / Suspend Customer' : 'Reactivate Customer')
                        ->icon(fn (User $record): string => $record->isActive() ? 'heroicon-o-no-symbol' : 'heroicon-o-check-circle')
                        ->color(fn (User $record): string => $record->isActive() ? 'danger' : 'success')
                        ->requiresConfirmation()
                        ->modalHeading(fn (User $record): string => $record->isActive() ? "Suspend {$record->name}" : "Reactivate {$record->name}")
                        ->modalDescription(fn (User $record): string => $record->isActive()
                            ? 'Are you sure you want to suspend this customer? They will be immediately logged out of all devices and prevented from signing in or transacting.'
                            : 'Are you sure you want to reactivate this customer? Access will be immediately restored.'
                        )
                        ->action(function (User $record) {
                            if ($record->isActive()) {
                                $record->ban('Suspended by Super Admin');

                                ActivityLog::create([
                                    'tenant_id' => $record->store_id,
                                    'causer_type' => Auth::user() ? get_class(Auth::user()) : null,
                                    'causer_id' => Auth::id(),
                                    'event' => 'admin_suspended_customer',
                                    'description' => "Admin suspended customer {$record->name} ({$record->email}) and terminated active sessions.",
                                    'properties' => ['user_id' => $record->id],
                                ]);

                                Notification::make()
                                    ->title("Customer {$record->name} has been suspended and sessions terminated.")
                                    ->danger()
                                    ->send();
                            } else {
                                $record->activate();

                                ActivityLog::create([
                                    'tenant_id' => $record->store_id,
                                    'causer_type' => Auth::user() ? get_class(Auth::user()) : null,
                                    'causer_id' => Auth::id(),
                                    'event' => 'admin_activated_customer',
                                    'description' => "Admin reactivated customer {$record->name} ({$record->email}).",
                                    'properties' => ['user_id' => $record->id],
                                ]);

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
            ])
            ->defaultSort('created_at', 'desc');
    }
}
