<?php

use App\Filament\Merchant\Pages\Billing;
use App\Models\Owner;
use App\Models\Plan;
use App\Models\Store;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Livewire\Livewire;
use PayMint\Laravel\Facades\PayMint;
use PayMint\Resources\Checkout;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->owner = Owner::create([
        'name' => 'Merchant Owner',
        'email' => 'merchant@example.com',
        'password' => bcrypt('password'),
    ]);

    $this->store = Store::create([
        'name' => 'Demo Store',
        'owner_id' => $this->owner->id,
    ]);

    Filament::setCurrentPanel(Filament::getPanel('merchant'));
    $this->actingAs($this->owner, 'owner');
    Filament::setTenant($this->store);
});

test('merchant cannot activate paid plan without sufficient wallet balance', function () {
    $wallet = $this->store->mainWallet();
    $wallet->update(['balance' => 100.00]);

    $proPlan = Plan::create([
        'name' => 'Pro',
        'slug' => 'pro',
        'price_monthly' => 5000.00,
        'price_yearly' => 50000.00,
        'trial_days' => 0,
        'is_active' => true,
    ]);

    Livewire::test(Billing::class)
        ->set('interval', 'month')
        ->call('openPaymentModal', $proPlan->id)
        ->assertSet('showPaymentModal', true)
        ->assertSet('selectedPlanId', $proPlan->id)
        ->call('confirmAndPay');

    // Wallet balance must NOT be debited
    expect($wallet->fresh()->balance)->toEqual(100.00);

    // Subscription must NOT be active
    expect($this->store->subscriptions()->where('plan_id', $proPlan->id)->where('status', 'active')->count())->toBe(0);
});

test('merchant pays from store wallet and activates paid plan when balance is sufficient', function () {
    $wallet = $this->store->mainWallet();
    $wallet->update(['balance' => 10000.00]);

    $proPlan = Plan::create([
        'name' => 'Pro',
        'slug' => 'pro',
        'price_monthly' => 5000.00,
        'price_yearly' => 50000.00,
        'trial_days' => 0,
        'is_active' => true,
    ]);

    Livewire::test(Billing::class)
        ->set('interval', 'month')
        ->call('openPaymentModal', $proPlan->id)
        ->assertSet('showPaymentModal', true)
        ->call('confirmAndPay')
        ->assertSet('showPaymentModal', false);

    // Wallet balance should be debited by 5,000.00
    expect($wallet->fresh()->balance)->toEqual(5000.00);

    // Ledger transaction must exist
    $transaction = $wallet->transactions()->where('type', 'debit')->first();
    expect($transaction)->not->toBeNull();
    expect((float) $transaction->amount)->toBe(5000.00);

    // Subscription must now be active
    $activeSub = $this->store->subscriptions()->where('plan_id', $proPlan->id)->where('status', 'active')->first();
    expect($activeSub)->not->toBeNull();
    expect((float) $activeSub->price)->toBe(5000.00);
});

test('merchant can initialize paymint hosted checkout and gets redirected to authorization url', function () {
    $proPlan = Plan::create([
        'name' => 'Pro',
        'slug' => 'pro',
        'price_monthly' => 5000.00,
        'price_yearly' => 50000.00,
        'trial_days' => 0,
        'is_active' => true,
    ]);

    $checkoutMock = Mockery::mock(Checkout::class);
    $checkoutMock->shouldReceive('initialize')
        ->once()
        ->andReturn([
            'status' => 'success',
            'data' => [
                'authorization_url' => 'https://pay.paymint.africa/SUB_TEST123',
                'reference' => 'SUB_TEST123',
                'amount' => 5000,
            ],
        ]);

    PayMint::shouldReceive('checkout')
        ->once()
        ->andReturn($checkoutMock);

    Livewire::test(Billing::class)
        ->set('interval', 'month')
        ->call('openPaymentModal', $proPlan->id)
        ->assertSet('showPaymentModal', true)
        ->call('payWithCheckout')
        ->assertRedirect('https://pay.paymint.africa/SUB_TEST123');
});

test('billing callback verifies successful paymint payment and activates subscription', function () {
    $proPlan = Plan::create([
        'name' => 'Pro',
        'slug' => 'pro',
        'price_monthly' => 5000.00,
        'price_yearly' => 50000.00,
        'trial_days' => 0,
        'is_active' => true,
    ]);

    $reference = 'SUB_VERIFY123';

    // Store checkout intent in cache
    Cache::put("billing_checkout_{$reference}", [
        'store_id' => $this->store->id,
        'plan_id' => $proPlan->id,
        'interval' => 'month',
        'price' => 5000.00,
    ], now()->addMinutes(30));

    $checkoutMock = Mockery::mock(Checkout::class);
    $checkoutMock->shouldReceive('verify')
        ->with($reference)
        ->once()
        ->andReturn([
            'status' => 'success',
            'data' => [
                'status' => 'successful',
                'reference' => $reference,
                'amount' => 5000,
            ],
        ]);

    PayMint::shouldReceive('checkout')
        ->once()
        ->andReturn($checkoutMock);

    $response = $this->get(route('merchant.billing.callback', [
        'tenant' => $this->store->public_id,
        'reference' => $reference,
        'status' => 'successful',
    ]));

    $response->assertRedirect(route('filament.merchant.pages.billing', ['tenant' => $this->store->public_id]));

    // Subscription must now be active!
    $activeSub = $this->store->subscriptions()->where('plan_id', $proPlan->id)->where('status', 'active')->first();
    expect($activeSub)->not->toBeNull();
    expect((float) $activeSub->price)->toBe(5000.00);
});

test('billing callback rejects unsuccessful payment verification', function () {
    $proPlan = Plan::create([
        'name' => 'Pro',
        'slug' => 'pro',
        'price_monthly' => 5000.00,
        'price_yearly' => 50000.00,
        'trial_days' => 0,
        'is_active' => true,
    ]);

    $reference = 'SUB_FAILED123';

    Cache::put("billing_checkout_{$reference}", [
        'store_id' => $this->store->id,
        'plan_id' => $proPlan->id,
        'interval' => 'month',
        'price' => 5000.00,
    ], now()->addMinutes(30));

    $checkoutMock = Mockery::mock(Checkout::class);
    $checkoutMock->shouldReceive('verify')
        ->with($reference)
        ->once()
        ->andReturn([
            'status' => 'success',
            'data' => [
                'status' => 'failed',
                'reference' => $reference,
                'amount' => 5000,
            ],
        ]);

    PayMint::shouldReceive('checkout')
        ->once()
        ->andReturn($checkoutMock);

    $response = $this->get(route('merchant.billing.callback', [
        'tenant' => $this->store->public_id,
        'reference' => $reference,
        'status' => 'failed',
    ]));

    $response->assertRedirect(route('filament.merchant.pages.billing', ['tenant' => $this->store->public_id]));

    // Subscription must NOT be active
    expect($this->store->subscriptions()->where('plan_id', $proPlan->id)->where('status', 'active')->count())->toBe(0);
});
