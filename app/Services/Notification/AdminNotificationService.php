<?php

declare(strict_types=1);

namespace App\Services\Notification;

use App\Mail\AdminSettlementAccountSubmittedMail;
use App\Mail\AdminWithdrawalRequestedMail;
use App\Models\Admin;
use App\Models\SettlementAccount;
use App\Models\Withdrawal;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Mail;
use Throwable;

class AdminNotificationService
{
    /**
     * Resolve the public Super Admin URL for the given path.
     */
    public static function getAdminUrl(string $path = ''): string
    {
        $appUrl = config('app.url', 'http://localhost');
        $scheme = parse_url($appUrl, PHP_URL_SCHEME) ?: 'https';
        $host = parse_url($appUrl, PHP_URL_HOST) ?: 'localhost';
        $port = parse_url($appUrl, PHP_URL_PORT);
        $portStr = ($port && ! in_array($port, [80, 443])) ? ":{$port}" : '';
        $cleanHost = preg_replace('/^(admin|merchant)\./', '', $host);

        $adminBase = "{$scheme}://admin.{$cleanHost}{$portStr}";

        return rtrim($adminBase, '/').'/'.ltrim($path, '/');
    }

    /**
     * Notify Super Admins via email & in-app database notification of a new payout request.
     */
    public function notifyWithdrawalRequested(Withdrawal $withdrawal): void
    {
        $admins = Admin::all();
        $storeName = $withdrawal->store?->name ?? 'Merchant';
        $net = (float) ($withdrawal->net_amount ?? $withdrawal->amount);
        $withdrawalsUrl = self::getAdminUrl('withdrawals');

        // 1. In-App Database Notifications for all Super Admins
        foreach ($admins as $admin) {
            try {
                Notification::make()
                    ->title('💰 New Payout Request')
                    ->body(sprintf(
                        '%s requested ₦%s payout. Net to pay: ₦%s to %s (%s).',
                        $storeName,
                        number_format((float) $withdrawal->amount, 2),
                        number_format($net, 2),
                        $withdrawal->bank_name,
                        $withdrawal->account_number
                    ))
                    ->icon('heroicon-o-banknotes')
                    ->warning()
                    ->actions([
                        Action::make('view')
                            ->label('Review & Pay')
                            ->url($withdrawalsUrl),
                    ])
                    ->sendToDatabase($admin);
            } catch (Throwable $e) {
                report($e);
            }
        }

        // 2. Email Notification to Super Admin(s)
        $emails = $admins->pluck('email')->filter()->unique()->all();
        if (empty($emails) && config('mail.from.address')) {
            $emails = [config('mail.from.address')];
        }

        foreach ($emails as $email) {
            try {
                Mail::to($email)->queue(new AdminWithdrawalRequestedMail($withdrawal));
            } catch (Throwable $e) {
                report($e);
            }
        }
    }

    /**
     * Notify Super Admins via email & in-app database notification when a settlement account is submitted.
     */
    public function notifySettlementAccountSubmitted(SettlementAccount $account): void
    {
        $admins = Admin::all();
        $storeName = $account->store?->name ?? 'Merchant';
        $settlementUrl = self::getAdminUrl('settlement-accounts');

        // 1. In-App Database Notifications for all Super Admins
        foreach ($admins as $admin) {
            try {
                Notification::make()
                    ->title('🏦 Settlement Bank Submitted')
                    ->body(sprintf(
                        '%s submitted bank details for approval: %s - %s (%s).',
                        $storeName,
                        $account->bank_name,
                        $account->account_number,
                        $account->account_name
                    ))
                    ->icon('heroicon-o-building-library')
                    ->info()
                    ->actions([
                        Action::make('view')
                            ->label('Verify Bank')
                            ->url($settlementUrl),
                    ])
                    ->sendToDatabase($admin);
            } catch (Throwable $e) {
                report($e);
            }
        }

        // 2. Email Notification to Super Admin(s)
        $emails = $admins->pluck('email')->filter()->unique()->all();
        if (empty($emails) && config('mail.from.address')) {
            $emails = [config('mail.from.address')];
        }

        foreach ($emails as $email) {
            try {
                Mail::to($email)->queue(new AdminSettlementAccountSubmittedMail($account));
            } catch (Throwable $e) {
                report($e);
            }
        }
    }
}
