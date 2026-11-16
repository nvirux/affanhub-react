<?php

use App\Filament\Merchant\Pages\StoreWallet;
use App\Models\Owner;
use App\Models\Plan;
use App\Models\Store;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->owner = Owner::create([
        'name' => 'Wallet Merchant',
        'email' => 'walletmerchant@example.com',
        'password' => bcrypt('password'),
    ]);

    $this->store = Store::create([
        'name' => 'Wallet Store',
        'owner_id' => $this->owner->id,
    ]);

    Plan::firstOrCreate(
        ['slug' => 'starter'],
        [
            'name' => 'Starter',
            'price_monthly' => 0.00,
            'is_active' => true,
        ]
    );

    Filament::setCurrentPanel(Filament::getPanel('merchant'));
    $this->actingAs($this->owner, 'owner');
    Filament::setTenant($this->store);
});

test('merchant can access store wallet page successfully', function () {
    Livewire::test(StoreWallet::class)
        ->assertSuccessful()
        ->assertSee('Wallet');
});

test('merchant wallet defaults customer funding fee to platform settings', function () {
    Livewire::test(StoreWallet::class)
        ->assertSet('customerFeeType', 'percentage')
        ->assertSet('customerFeeAmount', 1.0)
        ->assertSet('customerFeeCap', 1000.0);
});

test('merchant can save customer funding fee settings with 1% and 1000 cap', function () {
    Livewire::test(StoreWallet::class)
        ->set('customerFeeType', 'percentage')
        ->set('customerFeeAmount', 1.0)
        ->set('customerFeeCap', 1000.0)
        ->call('saveFundingFeeSettings')
        ->assertHasNoErrors();

    $this->store->refresh();
    expect($this->store->customer_funding_fee_type)->toBe('percentage');
    expect((float) $this->store->customer_funding_fee_amount)->toBe(1.0);
    expect((float) $this->store->customer_funding_fee_cap)->toBe(1000.0);
});

test('merchant can save flat fee and switch tabs', function () {
    Livewire::test(StoreWallet::class)
        ->set('customerFeeType', 'flat')
        ->set('customerFeeAmount', 75.0)
        ->call('saveFundingFeeSettings')
        ->assertHasNoErrors()
        ->call('setTab', 'policy')
        ->assertSet('activeTab', 'policy')
        ->call('resetToPlatformDefault')
        ->assertSet('customerFeeType', 'percentage')
        ->assertSet('customerFeeAmount', 1.0)
        ->assertSet('customerFeeCap', 1000.0);
});

test('store calculateCustomerDepositFee respects 1% and 1000 cap', function () {
    $this->store->customer_funding_fee_type = 'percentage';
    $this->store->customer_funding_fee_amount = 1.0;
    $this->store->customer_funding_fee_cap = 1000.0;
    $this->store->save();

    // 1% of ₦5,000 = ₦50
    expect($this->store->calculateCustomerDepositFee(5000.0))->toBe(50.0);

    // 1% of ₦200,000 = ₦2,000, capped at ₦1,000
    expect($this->store->calculateCustomerDepositFee(200000.0))->toBe(1000.0);
});
