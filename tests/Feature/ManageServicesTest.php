<?php

use App\Filament\Merchant\Pages\ManageServices;
use App\Models\Feature;
use App\Models\Owner;
use App\Models\Plan;
use App\Models\PlanFeature;
use App\Models\Service;
use App\Models\Store;
use App\Models\StoreService;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->owner = Owner::create([
        'name' => 'Store Merchant',
        'email' => 'merchant@affanhub.test',
        'password' => bcrypt('password'),
    ]);

    $this->store = Store::create([
        'name' => 'Affan Test Store',
        'public_id' => 'str_wrgtr6vedc',
        'owner_id' => $this->owner->id,
    ]);

    Filament::setCurrentPanel(Filament::getPanel('merchant'));
    $this->actingAs($this->owner, 'owner');
    Filament::auth()->setUser($this->owner);
    Filament::setTenant($this->store);
});

test('manage services page loads cleanly for merchant tenant', function () {
    $service = Service::create([
        'name' => 'Airtime Topup',
        'key' => 'airtime',
        'category' => 'vtu',
        'is_active' => true,
        'sort_order' => 1,
    ]);

    Livewire::test(ManageServices::class)
        ->assertSuccessful()
        ->assertSee('Airtime Topup')
        ->assertSee('Storefront Layout Status');
});

test('manage services resolves upgrade requirements for locked features without SQL cast errors', function () {
    $feature = Feature::create([
        'name' => 'Airtime to Cash',
        'slug' => 'vtu_airtime_cash',
        'type' => 'boolean',
        'default_value' => 'false',
    ]);

    $proPlan = Plan::create([
        'name' => 'Pro',
        'slug' => 'pro',
        'price_monthly' => 5000,
        'price_yearly' => 50000,
        'is_active' => true,
    ]);

    PlanFeature::create([
        'plan_id' => $proPlan->id,
        'feature_id' => $feature->id,
        'value' => 'true',
    ]);

    $service = Service::create([
        'name' => 'Airtime to Cash',
        'key' => 'airtime_cash',
        'category' => 'vtu',
        'feature_id' => $feature->id,
        'is_active' => true,
        'sort_order' => 6,
    ]);

    // Store is on starter plan (default), so airtime to cash is locked and requires Pro Plan
    Livewire::test(ManageServices::class)
        ->assertSuccessful()
        ->assertSee('Airtime to Cash')
        ->assertSee('Pro Plan');
});

test('merchant can toggle enabled state for accessible services', function () {
    $service = Service::create([
        'name' => 'Data Bundle',
        'key' => 'data',
        'category' => 'vtu',
        'is_active' => true,
        'sort_order' => 2,
    ]);

    Livewire::test(ManageServices::class)
        ->call('toggleService', $service->id);

    expect(StoreService::where('store_id', $this->store->id)
        ->where('service_id', $service->id)
        ->value('is_enabled')
    )->toBeFalse();
});

test('merchant cannot toggle locked service and receives warning notification', function () {
    $feature = Feature::create([
        'name' => 'IPE Clearance',
        'slug' => 'id_ipe_clearance',
        'type' => 'boolean',
        'default_value' => 'false',
    ]);

    $service = Service::create([
        'name' => 'IPE Clearance',
        'key' => 'ipe_clearance',
        'category' => 'identity',
        'feature_id' => $feature->id,
        'is_active' => true,
        'sort_order' => 13,
    ]);

    Livewire::test(ManageServices::class)
        ->call('toggleService', $service->id)
        ->assertNotified('Feature Locked');

    // Should not create or set enabled in store_services
    expect(StoreService::where('store_id', $this->store->id)
        ->where('service_id', $service->id)
        ->exists()
    )->toBeFalse();
});
