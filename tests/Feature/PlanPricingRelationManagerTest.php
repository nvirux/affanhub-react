<?php

use App\Filament\Resources\AirtimeDiscounts\Pages\EditAirtimeDiscount;
use App\Filament\Resources\AirtimeDiscounts\RelationManagers\PlanAirtimeDiscountsRelationManager;
use App\Filament\Resources\DataPlans\Pages\EditDataPlan;
use App\Filament\Resources\DataPlans\RelationManagers\PlanPricesRelationManager;
use App\Models\Admin;
use App\Models\AirtimeDiscount;
use App\Models\DataPlan;
use App\Models\DataType;
use App\Models\Network;
use App\Models\Plan;
use App\Models\PlanAirtimeDiscount;
use App\Models\PlanDataPrice;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = Admin::create([
        'name' => 'Super Admin',
        'email' => 'admin@affanhub.test',
        'password' => bcrypt('secret123'),
    ]);

    Filament::setCurrentPanel(Filament::getPanel('admin'));
    $this->actingAs($this->admin, 'admin');

    $this->network = Network::create([
        'name' => 'MTN',
        'slug' => 'mtn',
        'is_active' => true,
    ]);

    $this->dataType = DataType::create([
        'name' => 'SME Data',
        'slug' => 'sme-data',
    ]);

    $this->starterPlan = Plan::create([
        'name' => 'Starter',
        'slug' => 'starter',
        'price_monthly' => 1000,
        'price_yearly' => 10000,
        'is_active' => true,
    ]);

    $this->proPlan = Plan::create([
        'name' => 'Pro',
        'slug' => 'pro',
        'price_monthly' => 5000,
        'price_yearly' => 50000,
        'is_active' => true,
    ]);
});

test('can view edit data plan page and manage plan prices relation manager', function () {
    $dataPlan = DataPlan::create([
        'network_id' => $this->network->id,
        'data_type_id' => $this->dataType->id,
        'name' => '1.0 GB SME',
        'plan_code' => 'MTN_SME_1GB',
        'size_mb' => 1024,
        'validity' => '30 Days',
        'cost_price' => 210.00,
        'selling_price' => 220.00,
        'default_retail_price' => 250.00,
        'is_active' => true,
    ]);

    Livewire::test(EditDataPlan::class, ['record' => $dataPlan->id])
        ->assertSuccessful();

    // Test creating plan price via RelationManager
    Livewire::test(PlanPricesRelationManager::class, [
        'ownerRecord' => $dataPlan,
        'pageClass' => EditDataPlan::class,
    ])
        ->assertSuccessful()
        ->callTableAction('create', data: [
            'plan_id' => $this->starterPlan->id,
            'wholesale_price' => 218.00,
        ])
        ->assertHasNoTableActionErrors();

    expect(PlanDataPrice::where('data_plan_id', $dataPlan->id)->where('plan_id', $this->starterPlan->id)->first())
        ->not->toBeNull()
        ->wholesale_price->toEqual(218.00);

    // Test Auto-Populate All Tiers action
    Livewire::test(PlanPricesRelationManager::class, [
        'ownerRecord' => $dataPlan,
        'pageClass' => EditDataPlan::class,
    ])
        ->callTableAction('populate_all_tiers')
        ->assertHasNoTableActionErrors();

    expect(PlanDataPrice::where('data_plan_id', $dataPlan->id)->count())->toBe(2);
});

test('can view edit airtime discount page and manage plan airtime discounts relation manager', function () {
    $airtime = AirtimeDiscount::create([
        'network_id' => $this->network->id,
        'buy_discount' => 3.50,
        'default_merchant_discount' => 2.00,
        'default_retail_discount' => 1.50,
        'min_amount' => 50,
        'max_amount' => 50000,
        'is_active' => true,
    ]);

    Livewire::test(EditAirtimeDiscount::class, ['record' => $airtime->id])
        ->assertSuccessful();

    // Test creating plan airtime discount via RelationManager
    Livewire::test(PlanAirtimeDiscountsRelationManager::class, [
        'ownerRecord' => $airtime,
        'pageClass' => EditAirtimeDiscount::class,
    ])
        ->assertSuccessful()
        ->callTableAction('create', data: [
            'plan_id' => $this->proPlan->id,
            'wholesale_discount' => 2.80,
            'min_amount' => 50,
            'max_amount' => 50000,
        ])
        ->assertHasNoTableActionErrors();

    expect(PlanAirtimeDiscount::where('network_id', $this->network->id)->where('plan_id', $this->proPlan->id)->first())
        ->not->toBeNull()
        ->wholesale_discount->toEqual(2.80);

    // Test Auto-Populate All Tiers action
    Livewire::test(PlanAirtimeDiscountsRelationManager::class, [
        'ownerRecord' => $airtime,
        'pageClass' => EditAirtimeDiscount::class,
    ])
        ->callTableAction('populate_all_tiers')
        ->assertHasNoTableActionErrors();

    expect(PlanAirtimeDiscount::where('network_id', $this->network->id)->count())->toBe(2);
});
