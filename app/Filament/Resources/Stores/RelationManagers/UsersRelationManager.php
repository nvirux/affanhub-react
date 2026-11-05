<?php

namespace App\Filament\Resources\Stores\RelationManagers;

use App\Models\User;
use App\Services\WalletService;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UsersRelationManager extends RelationManager
{
    protected static string $relationship = 'users';

    protected static ?string $title = 'Storefront Customers';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')
                    ->label('Customer Name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->icon('heroicon-m-envelope'),

                TextColumn::make('phone')
                    ->label('Phone')
                    ->searchable()
                    ->copyable(),

                TextColumn::make('wallet_balance')
                    ->label('Wallet Balance (₦)')
                    ->badge()
                    ->color('success')
                    ->state(fn (User $record): string => '₦'.number_format((float) ($record->wallet('main')->balance ?? 0), 2)),

                TextColumn::make('is_active')
                    ->label('Status')
                    ->badge()
                    ->state(fn (User $record): string => $record->isActive() ? 'Active' : 'Banned')
                    ->color(fn (string $state): string => match ($state) {
                        'Active' => 'success',
                        default => 'danger',
                    }),

                TextColumn::make('nin')
                    ->label('NIN')
                    ->placeholder('None')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('bvn')
                    ->label('BVN')
                    ->placeholder('None')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Joined')
                    ->dateTime('M j, Y H:i')
                    ->sortable(),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make()
                        ->form([
                            TextInput::make('name')->label('Customer Name'),
                            TextInput::make('email')->label('Email Address'),
                            TextInput::make('phone')->label('Phone Number'),
                            TextInput::make('bvn')->label('BVN'),
                            TextInput::make('nin')->label('NIN'),
                            TextInput::make('balance_display')
                                ->label('Current Wallet Balance')
                                ->formatStateUsing(fn (User $record): string => '₦'.number_format((float) ($record->wallet('main')->balance ?? 0), 2)),
                            TextInput::make('status_display')
                                ->label('Account Status')
                                ->formatStateUsing(fn (User $record): string => $record->isActive() ? 'Active' : 'Banned / Suspended'),
                            TextInput::make('created_at')->label('Registration Date'),
                        ]),

                    Action::make('adjust_balance')
                        ->label('Fund / Debit Wallet')
                        ->icon('heroicon-o-banknotes')
                        ->color('warning')
                        ->form([
                            Select::make('action')
                                ->label('Action')
                                ->options([
                                    'credit' => 'Credit (+ Add Money)',
                                    'debit' => 'Debit (- Deduct Money)',
                                ])
                                ->default('credit')
                                ->required(),
                            TextInput::make('amount')
                                ->label('Amount (₦)')
                                ->numeric()
                                ->rules(['required', 'numeric', 'gt:0']),
                            TextInput::make('reason')
                                ->label('Reason / Description')
                                ->required()
                                ->placeholder('e.g. Manual refund / Wholesale correction'),
                        ])
                        ->action(function (User $record, array $data, WalletService $walletService) {
                            $wallet = $record->wallet('main');
                            $amount = (float) $data['amount'];
                            $reason = $data['reason'];

                            if ($data['action'] === 'credit') {
                                $walletService->credit($wallet, $amount, 'admin_manual_adjustment', $reason, ['by' => 'Super Admin']);
                                Notification::make()
                                    ->title(sprintf('Credited ₦%s to %s', number_format($amount, 2), $record->name))
                                    ->success()
                                    ->send();
                            } else {
                                $walletService->debit($wallet, $amount, 'admin_manual_adjustment', $reason, ['by' => 'Super Admin']);
                                Notification::make()
                                    ->title(sprintf('Debited ₦%s from %s', number_format($amount, 2), $record->name))
                                    ->warning()
                                    ->send();
                            }
                        }),

                    Action::make('toggle_ban')
                        ->label(fn (User $record): string => $record->isActive() ? 'Ban Customer' : 'Unban Customer')
                        ->icon(fn (User $record): string => $record->isActive() ? 'heroicon-o-no-symbol' : 'heroicon-o-check-circle')
                        ->color(fn (User $record): string => $record->isActive() ? 'danger' : 'success')
                        ->requiresConfirmation()
                        ->action(function (User $record) {
                            if ($record->isActive()) {
                                $record->ban('Banned by Super Admin');
                                Notification::make()->title("Customer {$record->name} has been banned.")->danger()->send();
                            } else {
                                $record->activate();
                                Notification::make()->title("Customer {$record->name} has been unbanned.")->success()->send();
                            }
                        }),
                ])
                    ->label('Actions')
                    ->icon('heroicon-m-ellipsis-vertical')
                    ->color('gray'),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
