<?php

use App\Filament\Merchant\Pages\WholesaleRates;
use App\Models\DataPlan;
use App\Models\DataType;
use App\Models\Network;
use App\Models\Owner;
use App\Models\Plan;
use App\Models\PlanDataPrice;
use App\Models\Store;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->owner = Owner::create([
        'name' => 'Wholesale Merchant',
        'email' => 'wholesale@example.com',
        'password' => bcrypt('password'),
    ]);

    $this->store = Store::create([
        'name' => 'Rates Demo Store',
        'owner_id' => $this->owner->id,
    ]);

    $starter = Plan::firstOrCreate(
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

test('merchant can view wholesale rates page with data plans and pricing', function () {
    $starterPlan = Plan::where('slug', 'starter')->first();

    $proPlan = Plan::firstOrCreate(
        ['slug' => 'pro'],
        [
            'name' => 'Pro',
            'price_monthly' => 5000.00,
            'is_active' => true,
        ]
    );

    $network = Network::create([
        'name' => 'MTN',
        'slug' => 'mtn',
        'is_active' => true,
    ]);

    $dataType = DataType::create([
        'name' => 'SME',
        'slug' => 'sme',
        'is_active' => true,
    ]);

    $dataPlan = DataPlan::create([
        'network_id' => $network->id,
        'data_type_id' => $dataType->id,
        'name' => 'MTN 1GB SME',
        'plan_code' => 'mtn-1gb-sme',
        'size_mb' => 1024,
        'validity' => '30 Days',
        'cost_price' => 205.00,
        'selling_price' => 225.00,
        'default_retail_price' => 260.00,
        'is_active' => true,
    ]);

    PlanDataPrice::create([
        'plan_id' => $starterPlan->id,
        'data_plan_id' => $dataPlan->id,
        'wholesale_price' => 225.00,
    ]);

    PlanDataPrice::create([
        'plan_id' => $proPlan->id,
        'data_plan_id' => $dataPlan->id,
        'wholesale_price' => 215.00,
    ]);

    Livewire::test(WholesaleRates::class)
        ->assertSuccessful()
        ->assertSee('Wholesale Rates & Pricing')
        ->assertSee('MTN 1GB SME')
        ->assertSee('225.00')
        ->assertSee('215.00');
});

test('merchant can switch tabs on wholesale rates page', function () {
    Livewire::test(WholesaleRates::class)
        ->assertSet('activeTab', 'data')
        ->call('selectTab', 'airtime')
        ->assertSet('activeTab', 'airtime')
        ->assertSee('Airtime Wholesale Purchase Discounts')
        ->call('selectTab', 'identity')
        ->assertSet('activeTab', 'identity')
        ->assertSee('Identity Verification Services');
});

test('wholesale rates page respects tab query parameter', function () {
    Livewire::withQueryParams(['tab' => 'airtime'])
        ->test(WholesaleRates::class)
        ->assertSet('activeTab', 'airtime')
        ->assertSee('Airtime Wholesale Purchase Discounts');

    Livewire::withQueryParams(['tab' => 'identity'])
        ->test(WholesaleRates::class)
        ->assertSet('activeTab', 'identity')
        ->assertSee('Identity Verification Services');
});
