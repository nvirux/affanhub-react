<?php

use App\Models\Owner;
use App\Models\Slip;
use App\Models\Store;
use App\Models\StoreSlip;
use App\Models\User;
use App\Services\WalletService;
use Database\Seeders\SlipSeeder;
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

    $this->user = User::factory()->withTransactionPin('1234')->create([
        'store_id' => $this->store->id,
    ]);

    $this->walletService = app(WalletService::class);
    $this->mainWallet = $this->user->wallet('main') ?? $this->user->wallets()->create([
        'type' => 'main',
        'balance' => 0.00,
    ]);

    $this->seed(SlipSeeder::class);
    config()->set('services.idcore.api_key', null);
});

test('guests are redirected to login on identity nin route', function () {
    $response = $this->get('http://demo.localhost/identity/nin');
    $response->assertRedirect('http://demo.localhost/login');
});

test('storefront identity nin page loads with slip formats and wallet balance', function () {
    $this->walletService->credit(
        $this->mainWallet,
        1500.00,
        'deposit',
        'Wallet Funding',
        ['type' => 'test'],
        'REF-TEST-FUND'
    );

    $this->actingAs($this->user);

    $response = $this->get('http://demo.localhost/identity/nin');

    $response->assertOk();
    $response->assertInertia(fn ($page) => $page
        ->component('Storefront/Identity/Nin')
        ->has('slips', 4)
        ->has('wallet_balance')
        ->where('wallet_balance', fn ($val) => (float) $val === 1500.0)
        ->where('slips.0.id', 'information')
        ->where('slips.1.id', 'regular')
        ->where('slips.2.id', 'standard')
        ->where('slips.3.id', 'premium')
    );
});

test('verification validation fails when search value is not 11 digits', function () {
    $this->actingAs($this->user);

    $response = $this->postJson('http://demo.localhost/identity/nin/verify', [
        'search_type' => 'nin',
        'search_value' => '12345', // only 5 digits
        'slip_type' => 'standard_nin',
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['search_value']);
});

test('verification fails if wallet balance is insufficient', function () {
    $this->actingAs($this->user);

    // User has 0 balance
    $response = $this->postJson('http://demo.localhost/identity/nin/verify', [
        'search_type' => 'nin',
        'search_value' => '12345678901',
        'slip_type' => 'standard_nin',
        'pin' => '1234',
    ]);

    $response->assertStatus(422);
    $response->assertJson([
        'success' => false,
    ]);
    expect($response->json('message'))->toContain('Insufficient wallet balance');
});

test('verification fails if invalid transaction pin is entered', function () {
    $this->walletService->credit(
        $this->mainWallet,
        1000.00,
        'deposit',
        'Wallet Funding',
        ['type' => 'test'],
        'REF-TEST-PIN'
    );

    $this->actingAs($this->user);

    $response = $this->postJson('http://demo.localhost/identity/nin/verify', [
        'search_type' => 'nin',
        'search_value' => '12345678901',
        'slip_type' => 'standard_nin',
        'pin' => '9999', // Incorrect PIN
    ]);

    $response->assertStatus(422);
    $response->assertJson([
        'success' => false,
        'message' => 'Invalid transaction PIN. Please try again.',
    ]);
});

test('verification succeeds and returns verified identity payload for both nin and phone modes', function () {
    $this->walletService->credit(
        $this->mainWallet,
        2000.00,
        'deposit',
        'Wallet Funding',
        ['type' => 'test'],
        'REF-TEST-SUCCESS'
    );

    $this->actingAs($this->user);

    // 1. Test Search by 11-digit NIN
    $ninResponse = $this->postJson('http://demo.localhost/identity/nin/verify', [
        'search_type' => 'nin',
        'search_value' => '12345678901',
        'slip_type' => 'premium',
        'pin' => '1234',
    ]);

    $ninResponse->assertOk();
    $ninResponse->assertJson([
        'success' => true,
        'data' => [
            'nin' => '12345678901',
            'firstname' => 'MUSA',
            'surname' => 'BELLO',
            'slip_type' => 'premium',
        ],
    ]);

    // 2. Test Search by 11-digit Phone Number (Lookup)
    $phoneResponse = $this->postJson('http://demo.localhost/identity/nin/verify', [
        'search_type' => 'phone',
        'search_value' => '08012345678',
        'slip_type' => 'standard',
        'pin' => '1234',
    ]);

    $phoneResponse->assertOk();
    $phoneResponse->assertJson([
        'success' => true,
        'data' => [
            'phone' => '08012345678',
            'firstname' => 'MUSA',
            'surname' => 'BELLO',
            'slip_type' => 'standard',
        ],
    ]);

    $this->assertDatabaseHas('identity_verifications', [
        'store_id' => $this->store->id,
        'user_id' => $this->user->id,
        'search_type' => 'nin',
        'search_value' => '12345678901',
        'status' => 'successful',
    ]);
});

test('store custom slip selling price overrides default retail price', function () {
    $this->walletService->credit(
        $this->mainWallet,
        2000.00,
        'deposit',
        'Wallet Funding',
        ['type' => 'test'],
        'REF-TEST-OVERRIDE'
    );

    // Find standard slip and create store slip override with custom price ₦450
    $standardSlip = Slip::where('slug', 'standard')->firstOrFail();
    StoreSlip::create([
        'store_id' => $this->store->id,
        'slip_id' => $standardSlip->id,
        'selling_price' => 450.00,
        'is_enabled' => true,
    ]);

    $this->actingAs($this->user);

    // Verify storefront page returns 450.00 for standard slip
    $pageResponse = $this->get('http://demo.localhost/identity/nin');
    $pageResponse->assertOk();
    $pageResponse->assertInertia(fn ($page) => $page
        ->where('slips.2.id', 'standard')
        ->where('slips.2.price', fn ($val) => (float) $val === 450.0)
    );

    // Perform verification with standard slip
    $response = $this->postJson('http://demo.localhost/identity/nin/verify', [
        'search_type' => 'nin',
        'search_value' => '12345678901',
        'slip_type' => 'standard',
        'pin' => '1234',
    ]);

    $response->assertOk();

    // Check customer wallet was debited 450 (2000 - 450 = 1550)
    expect((float) $this->mainWallet->fresh()->balance)->toBe(1550.0);

    // Check identity verification record stores fee_charged = 450 and profit = 450 - merchant_price (150) = 300
    $this->assertDatabaseHas('identity_verifications', [
        'store_id' => $this->store->id,
        'user_id' => $this->user->id,
        'slip_id' => $standardSlip->id,
        'fee_charged' => 450.00,
        'merchant_cost' => 150.00,
        'profit' => 300.00,
    ]);

    // Check full 4-part financial ledger in wallet_transactions:
    // 1. Customer retail debit
    $this->assertDatabaseHas('wallet_transactions', [
        'wallet_id' => $this->mainWallet->id,
        'type' => 'debit',
        'category' => 'nin_verification',
        'amount' => 450.00,
    ]);

    // 2. Store main wholesale debit
    $this->assertDatabaseHas('wallet_transactions', [
        'wallet_id' => $this->store->mainWallet()->id,
        'type' => 'debit',
        'category' => 'wholesale_cost',
        'amount' => 150.00,
    ]);

    // 3. Store profit sweep debit
    $this->assertDatabaseHas('wallet_transactions', [
        'wallet_id' => $this->store->mainWallet()->id,
        'type' => 'debit',
        'category' => 'profit_sweep',
        'amount' => 300.00,
    ]);

    // 4. Store profit wallet credit
    $this->assertDatabaseHas('wallet_transactions', [
        'wallet_id' => $this->store->profitWallet()->id,
        'type' => 'credit',
        'category' => 'earned_profit',
        'amount' => 300.00,
    ]);
});
