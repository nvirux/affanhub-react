<?php

use App\Models\Network;
use App\Models\Owner;
use App\Models\Store;
use App\Models\Transaction;
use App\Models\User;
use App\Models\VirtualAccount;
use App\Services\Vtu\AirtimeService;
use App\Services\Vtu\VtuLabService;
use App\Services\Vtu\VtuReconciliationService;
use App\Services\WalletService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;

uses(RefreshDatabase::class);

beforeEach(function () {
    Cache::flush();

    $this->owner = Owner::create([
        'name' => 'Owner Admin',
        'email' => 'owner@example.com',
        'password' => bcrypt('password'),
    ]);

    $this->store = Store::create([
        'name' => 'Fast VTU',
        'owner_id' => $this->owner->id,
    ]);

    $this->user = User::factory()->withTransactionPin('1234')->create([
        'store_id' => $this->store->id,
    ]);

    $this->walletService = app(WalletService::class);
    $this->customerWallet = $this->user->wallet('main');
    $this->storeMainWallet = $this->store->wallet('main');

    $this->walletService->credit($this->customerWallet, 10000.00, 'deposit', 'Initial customer fund');
    $this->walletService->credit($this->storeMainWallet, 20000.00, 'deposit', 'Initial store fund');

    $this->network = Network::create([
        'name' => 'MTN',
        'slug' => 'mtn',
        'is_active' => true,
    ]);
});

test('airtime purchase rejects rapid duplicate submissions via atomic cooldown lock', function () {
    $mockVtuLab = Mockery::mock(VtuLabService::class);
    $mockVtuLab->shouldReceive('purchaseAirtime')
        ->once() // Must only be called ONCE despite 2 rapid attempts!
        ->andReturn([
            'success' => true,
            'pending' => false,
            'status' => 'successful',
            'reference' => 'TEST_REF',
            'message' => 'Airtime delivered successfully.',
            'raw' => ['status' => 'successful'],
        ]);
    $this->app->instance(VtuLabService::class, $mockVtuLab);

    $airtimeService = app(AirtimeService::class);

    // 1. First purchase succeeds
    $result1 = $airtimeService->buyAirtime($this->user, $this->network, 1000.00, '08012345678');
    expect($result1['success'])->toBeTrue();

    // 2. Second rapid purchase for same recipient fails immediately with concurrency cooldown exception
    expect(fn () => $airtimeService->buyAirtime($this->user, $this->network, 1000.00, '08012345678'))
        ->toThrow(Exception::class, 'A transaction for this recipient is already in progress.');
});

test('vtu reconciliation gracefully handles concurrent execution via atomic lock', function () {
    $transaction = Transaction::create([
        'user_id' => $this->user->id,
        'store_id' => $this->store->id,
        'service_type' => 'airtime',
        'amount' => 1000.00,
        'amount_paid' => 1000.00,
        'cost_price' => 970.00,
        'profit' => 30.00,
        'recipient' => '08012345678',
        'status' => 'pending',
        'reference' => 'LOCK_TEST_REF_001',
    ]);

    // Acquire lock manually as if another worker/cron is running
    $manualLock = Cache::lock("reconcile:transaction:{$transaction->id}", 10);
    $manualLock->get();

    $reconciliationService = app(VtuReconciliationService::class);
    $result = $reconciliationService->reconcile($transaction);

    // Reconcile must return early without crashing or throwing
    expect($result['success'])->toBeTrue()
        ->and($result['status'])->toBe('pending')
        ->and($result['message'])->toContain('reconciliation is currently in progress');

    $manualLock->release();
});

test('PayMint webhook handles duplicate or concurrent payloads gracefully with status duplicate', function () {
    $va = VirtualAccount::create([
        'holder_type' => User::class,
        'holder_id' => $this->user->id,
        'provider' => 'paymint',
        'account_number' => '9988776655',
        'account_name' => 'Demo User',
        'bank_name' => 'Wema Bank',
        'reference' => 'VA_REF_001',
    ]);

    $payload = [
        'event' => 'payment.success',
        'data' => [
            'status' => 'successful',
            'amount' => 500.00,
            'reference' => 'PM_DUPLICATE_TX_123',
            'account_number' => '9988776655',
            'sender' => [
                'name' => 'Sender Jane',
                'bank_name' => 'Access Bank',
            ],
        ],
    ];

    // First webhook delivery
    $response1 = $this->withHeaders(['X-Test-Bypass-Signature' => 'true'])
        ->postJson('/webhooks/paymint', $payload);

    $response1->assertStatus(200);
    expect($response1->json('status'))->toBe('success');

    // Second webhook delivery with same reference
    $response2 = $this->withHeaders(['X-Test-Bypass-Signature' => 'true'])
        ->postJson('/webhooks/paymint', $payload);

    $response2->assertStatus(200);
    expect($response2->json('status'))->toBe('duplicate');
});
