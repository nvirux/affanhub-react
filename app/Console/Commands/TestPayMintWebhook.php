<?php

namespace App\Console\Commands;

use App\Models\Store;
use App\Models\User;
use App\Models\VirtualAccount;
use App\Models\WalletTransaction;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class TestPayMintWebhook extends Command
{
    protected $signature = 'test:paymint-webhook {--amount=700} {--user=} {--merchant}';
    protected $description = 'Simulate an incoming PayMint bank deposit webhook for Customer or Merchant Store.';

    public function handle(): int
    {
        $this->info('🚀 Starting PayMint Webhook Simulation...');

        $isMerchant = $this->option('merchant');

        if ($isMerchant) {
            $store = Store::first();
            if (! $store) {
                $this->error('No store found for merchant deposit simulation.');
                return 1;
            }

            $holder = $store;
            $wallet = $store->mainWallet();
            $holderName = "Store: {$store->name}";
            $aliasEmail = "t{$store->id}-merchant@va.affanhub.com";
        } else {
            $userId = $this->option('user');
            $user = $userId ? User::find($userId) : User::first();

            if (! $user) {
                $this->error('No user found for customer deposit simulation.');
                return 1;
            }

            $holder = $user;
            $wallet = $user->wallet('main');
            if (! $wallet) {
                $wallet = $user->wallets()->create(['type' => 'main', 'balance' => 0.00]);
            }
            $holderName = "Customer: {$user->name}";
            $storeId = $user->store_id ?? ($user->store->id ?? 1);
            $aliasEmail = "t{$storeId}-u{$user->id}@va.affanhub.com";
        }

        // 2. Find or create virtual account for holder
        $virtualAccount = VirtualAccount::where('holder_type', get_class($holder))
            ->where('holder_id', $holder->id)
            ->first();

        if (! $virtualAccount) {
            $virtualAccount = VirtualAccount::create([
                'holder_type' => get_class($holder),
                'holder_id' => $holder->id,
                'provider' => 'palmpay',
                'bank_name' => 'Palmpay',
                'account_number' => '669' . rand(1000000, 9999999),
                'account_name' => $isMerchant ? $store->name : $user->name,
                'email_alias' => $aliasEmail,
                'reference' => 'pm_va_' . Str::random(8),
            ]);
            $this->info("Created Test Virtual Account for {$holderName}: {$virtualAccount->bank_name} - {$virtualAccount->account_number} ({$aliasEmail})");
        } else {
            $virtualAccount->update(['email_alias' => $aliasEmail]);
            $this->info("Using Existing Virtual Account for {$holderName}: {$virtualAccount->bank_name} - {$virtualAccount->account_number} ({$aliasEmail})");
        }

        $initialBalance = (float) ($wallet->balance ?? 0);
        $amount = (float) $this->option('amount');
        $txRef = 'PMNT|PAY|' . date('YmdHis') . '|' . strtoupper(Str::random(6));
        $evtId = 'PMNT|EVT|' . date('YmdHis') . '|' . strtoupper(Str::random(6));

        $this->line("👤 Target Holder: {$holderName} (#{$holder->id})");
        $this->line("💳 Virtual Account Number: {$virtualAccount->account_number} ({$virtualAccount->bank_name})");
        $this->line("📧 Email Alias: {$aliasEmail}");
        $this->line("💰 Balance Before Deposit: ₦" . number_format($initialBalance, 2));
        $this->line("💵 Simulating Deposit Amount: ₦" . number_format($amount, 2));
        $this->line("📑 PayMint Reference: {$txRef}");

        // 3. Exact PayMint Webhook Payload Structure
        $payload = [
            'id' => $evtId,
            'event' => 'payment.success',
            'created_at' => now()->toIso8601String(),
            'data' => [
                'fee' => 7,
                'amount' => $amount,
                'net_amount' => $amount - 7,
                'currency' => 'NGN',
                'status' => 'successful',
                'channel' => 'virtual_account',
                'provider' => 'palmpay',
                'paid_at' => now()->toIso8601String(),
                'reference' => $txRef,
                'receiver' => [
                    'bank_name' => $virtualAccount->bank_name,
                    'account_number' => $virtualAccount->account_number,
                ],
                'sender' => [
                    'name' => $isMerchant ? 'WHOLESALE CAPITAL' : 'MUSA SADDAM',
                    'bank_name' => 'GTBank',
                    'account_number' => '0123456789',
                ],
                'customer' => [
                    'id' => (string) Str::uuid(),
                    'name' => $isMerchant ? $store->name : $user->name,
                    'email' => $aliasEmail,
                    'phone' => '07039881342',
                ],
                'metadata' => [
                    'narration' => 'BANK DEPOSIT',
                ],
            ],
        ];

        // 4. Pass Request with X-Test-Bypass-Signature header for local CLI testing
        $request = new \Illuminate\Http\Request();
        $request->replace($payload);
        $request->headers->set('X-Test-Bypass-Signature', 'true');

        $controller = app(\App\Http\Controllers\Webhook\PayMintWebhookController::class);
        $response = $controller->handle($request, app(\App\Services\WalletService::class));

        $this->info("📡 Webhook Response Status: " . $response->getStatusCode());
        $this->line("📦 Response Content: " . $response->getContent());

        // 5. Verify Wallet Balance Update
        $wallet->refresh();
        $newBalance = (float) $wallet->balance;

        $this->line("🟢 Balance After Deposit: ₦" . number_format($newBalance, 2));

        if (abs($newBalance - ($initialBalance + $amount)) < 0.01) {
            $this->info("✅ SUCCESS: {$holderName} wallet credited cleanly by ₦" . number_format($amount, 2) . "!");
        } else {
            $this->warn("⚠️ Warning: Balance did not match expected amount.");
        }

        // 6. Test Idempotency Guard (Duplicate Webhook Payload)
        $this->line("\n🔄 Testing Idempotency Protection (Sending duplicate payload)...");
        $dupResponse = $controller->handle($request, app(\App\Services\WalletService::class));
        $this->info("📡 Duplicate Webhook Response Status: " . $dupResponse->getStatusCode());
        $this->line("📦 Duplicate Response Content: " . $dupResponse->getContent());

        if (str_contains($dupResponse->getContent(), 'duplicate') || str_contains($dupResponse->getContent(), 'Already processed')) {
            $this->info("🛡️ IDEMPOTENCY PASSED: System blocked double-deposit!");
        }

        return 0;
    }
}
