<?php

use App\Models\Owner;
use App\Models\Store;
use App\Models\User;
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

    $this->user = User::factory()->withTransactionPin('1234')->create([
        'store_id' => $this->store->id,
    ]);

    $this->walletService = app(WalletService::class);
    $this->mainWallet = $this->user->wallet('main') ?? $this->user->wallets()->create([
        'type' => 'main',
        'balance' => 0.00,
    ]);
});

test('guests are redirected to login on identity bvn route', function () {
    $response = $this->get('http://demo.localhost/identity/bvn');
    $response->assertRedirect('http://demo.localhost/login');
});

test('storefront identity bvn page loads with slip formats and wallet balance', function () {
    $this->walletService->credit(
        $this->mainWallet,
        1500.00,
        'deposit',
        'Wallet Funding',
        ['type' => 'test'],
        'REF-TEST-FUND-BVN'
    );

    $this->actingAs($this->user);

    $response = $this->get('http://demo.localhost/identity/bvn');

    $response->assertOk();
    $response->assertInertia(fn ($page) => $page
        ->component('Storefront/Identity/Bvn')
        ->has('slips', 3)
        ->has('wallet_balance')
        ->where('wallet_balance', fn ($val) => (float) $val === 1500.0)
        ->where('slips.0.id', 'basic')
        ->where('slips.1.id', 'advance')
        ->where('slips.2.id', 'plastic')
    );
});

test('verification validation fails when search value is not 11 digits', function () {
    $this->actingAs($this->user);

    $response = $this->postJson('http://demo.localhost/identity/bvn/verify', [
        'search_type' => 'bvn',
        'search_value' => '12345', // only 5 digits
        'slip_type' => 'basic',
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['search_value']);
});

test('verification fails if wallet balance is insufficient', function () {
    $this->actingAs($this->user);

    // User has 0 balance
    $response = $this->postJson('http://demo.localhost/identity/bvn/verify', [
        'search_type' => 'bvn',
        'search_value' => '22345678901',
        'slip_type' => 'basic',
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
        'REF-TEST-PIN-BVN'
    );

    $this->actingAs($this->user);

    $response = $this->postJson('http://demo.localhost/identity/bvn/verify', [
        'search_type' => 'bvn',
        'search_value' => '22345678901',
        'slip_type' => 'basic',
        'pin' => '9999', // Incorrect PIN
    ]);

    $response->assertStatus(422);
    $response->assertJson([
        'success' => false,
        'message' => 'Invalid transaction PIN. Please try again.',
    ]);
});

test('verification succeeds and returns verified identity payload for both bvn and phone modes', function () {
    $this->walletService->credit(
        $this->mainWallet,
        2000.00,
        'deposit',
        'Wallet Funding',
        ['type' => 'test'],
        'REF-TEST-SUCCESS-BVN'
    );

    $this->actingAs($this->user);

    // 1. Test Search by 11-digit BVN
    $bvnResponse = $this->postJson('http://demo.localhost/identity/bvn/verify', [
        'search_type' => 'bvn',
        'search_value' => '22345678901',
        'slip_type' => 'plastic',
        'pin' => '1234',
    ]);

    $bvnResponse->assertOk();
    $bvnResponse->assertJson([
        'success' => true,
        'data' => [
            'bvn' => '22345678901',
            'firstname' => 'FATIMA',
            'surname' => 'GARBA',
            'slip_type' => 'plastic',
        ],
    ]);

    // 2. Test Search by 11-digit Phone Number (Lookup)
    $phoneResponse = $this->postJson('http://demo.localhost/identity/bvn/verify', [
        'search_type' => 'phone',
        'search_value' => '08023456789',
        'slip_type' => 'basic',
        'pin' => '1234',
    ]);

    $phoneResponse->assertOk();
    $phoneResponse->assertJson([
        'success' => true,
        'data' => [
            'phone' => '08023456789',
            'firstname' => 'FATIMA',
            'surname' => 'GARBA',
            'slip_type' => 'basic',
        ],
    ]);
});
