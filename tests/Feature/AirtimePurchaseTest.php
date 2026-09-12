<?php

use App\Models\AirtimeDiscount;
use App\Models\Network;
use App\Models\Owner;
use App\Models\Plan;
use App\Models\PlanAirtimeDiscount;
use App\Models\Store;
use App\Models\Subscription;
use App\Models\Transaction;
use App\Models\User;
use App\Services\Vtu\AirtimeService;
use App\Services\Vtu\VtuLabService;
use App\Services\WalletService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

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

    DB::table('domains')->insert([
        'tenant_id' => $this->store->id,
        'domain' => 'demo.localhost',
        'is_primary' => true,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $this->network = Network::create([
        'name' => 'MTN',
        'slug' => 'mtn',
        'is_active' => true,
        'sort_order' => 1,
    ]);

    $this->airtimeDiscount = AirtimeDiscount::create([
        'network_id' => $this->network->id,
        'buy_discount' => 3.00,
        'default_merchant_discount' => 2.00,
        'default_retail_discount' => 1.50,
        'min_amount' => 50.00,
        'max_amount' => 50000.00,
        'is_active' => true,
    ]);

    $this->user = User::factory()->withTransactionPin('1234')->create([
        'store_id' => $this->store->id,
    ]);
});

test('airtime storefront page loads with configured networks and wallet balance', function () {
    $this->actingAs($this->user);

    $response = $this->get('http://demo.localhost/vtu/airtime');

    $response->assertOk();
    $response->assertInertia(fn ($page) => $page
        ->component('Storefront/Vtu/Airtime')
        ->has('networks')
        ->has('wallet_balance')
        ->where('networks.0.slug', 'mtn')
        ->where('networks.0.discount', 1.5)
    );
});

test('it calculates retail and tier wholesale pricing accurately and debits wallets', function () {
    // 1. Create a Pro subscription plan with custom wholesale discount
    $plan = Plan::create([
        'name' => 'Pro Reseller',
        'slug' => 'pro',
        'price_monthly' => 5000,
        'price_yearly' => 50000,
        'trial_days' => 14,
        'is_active' => true,
    ]);

    PlanAirtimeDiscount::create([
        'plan_id' => $plan->id,
        'network_id' => $this->network->id,
        'wholesale_discount' => 2.80, // 2.8% wholesale discount for Pro tier
    ]);

    Subscription::create([
        'store_id' => $this->store->id,
        'plan_id' => $plan->id,
        'price' => 5000,
        'billing_interval' => 'month',
        'status' => 'active',
        'starts_at' => now(),
    ]);

    // 2. Fund Customer and Store Wallets
    $walletService = app(WalletService::class);
    $customerWallet = $this->user->wallet('main');
    $storeMainWallet = $this->store->mainWallet();
    $storeProfitWallet = $this->store->profitWallet();

    $walletService->credit($customerWallet, 5000, 'deposit', 'Initial deposit');
    $walletService->credit($storeMainWallet, 5000, 'deposit', 'Store wholesale fund');

    // 3. Mock VtuLabService for successful airtime delivery
    $mockVtuLab = Mockery::mock(VtuLabService::class);
    $mockVtuLab->shouldReceive('purchaseAirtime')
        ->once()
        ->andReturn([
            'success' => true,
            'pending' => false,
            'status' => 'successful',
            'message' => 'Airtime delivered successfully.',
            'raw' => ['status' => 'successful'],
        ]);
    $this->app->instance(VtuLabService::class, $mockVtuLab);

    // 4. Execute purchase of ₦1,000 Airtime
    $airtimeService = app(AirtimeService::class);
    $result = $airtimeService->buyAirtime($this->user, $this->network, 1000.00, '08012345678');

    expect($result['success'])->toBeTrue();
    expect($result['status'])->toBe('success');

    // Customer discount: 1.5% => Customer pays ₦985.00
    // Starting balance: 5000 - 985 = 4015.00
    expect((float) $customerWallet->fresh()->balance)->toBe(4015.00);

    // Store wholesale discount: 2.8% => Wholesale cost is ₦972.00
    // Profit margin: 985 - 972 = ₦13.00
    // Starting store balance: 5000 - 972 (wholesale) - 13 (profit swept to profit wallet) = 4015.00
    expect((float) $storeMainWallet->fresh()->balance)->toBe(4015.00);

    // Profit wallet received the ₦13.00 withdrawable profit
    expect((float) $storeProfitWallet->fresh()->balance)->toBe(13.00);

    // Verify Transaction record
    $transaction = Transaction::where('reference', $result['reference'])->first();
    expect($transaction)->not->toBeNull();
    expect((float) $transaction->amount)->toBe(1000.00);
    expect((float) $transaction->discount)->toBe(15.00);
    expect((float) $transaction->amount_paid)->toBe(985.00);
    expect((float) $transaction->cost_price)->toBe(972.00);
    expect((float) $transaction->profit)->toBe(13.00);
    expect(in_array($transaction->status, ['success', 'successful']))->toBeTrue();
});

test('it validates min and max amount limits', function () {
    $this->actingAs($this->user);

    // Minimum is ₦50; trying to recharge ₦20
    $response = $this->postJson('http://demo.localhost/vtu/airtime/purchase', [
        'network_id' => $this->network->id,
        'amount' => 20,
        'phone' => '08012345678',
        'transaction_pin' => '1234',
    ]);

    $response->assertStatus(422);
});

test('it rejects airtime purchase when transaction PIN is incorrect', function () {
    $this->actingAs($this->user);

    $response = $this->postJson('http://demo.localhost/vtu/airtime/purchase', [
        'network_id' => $this->network->id,
        'amount' => 100,
        'phone' => '08012345678',
        'transaction_pin' => '0000',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors('transaction_pin');
});

test('it auto-refunds customer and store when provider call fails', function () {
    $walletService = app(WalletService::class);
    $customerWallet = $this->user->wallet('main');
    $storeMainWallet = $this->store->mainWallet();
    $storeProfitWallet = $this->store->profitWallet();

    $walletService->credit($customerWallet, 2000, 'deposit', 'Initial deposit');
    $walletService->credit($storeMainWallet, 2000, 'deposit', 'Store wholesale fund');

    // Mock VtuLabService failure
    $mockVtuLab = Mockery::mock(VtuLabService::class);
    $mockVtuLab->shouldReceive('purchaseAirtime')
        ->once()
        ->andReturn([
            'success' => false,
            'pending' => false,
            'status' => 'failed',
            'message' => 'Telecom network timeout',
            'raw' => [],
        ]);
    $this->app->instance(VtuLabService::class, $mockVtuLab);

    $airtimeService = app(AirtimeService::class);
    $result = $airtimeService->buyAirtime($this->user, $this->network, 1000.00, '08012345678');

    expect($result['success'])->toBeFalse();
    expect($result['status'])->toBe('failed');

    // Both wallets should be fully restored to 2000.00
    expect((float) $customerWallet->fresh()->balance)->toBe(2000.00);
    expect((float) $storeMainWallet->fresh()->balance)->toBe(2000.00);
    expect((float) $storeProfitWallet->fresh()->balance)->toBe(0.00);

    // Transaction should be recorded as failed
    $transaction = Transaction::where('reference', $result['reference'])->first();
    expect($transaction->status)->toBe('failed');
});
