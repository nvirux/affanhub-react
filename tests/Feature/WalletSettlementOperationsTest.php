<?php

use App\Models\Owner;
use App\Models\Store;
use App\Models\User;
use App\Services\WalletService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->owner = Owner::create([
        'name' => 'Store Owner',
        'email' => 'owner_wallet@example.com',
        'password' => bcrypt('password'),
    ]);

    $this->store = Store::create([
        'name' => 'Wallet Test Telecom',
        'owner_id' => $this->owner->id,
    ]);

    $this->user = User::factory()->create([
        'store_id' => $this->store->id,
    ]);

    $this->walletService = app(WalletService::class);
    $this->customerWallet = $this->user->wallet('main');
    $this->storeMainWallet = $this->store->mainWallet();
    $this->storeProfitWallet = $this->store->profitWallet();
});

test('sweepWholesaleProfit debits store main and credits profit wallet atomically', function () {
    $this->walletService->credit($this->storeMainWallet, 5000.00, 'test_deposit', 'Initial Float');

    $result = $this->walletService->sweepWholesaleProfit(
        storeMainWallet: $this->storeMainWallet,
        storeProfitWallet: $this->storeProfitWallet,
        profitMargin: 250.00,
        reference: 'SWEEP_TEST_01',
        serviceName: 'Data Plan 1GB',
        customerId: $this->user->id
    );

    expect($result['swept'])->toBeTrue();
    expect($result['sweep_tx'])->not->toBeNull();
    expect($result['profit_tx'])->not->toBeNull();

    $this->storeMainWallet->refresh();
    $this->storeProfitWallet->refresh();

    // 5000 - 250 = 4750
    expect((float) $this->storeMainWallet->balance)->toBe(4750.00);
    expect((float) $this->storeProfitWallet->balance)->toBe(250.00);
});

test('sweepWholesaleProfit returns false when profit margin is zero or negative', function () {
    $result = $this->walletService->sweepWholesaleProfit(
        storeMainWallet: $this->storeMainWallet,
        storeProfitWallet: $this->storeProfitWallet,
        profitMargin: 0.00,
        reference: 'SWEEP_ZERO',
        serviceName: 'Zero Profit Service'
    );

    expect($result['swept'])->toBeFalse();
    expect($result['sweep_tx'])->toBeNull();
    expect($result['profit_tx'])->toBeNull();
});

test('settleManualServiceProfit transfers from main to profit wallet when main has funds', function () {
    $this->walletService->credit($this->storeMainWallet, 10000.00, 'customer_deposit', 'Customer Funded Account');

    $result = $this->walletService->settleManualServiceProfit(
        store: $this->store,
        amount: 4000.00,
        reference: 'MANUAL_SETTLE_01',
        serviceName: 'Name Reservation',
        serviceCategoryKey: 'cac',
        customerId: $this->user->id
    );

    expect($result['settled'])->toBeTrue();
    expect($result['reason'])->toBeNull();

    $this->storeMainWallet->refresh();
    $this->storeProfitWallet->refresh();

    // 10000 - 4000 = 6000
    expect((float) $this->storeMainWallet->balance)->toBe(6000.00);
    expect((float) $this->storeProfitWallet->balance)->toBe(4000.00);
});

test('settleManualServiceProfit safely skips transfer when main wallet balance is insufficient', function () {
    // Store main wallet has 0.00
    expect((float) $this->storeMainWallet->balance)->toBe(0.00);

    $result = $this->walletService->settleManualServiceProfit(
        store: $this->store,
        amount: 4000.00,
        reference: 'MANUAL_ZERO_01',
        serviceName: 'Name Reservation',
        serviceCategoryKey: 'cac',
        customerId: $this->user->id
    );

    expect($result['settled'])->toBeFalse();
    expect($result['reason'])->toBe('insufficient_main_wallet_balance');

    $this->storeProfitWallet->refresh();
    expect((float) $this->storeProfitWallet->balance)->toBe(0.00);
});

test('reverseInstantService refunds customer and store main atomically', function () {
    $this->customerWallet->update(['balance' => 100.00]);
    $this->storeMainWallet->update(['balance' => 500.00]);

    $this->walletService->reverseInstantService(
        customerWallet: $this->customerWallet,
        storeMainWallet: $this->storeMainWallet,
        retailAmount: 1000.00,
        wholesaleAmount: 950.00,
        reference: 'REV_TEST_01',
        serviceDescription: 'Airtime Recharge',
        failureReason: 'Provider timeout'
    );

    $this->customerWallet->refresh();
    $this->storeMainWallet->refresh();

    // Customer: 100 + 1000 = 1100
    expect((float) $this->customerWallet->balance)->toBe(1100.00);
    // Store Main: 500 + 950 = 1450
    expect((float) $this->storeMainWallet->balance)->toBe(1450.00);
});
