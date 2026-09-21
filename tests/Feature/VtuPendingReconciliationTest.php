<?php

use App\Models\Owner;
use App\Models\Store;
use App\Models\Transaction;
use App\Models\User;
use App\Services\Vtu\VtuLabService;
use App\Services\Vtu\VtuReconciliationService;
use App\Services\WalletService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->owner = Owner::create([
        'name' => 'Store Owner',
        'email' => 'storeowner@example.com',
        'password' => bcrypt('password'),
    ]);

    $this->store = Store::create([
        'name' => 'Demo Telecom',
        'owner_id' => $this->owner->id,
    ]);

    $this->user = User::factory()->withTransactionPin('1234')->create([
        'store_id' => $this->store->id,
    ]);

    $this->customerWallet = $this->user->wallet('main');
    $this->storeMainWallet = $this->store->wallet('main');
    $this->storeProfitWallet = $this->store->wallet('profit');
});

test('VtuLabService correctly detects HTTP 202 and ACCEPTED pending responses', function () {
    Http::fake([
        '*/data' => Http::response([
            'success' => true,
            'code' => 'ACCEPTED',
            'message' => 'Data purchase accepted and is processing.',
            'data' => [
                'reference' => 'REQ_DATA_123',
                'status' => 'pending',
            ],
        ], 202),
    ]);

    $service = app(VtuLabService::class);
    $result = $service->purchaseData(14, '08012345678', 'REQ_DATA_123');

    expect($result['success'])->toBeTrue()
        ->and($result['pending'])->toBeTrue()
        ->and($result['status'])->toBe('pending')
        ->and($result['reference'])->toBe('REQ_DATA_123');
});

test('VtuReconciliationService marks successful and sweeps profit on success', function () {
    // Fund customer and store wallets
    $walletService = app(WalletService::class);
    $walletService->credit($this->storeMainWallet, 1000.00, 'deposit', 'Initial deposit');

    $transaction = Transaction::create([
        'user_id' => $this->user->id,
        'store_id' => $this->store->id,
        'service_type' => 'data',
        'amount' => 500.00,
        'amount_paid' => 500.00,
        'cost_price' => 450.00,
        'profit' => 50.00,
        'recipient' => '08012345678',
        'status' => 'pending',
        'reference' => 'TXN_TEST_PENDING_1',
    ]);

    Http::fake([
        '*/transactions/TXN_TEST_PENDING_1' => Http::response([
            'success' => true,
            'data' => [
                'reference' => 'TXN_TEST_PENDING_1',
                'status' => 'successful',
            ],
        ], 200),
    ]);

    $reconciliation = app(VtuReconciliationService::class);
    $res = $reconciliation->reconcile($transaction);

    expect($res['status'])->toBe('successful');

    $transaction->refresh();
    expect($transaction->status)->toBe('successful');

    // Profit should be swept into profit wallet
    $this->storeProfitWallet->refresh();
    expect((float) $this->storeProfitWallet->balance)->toBe(50.00);
});

test('VtuReconciliationService auto-refunds customer and store wholesale on failed status', function () {
    $walletService = app(WalletService::class);
    // Initial balances before refund
    expect((float) $this->customerWallet->balance)->toBe(0.00);
    expect((float) $this->storeMainWallet->balance)->toBe(0.00);

    $transaction = Transaction::create([
        'user_id' => $this->user->id,
        'store_id' => $this->store->id,
        'service_type' => 'data',
        'amount' => 500.00,
        'amount_paid' => 500.00,
        'cost_price' => 450.00,
        'profit' => 50.00,
        'recipient' => '08012345678',
        'status' => 'pending',
        'reference' => 'TXN_TEST_FAILED_1',
    ]);

    Http::fake([
        '*/transactions/TXN_TEST_FAILED_1' => Http::response([
            'success' => true,
            'data' => [
                'reference' => 'TXN_TEST_FAILED_1',
                'status' => 'failed',
            ],
        ], 200),
    ]);

    $reconciliation = app(VtuReconciliationService::class);
    $res = $reconciliation->reconcile($transaction);

    expect($res['status'])->toBe('failed');

    $transaction->refresh();
    expect($transaction->status)->toBe('failed');

    // Customer should have received ₦500 auto-refund
    $this->customerWallet->refresh();
    expect((float) $this->customerWallet->balance)->toBe(500.00);

    // Store should have received ₦450 wholesale refund
    $this->storeMainWallet->refresh();
    expect((float) $this->storeMainWallet->balance)->toBe(450.00);
});

test('vtu:reconcile-pending command reconciles pending orders via CLI', function () {
    $transaction = Transaction::create([
        'user_id' => $this->user->id,
        'store_id' => $this->store->id,
        'service_type' => 'data',
        'amount' => 500.00,
        'amount_paid' => 500.00,
        'cost_price' => 450.00,
        'profit' => 50.00,
        'recipient' => '08012345678',
        'status' => 'pending',
        'reference' => 'TXN_CLI_TEST_1',
        'created_at' => now()->subMinutes(1),
    ]);

    Http::fake([
        '*/transactions/TXN_CLI_TEST_1' => Http::response([
            'success' => true,
            'data' => [
                'reference' => 'TXN_CLI_TEST_1',
                'status' => 'successful',
            ],
        ], 200),
    ]);

    $this->artisan('vtu:reconcile-pending')
        ->expectsOutputToContain('TXN_CLI_TEST_1')
        ->assertSuccessful();

    $transaction->refresh();
    expect($transaction->status)->toBe('successful');
});
