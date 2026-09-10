<?php

namespace App\Filament\Resources\Stores\Tables;

use App\Models\ActivityLog;
use App\Models\Store;
use App\Services\WalletService;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class StoresTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('public_id')
                    ->label('Public ID')
                    ->searchable()
                    ->copyable()
                    ->sortable(),

                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('owner.name')
                    ->label('Owner (Merchant)')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('main_wallet')
                    ->label('Main Capital (₦)')
                    ->badge()
                    ->color('info')
                    ->state(fn (Store $record) => '₦'.number_format((float) ($record->mainWallet()->balance ?? 0), 2)),

                TextColumn::make('profit_wallet')
                    ->label('Profit Wallet (₦)')
                    ->badge()
                    ->color('success')
                    ->state(fn (Store $record) => '₦'.number_format((float) ($record->profitWallet()->balance ?? 0), 2)),

                TextColumn::make('activeSubscription.plan.name')
                    ->label('Active Plan')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
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
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'suspended' => 'Suspended',
                        'pending' => 'Pending',
                    ]),
            ])
            ->recordActions([
                Action::make('fund_store_wallet')
                    ->label('Fund Store Capital')
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
                    ->label('Impersonate')
                    ->icon('heroicon-o-arrow-right-end-on-rectangle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading(fn (Store $record) => "Impersonate {$record->name}")
                    ->modalDescription("You will be securely logged in to the merchant panel as this store's owner. You can return to Super Admin at any time.")
                    ->modalSubmitActionLabel('Start Impersonation')
                    ->action(function (Store $record) {
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

                        // Generate 60-second secure one-time token
                        $token = Str::random(40);
                        Cache::put("impersonate_token_{$token}", [
                            'admin_id' => $adminUser->id,
                            'admin_name' => $adminUser->name ?? 'Super Admin',
                            'owner_id' => $owner->id,
                            'store_id' => $record->id,
                            'store_name' => $record->name,
                            'created_at' => now()->timestamp,
                        ], now()->addSeconds(60));

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

                        $scheme = request()->getScheme();
                        $host = request()->getHost();
                        $cleanHost = preg_replace('/^(admin|merchant)\./', '', $host);
                        $port = request()->getPort();
                        $portStr = ($port && ! in_array($port, [80, 443])) ? ":{$port}" : '';

                        $url = "{$scheme}://merchant.{$cleanHost}{$portStr}/impersonate/consume?token={$token}&tenant={$record->public_id}";

                        return redirect()->away($url);
                    }),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
