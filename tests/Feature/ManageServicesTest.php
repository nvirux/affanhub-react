<?php

use App\Filament\Merchant\Resources\StoreServiceResource\Pages\ListStoreServices;
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

test('store services resource list page loads cleanly with tabs', function () {
    $service = Service::create([
        'name' => 'Airtime Topup',
        'key' => 'airtime',
        'category' => 'vtu',
        'is_active' => true,
        'sort_order' => 1,
    ]);

    Livewire::test(ListStoreServices::class)
        ->assertSuccessful()
        ->assertSee('Airtime Topup')
        ->assertSee('All Services')
        ->assertSee('VTU Utilities')
        ->assertSee('Identity Services');
});

test('store services resource auto-populates store_services records for tenant', function () {
    Service::create([
        'name' => 'Data Bundle',
        'key' => 'data',
        'category' => 'vtu',
        'is_active' => true,
        'sort_order' => 2,
    ]);

    expect(StoreService::where('store_id', $this->store->id)->count())->toBe(0);

    Livewire::test(ListStoreServices::class)
        ->assertSuccessful();

    expect(StoreService::where('store_id', $this->store->id)->count())->toBe(1);
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

    Livewire::test(ListStoreServices::class)
        ->assertSuccessful()
        ->assertSee('Airtime to Cash')
        ->assertSee('Pro Plan');
});
