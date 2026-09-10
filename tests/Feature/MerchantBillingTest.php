<?php

use App\Filament\Merchant\Pages\Billing;
use App\Models\Owner;
use App\Models\Plan;
use App\Models\Store;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

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
