<?php

use App\Filament\Pages\PlatformVtuProfitSettings;
use App\Models\AirtimeDiscount;
use App\Models\DataPlan;
use App\Models\DataType;
use App\Models\Network;
use App\Models\Owner;
use App\Models\Plan;
use App\Models\PlanDataPrice;
use App\Models\PlatformSetting;
use App\Models\Store;
use App\Models\StoreDataPlan;
use App\Models\Subscription;
use App\Services\Vtu\AirtimeService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->owner = Owner::create([
        'name' => 'Store Owner',
        'email' => 'owner@example.com',
        'password' => bcrypt('password'),
    ]);

    $this->store = Store::create([
        'name' => 'Alpha Telecom',
        'owner_id' => $this->owner->id,
    ]);

    $this->network = Network::create([
        'name' => 'MTN Nigeria',
        'slug' => 'mtn',
        'is_active' => true,
        'sort_order' => 1,
    ]);

    $this->dataType = DataType::create([
        'name' => 'SME',
        'slug' => 'sme',
        'is_active' => true,
    ]);

    $this->dataPlan = DataPlan::create([
        'network_id' => $this->network->id,
        'data_type_id' => $this->dataType->id,
        'name' => 'MTN 1GB SME',
        'size_mb' => 1024,
        'validity' => '30 Days',
        'cost_price' => 245.00,
        'selling_price' => 245.00, // zero profit baseline to test floor
        'default_retail_price' => 290.00,
        'plan_code' => 'MTN_1GB_SME',
        'is_active' => true,
    ]);

    $this->storeDataPlan = StoreDataPlan::create([
        'store_id' => $this->store->id,
        'data_plan_id' => $this->dataPlan->id,
        'selling_price' => 290.00,
        'is_enabled' => true,
    ]);
});

test('StoreDataPlan getWholesaleCost guarantees starter profit floor even if baseline has 0 profit', function () {
    // Default starter margin is 15.00
    // Plan cost is 245.00, selling_price is 245.00
    // getWholesaleCost should clamp to 245 + 15 = 260.00
    $wholesale = $this->storeDataPlan->getWholesaleCost();

    expect($wholesale)->toEqual(260.00);
});

test('StoreDataPlan getWholesaleCost adapts to active subscription tier floor', function () {
    $proPlan = Plan::create([
        'name' => 'Pro Merchant',
        'slug' => 'pro',
        'description' => 'Pro plan',
        'price_monthly' => 5000,
        'is_active' => true,
    ]);

    Subscription::create([
        'store_id' => $this->store->id,
        'plan_id' => $proPlan->id,
        'price' => 5000,
        'billing_interval' => 'monthly',
        'status' => 'active',
        'starts_at' => now(),
    ]);

    // Pro default margin is 10.00 -> 245 + 10 = 255.00
    $wholesale = $this->storeDataPlan->getWholesaleCost();
    expect($wholesale)->toEqual(255.00);

    // If an admin manually set a tier price that is lower than the floor (e.g. 248.00)
    PlanDataPrice::create([
        'plan_id' => $proPlan->id,
        'data_plan_id' => $this->dataPlan->id,
        'wholesale_price' => 248.00,
    ]);

    // The floor guarantees at least 255.00 (245 cost + 10 margin)
    $clampedWholesale = $this->storeDataPlan->getWholesaleCost();
    expect($clampedWholesale)->toEqual(255.00);
});

test('AirtimeService resolvePricing clamps wholesale discount to guarantee platform profit floor', function () {
    // Upstream vendor discount = 3.0%
    AirtimeDiscount::create([
        'network_id' => $this->network->id,
        'buy_discount' => 3.00,
        'default_merchant_discount' => 3.00, // Attempting to give store 3.0% (0 profit)
        'default_retail_discount' => 1.00,
        'min_amount' => 50,
        'max_amount' => 50000,
        'is_active' => true,
    ]);

    $service = app(AirtimeService::class);
    $pricing = $service->resolvePricing($this->store, $this->network, 1000.00);

    // Platform margin setting defaults to 0.5%
    // Maximum store discount allowed = 3.0% - 0.5% = 2.5%
    expect($pricing['wholesale_discount'])->toEqual(2.50);
    // On ₦1,000 face amount:
    // Vendor cost = ₦970.00 (3% discount)
    // Store wholesale cost = ₦975.00 (2.5% discount)
    // Platform profit = ₦5.00 (0.5% guaranteed)
    expect($pricing['vendor_cost'])->toEqual(970.00);
    expect($pricing['store_wholesale_cost'])->toEqual(975.00);
    expect($pricing['platform_profit'])->toEqual(5.00);
});

test('DataPlan custom min_profit overrides global default floor when set', function () {
    // Without custom min_profit, starter floor is 15.00 -> 245 + 15 = 260.00
    expect($this->dataPlan->getMinProfitFloor('starter'))->toEqual(15.00);
    expect($this->storeDataPlan->getWholesaleCost())->toEqual(260.00);

    // Set custom min_profit on the plan (e.g. 50.00)
    $this->dataPlan->update(['min_profit' => 50.00]);
    $this->storeDataPlan->refresh();

    // Now getMinProfitFloor returns 50.00, and wholesale cost clamps to 245 + 50 = 295.00
    expect($this->dataPlan->getMinProfitFloor('starter'))->toEqual(50.00);
    expect($this->storeDataPlan->getWholesaleCost())->toEqual(295.00);
});

test('PlatformVtuProfitSettings Livewire page mounts and saves configured margins', function () {
    PlatformSetting::set('vtu_min_profit_starter', 15.00);
    PlatformSetting::set('vtu_min_profit_pro', 10.00);

    Livewire::test(PlatformVtuProfitSettings::class)
        ->assertSet('starterMargin', 15.0)
        ->assertSet('proMargin', 10.0)
        ->set('starterMargin', 20.0)
        ->set('proMargin', 12.0)
        ->call('save')
        ->assertHasNoErrors();

    expect((float) PlatformSetting::get('vtu_min_profit_starter'))->toEqual(20.00);
    expect((float) PlatformSetting::get('vtu_min_profit_pro'))->toEqual(12.00);
});
