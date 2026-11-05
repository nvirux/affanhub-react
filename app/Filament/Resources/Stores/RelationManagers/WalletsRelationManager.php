<?php

namespace App\Filament\Resources\Stores\RelationManagers;

use App\Models\Wallet;
use App\Services\WalletService;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class WalletsRelationManager extends RelationManager
{
    protected static string $relationship = 'wallets';

    protected static ?string $title = 'Store Wallets & Capital';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('type')
            ->columns([
                TextColumn::make('type')
                    ->label('Wallet Type')
                    ->badge()
                    ->state(fn (Wallet $record): string => match ($record->type) {
                        'main' => 'Wholesale Capital (Main)',
                        'profit' => 'Store Profit (Earnings)',
                        default => ucfirst($record->type),
                    })
                    ->color(fn (Wallet $record): string => match ($record->type) {
                        'main' => 'info',
                        'profit' => 'success',
                        default => 'gray',
                    }),

                TextColumn::make('balance')
                    ->label('Balance (₦)')
                    ->money('NGN')
                    ->weight('bold')
                    ->color('success')
                    ->sortable(),

                TextColumn::make('currency')
                    ->badge()
                    ->color('gray'),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        default => 'warning',
                    }),

                TextColumn::make('updated_at')
                    ->label('Last Activity')
                    ->dateTime('M j, Y H:i')
                    ->sortable(),
            ])
            ->recordActions([
                ActionGroup::make([
                    Action::make('adjust_funds')
                        ->label('Credit / Debit Wallet')
                        ->icon('heroicon-o-banknotes')
                        ->color('warning')
                        ->form([
                            Select::make('operation')
                                ->label('Action')
                                ->options([
                                    'credit' => 'Credit (+ Add Funds)',
                                    'debit' => 'Debit (- Deduct Funds)',
                                ])
                                ->default('credit')
                                ->required(),
                            TextInput::make('amount')
                                ->label('Amount (₦)')
                                ->numeric()
                                ->rules(['required', 'numeric', 'gt:0']),
                            TextInput::make('reason')
                                ->label('Reason / Audit Memo')
                                ->required()
                                ->placeholder('e.g. Bank transfer wholesale top-up'),
                        ])
                        ->action(function (Wallet $record, array $data, WalletService $walletService) {
                            $amount = (float) $data['amount'];
                            $reason = $data['reason'];

                            if ($data['operation'] === 'credit') {
                                $walletService->credit($record, $amount, 'admin_manual_credit', $reason, ['by' => 'Super Admin']);
                                Notification::make()
                                    ->title(sprintf('Credited ₦%s to %s wallet', number_format($amount, 2), $record->type))
                                    ->success()
                                    ->send();
                            } else {
                                $walletService->debit($record, $amount, 'admin_manual_debit', $reason, ['by' => 'Super Admin']);
                                Notification::make()
                                    ->title(sprintf('Debited ₦%s from %s wallet', number_format($amount, 2), $record->type))
                                    ->warning()
                                    ->send();
                            }
                        }),
                ])
                    ->label('Actions')
                    ->icon('heroicon-m-ellipsis-vertical')
                    ->color('gray'),
            ]);
    }
}
