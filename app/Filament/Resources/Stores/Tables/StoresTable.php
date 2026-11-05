<?php

namespace App\Filament\Resources\Stores\Tables;

use App\Mail\MerchantDirectMessageMail;
use App\Models\ActivityLog;
use App\Models\Store;
use App\Services\Communication\MerchantOutreachService;
use App\Services\WalletService;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class StoresTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Store')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn (Store $record): string => $record->public_id),

                TextColumn::make('owner.name')
                    ->label('Owner (Merchant)')
                    ->searchable()
                    ->sortable()
                    ->description(fn (Store $record): ?string => $record->owner?->phone ?: $record->owner?->email),

                TextColumn::make('primary_domain')
                    ->label('Primary Domain')
                    ->state(fn (Store $record): string => $record->getPrimaryDomain() ?? 'None')
                    ->badge()
                    ->color('gray')
                    ->icon('heroicon-m-globe-alt')
                    ->copyable()
                    ->copyMessage('Domain copied'),

                TextColumn::make('main_wallet')
                    ->label('Main Capital (₦)')
                    ->badge()
                    ->color('info')
                    ->state(fn (Store $record): string => '₦'.number_format((float) ($record->mainWallet()->balance ?? 0), 2)),

                TextColumn::make('profit_wallet')
                    ->label('Profit Wallet (₦)')
                    ->badge()
                    ->color('success')
                    ->state(fn (Store $record): string => '₦'.number_format((float) ($record->profitWallet()->balance ?? 0), 2)),

                TextColumn::make('customers_balance')
                    ->label('Customers Balance (₦)')
                    ->badge()
                    ->color('warning')
                    ->state(fn (Store $record): string => '₦'.number_format((float) $record->totalCustomersBalance(), 2))
                    ->tooltip('Total balance held in all customer wallets of this store'),

                TextColumn::make('users_count')
                    ->label('Customers')
                    ->counts('users')
                    ->badge()
                    ->color('primary')
                    ->sortable(),

                TextColumn::make('activeSubscription.plan.name')
                    ->label('Plan')
                    ->badge()
                    ->color(fn ($state): string => match ($state) {
                        'Starter' => 'gray',
                        'Pro' => 'primary',
                        'Enterprise' => 'success',
                        default => 'danger',
                    })
                    ->placeholder('No Active Plan'),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'suspended' => 'danger',
                        default => 'warning',
                    })
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Joined')
                    ->dateTime('M j, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'suspended' => 'Suspended',
                        'pending' => 'Pending',
                    ]),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make()
                        ->modalHeading(fn (Store $record): string => "Store Overview: {$record->name}")
                        ->form([
                            TextInput::make('name')
                                ->label('Storefront Name'),
                            TextInput::make('public_id')
                                ->label('Store Public ID'),
                            TextInput::make('status')
                                ->label('Operational Status')
                                ->formatStateUsing(fn (Store $record): string => strtoupper($record->status)),
                            TextInput::make('owner_info')
                                ->label('Store Owner')
                                ->formatStateUsing(fn (Store $record): string => sprintf('%s (%s)', $record->owner?->name ?? 'No Owner', $record->owner?->email ?? 'No Email')),
                            TextInput::make('owner_phone')
                                ->label('Owner Phone')
                                ->formatStateUsing(fn (Store $record): string => $record->owner?->phone ?? 'None'),
                            TextInput::make('primary_domain_view')
                                ->label('Primary Domain')
                                ->formatStateUsing(fn (Store $record): string => $record->getPrimaryDomain() ?? 'None'),
                            TextInput::make('main_capital_view')
                                ->label('Store Wholesale Capital Balance')
                                ->formatStateUsing(fn (Store $record): string => '₦'.number_format((float) ($record->mainWallet()->balance ?? 0), 2)),
                            TextInput::make('profit_view')
                                ->label('Store Profit Wallet Balance')
                                ->formatStateUsing(fn (Store $record): string => '₦'.number_format((float) ($record->profitWallet()->balance ?? 0), 2)),
                            TextInput::make('customers_total_view')
                                ->label('Total Customer Wallets Balance (Holdings)')
                                ->formatStateUsing(fn (Store $record): string => '₦'.number_format((float) $record->totalCustomersBalance(), 2)),
                            TextInput::make('customers_count_view')
                                ->label('Total Customers Registered')
                                ->formatStateUsing(fn (Store $record): string => $record->customersCount().' Customers'),
                            TextInput::make('plan_view')
                                ->label('Subscription Plan')
                                ->formatStateUsing(fn (Store $record): string => $record->activeSubscription?->plan?->name ?? 'No Active Plan'),
                            TextInput::make('app_status_view')
                                ->label('Android App Build Status')
                                ->formatStateUsing(function (Store $record): string {
                                    $app = $record->mobileApp;
                                    if (! $app) {
                                        return 'Not Created';
                                    }

                                    return sprintf('%s (v%s - %s)', $app->app_name, $app->version_name ?? '1.0.0', strtoupper($app->status));
                                }),
                            TextInput::make('created_at')
                                ->label('Store Creation Date'),
                        ]),

                    EditAction::make(),

                    Action::make('whatsapp_owner')
                        ->label('Chat on WhatsApp')
                        ->icon('heroicon-o-chat-bubble-left-right')
                        ->color('success')
                        ->visible(fn (Store $record): bool => ! empty($record->owner?->phone))
                        ->modalHeading(fn (Store $record): string => "WhatsApp Store Owner: {$record->owner?->name}")
                        ->modalDescription('Select an onboarding message template or customize the message before launching WhatsApp.')
                        ->modalSubmitActionLabel('Open WhatsApp Chat ↗')
                        ->form(function (Store $record) {
                            $templates = MerchantOutreachService::getTemplates($record->owner?->name ?? 'Merchant', $record->name);
                            $options = collect($templates)->mapWithKeys(fn ($item, $key) => [$key => $item['label']])->toArray();
                            $options['custom'] = '✏️ Custom Message';

                            return [
                                TextInput::make('phone')
                                    ->label('WhatsApp Phone Number')
                                    ->default(fn () => $record->owner?->phone)
                                    ->required()
                                    ->helperText('Formatted automatically for WhatsApp (+234...).'),

                                Select::make('template')
                                    ->label('Choose Message Template')
                                    ->options($options)
                                    ->default('unfunded_capital')
                                    ->live()
                                    ->afterStateUpdated(function ($state, $set) use ($templates) {
                                        if (isset($templates[$state])) {
                                            $set('message', $templates[$state]['message']);
                                        }
                                    }),

                                Textarea::make('message')
                                    ->label('Message Content')
                                    ->default($templates['unfunded_capital']['message'] ?? '')
                                    ->rows(4)
                                    ->required(),
                            ];
                        })
                        ->action(function (array $data) {
                            $url = MerchantOutreachService::buildWhatsAppUrl($data['phone'], $data['message']);

                            return redirect()->away($url);
                        }),

                    Action::make('send_direct_email')
                        ->label('Send Direct Email')
                        ->icon('heroicon-o-envelope')
                        ->color('info')
                        ->visible(fn (Store $record): bool => ! empty($record->owner?->email))
                        ->modalHeading(fn (Store $record): string => "Send Email to {$record->owner?->name} ({$record->name})")
                        ->modalSubmitActionLabel('Send Email Now')
                        ->form([
                            TextInput::make('subject')
                                ->label('Email Subject')
                                ->default('Important update regarding your store on AffanHub')
                                ->required(),
                            Textarea::make('body')
                                ->label('Email Message Body')
                                ->placeholder('Write your message to the store owner here...')
                                ->rows(6)
                                ->required(),
                        ])
                        ->action(function (Store $record, array $data) {
                            $owner = $record->owner;
                            if (! $owner) {
                                return;
                            }

                            try {
                                Mail::to($owner->email)->send(
                                    new MerchantDirectMessageMail($owner, $data['subject'], $data['body'])
                                );

                                ActivityLog::create([
                                    'tenant_id' => $record->id,
                                    'causer_type' => Auth::user() ? get_class(Auth::user()) : null,
                                    'causer_id' => Auth::id(),
                                    'event' => 'admin_sent_store_email',
                                    'description' => "Admin sent email to store owner {$owner->name} ({$owner->email}): \"{$data['subject']}\"",
                                    'properties' => [
                                        'store_id' => $record->id,
                                        'owner_id' => $owner->id,
                                        'email' => $owner->email,
                                        'subject' => $data['subject'],
                                    ],
                                ]);

                                Notification::make()
                                    ->title("Email sent to {$owner->email} successfully.")
                                    ->success()
                                    ->send();
                            } catch (\Throwable $e) {
                                Notification::make()
                                    ->title('Failed to send email: '.$e->getMessage())
                                    ->danger()
                                    ->send();
                            }
                        }),

                    Action::make('fund_store_wallet')
                        ->label('Fund / Debit Capital')
                        ->icon('heroicon-o-banknotes')
                        ->color('warning')
                        ->form([
                            Select::make('operation')
                                ->label('Action Type')
                                ->options([
                                    'credit' => 'Credit (+ Add Capital)',
                                    'debit' => 'Debit (- Remove Capital)',
                                ])
                                ->default('credit')
                                ->required(),
                            TextInput::make('amount')
                                ->label('Amount (₦)')
                                ->numeric()
                                ->prefix('₦')
                                ->rules(['required', 'numeric', 'gt:0']),
                            TextInput::make('reason')
                                ->label('Reason / Bank Reference')
                                ->required()
                                ->placeholder('e.g., Bank Transfer / Wholesale Top-up Received'),
                        ])
                        ->action(function (Store $record, array $data, WalletService $walletService): void {
                            $mainWallet = $record->mainWallet();
                            $amount = (float) $data['amount'];
                            $reason = $data['reason'];
                            $adminUser = Auth::guard('admin')->user() ?? Auth::user();

                            try {
                                if ($data['operation'] === 'credit') {
                                    $walletService->credit(
                                        $mainWallet,
                                        $amount,
                                        'manual_admin_credit',
                                        $reason,
                                        [
                                            'channel' => 'manual',
                                            'funded_by' => 'Super Admin',
                                            'store_name' => $record->name,
                                        ]
                                    );

                                    ActivityLog::create([
                                        'tenant_id' => $record->id,
                                        'causer_type' => $adminUser ? get_class($adminUser) : null,
                                        'causer_id' => $adminUser?->id,
                                        'event' => 'admin_manual_capital_credit',
                                        'description' => sprintf('Super Admin credited ₦%s wholesale capital to store %s. Reason: %s', number_format($amount, 2), $record->name, $reason),
                                        'properties' => [
                                            'amount' => $amount,
                                            'store_id' => $record->id,
                                            'store_name' => $record->name,
                                            'reason' => $reason,
                                        ],
                                    ]);

                                    Notification::make()
                                        ->title(sprintf('Successfully credited ₦%s capital to %s', number_format($amount, 2), $record->name))
                                        ->success()
                                        ->send();
                                } else {
                                    $walletService->debit(
                                        $mainWallet,
                                        $amount,
                                        'manual_admin_debit',
                                        $reason,
                                        [
                                            'channel' => 'manual',
                                            'funded_by' => 'Super Admin',
                                            'store_name' => $record->name,
                                        ]
                                    );

                                    ActivityLog::create([
                                        'tenant_id' => $record->id,
                                        'causer_type' => $adminUser ? get_class($adminUser) : null,
                                        'causer_id' => $adminUser?->id,
                                        'event' => 'admin_manual_capital_debit',
                                        'description' => sprintf('Super Admin debited ₦%s wholesale capital from store %s. Reason: %s', number_format($amount, 2), $record->name, $reason),
                                        'properties' => [
                                            'amount' => $amount,
                                            'store_id' => $record->id,
                                            'store_name' => $record->name,
                                            'reason' => $reason,
                                        ],
                                    ]);

                                    Notification::make()
                                        ->title(sprintf('Successfully debited ₦%s capital from %s', number_format($amount, 2), $record->name))
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

                    Action::make('impersonate')
                        ->label('Impersonate Store')
                        ->icon('heroicon-o-arrow-right-end-on-rectangle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->modalHeading(fn (Store $record): string => "Impersonate {$record->name}")
                        ->modalDescription("You will be securely logged in to the merchant panel as this store's owner. You can return to Super Admin at any time.")
                        ->form([
                            Toggle::make('anonymous')
                                ->label('Anonymous / Silent Mode')
                                ->helperText('If enabled, this impersonation session will not be logged in activity logs.')
                                ->default(false),
                        ])
                        ->modalSubmitActionLabel('Start Impersonation')
                        ->action(function (Store $record, array $data) {
                            $owner = $record->owner;
                            if (! $owner) {
                                Notification::make()
                                    ->title('Store has no owner assigned.')
                                    ->danger()
                                    ->send();

                                return;
                            }

                            $adminUser = Auth::guard('admin')->user() ?? Auth::user();
                            if (! $adminUser) {
                                Notification::make()
                                    ->title('Unauthorized: Super Admin session required.')
                                    ->danger()
                                    ->send();

                                return;
                            }

                            $isAnonymous = (bool) ($data['anonymous'] ?? false);

                            $token = Str::random(40);
                            Cache::put("impersonate_token_{$token}", [
                                'admin_id' => $adminUser->id,
                                'admin_name' => $adminUser->name ?? 'Super Admin',
                                'owner_id' => $owner->id,
                                'store_id' => $record->id,
                                'store_name' => $record->name,
                                'anonymous' => $isAnonymous,
                                'created_at' => now()->timestamp,
                            ], now()->addSeconds(60));

                            if (! $isAnonymous) {
                                ActivityLog::create([
                                    'tenant_id' => $record->id,
                                    'causer_type' => get_class($adminUser),
                                    'causer_id' => $adminUser->id,
                                    'event' => 'admin_impersonate_store_start',
                                    'description' => sprintf('Super Admin %s started impersonating store %s (Owner: %s)', $adminUser->name ?? 'Admin', $record->name, $owner->name),
                                    'properties' => [
                                        'store_id' => $record->id,
                                        'store_name' => $record->name,
                                        'owner_id' => $owner->id,
                                    ],
                                ]);
                            }

                            $scheme = request()->getScheme();
                            $host = request()->getHost();
                            $cleanHost = preg_replace('/^(admin|merchant)\./', '', $host);
                            $port = request()->getPort();
                            $portStr = ($port && ! in_array($port, [80, 443])) ? ":{$port}" : '';

                            $url = "{$scheme}://merchant.{$cleanHost}{$portStr}/impersonate/consume?token={$token}&tenant={$record->public_id}";

                            return redirect()->away($url);
                        }),

                    Action::make('toggle_status')
                        ->label(fn (Store $record): string => $record->status === 'active' ? 'Suspend Store' : 'Activate Store')
                        ->icon(fn (Store $record): string => $record->status === 'active' ? 'heroicon-o-pause' : 'heroicon-o-play')
                        ->color(fn (Store $record): string => $record->status === 'active' ? 'danger' : 'success')
                        ->requiresConfirmation()
                        ->modalHeading(fn (Store $record): string => ($record->status === 'active' ? 'Suspend ' : 'Activate ').$record->name)
                        ->modalDescription(fn (Store $record): string => $record->status === 'active'
                            ? "Are you sure you want to suspend {$record->name}? Customers will not be able to access this storefront."
                            : "Are you sure you want to activate {$record->name}?")
                        ->action(function (Store $record) {
                            $newStatus = $record->status === 'active' ? 'suspended' : 'active';
                            $record->update(['status' => $newStatus]);
                            Notification::make()
                                ->title("Store status changed to {$newStatus}.")
                                ->success()
                                ->send();
                        }),

                    DeleteAction::make(),
                ])
                    ->label('More Actions')
                    ->icon('heroicon-m-ellipsis-vertical')
                    ->color('gray')
                    ->tooltip('Store Operations'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
