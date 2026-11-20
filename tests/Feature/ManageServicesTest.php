<?php

use App\Filament\Merchant\Pages\StoreLayoutSettings;
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

    expect(StoreService::where('store_id', $this->store->id)->count())->toBe(Service::where('is_active', true)->count());
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

test('store service resolves correct default icons including in prod', function () {
    $cac = Service::firstOrCreate(
        ['key' => 'cac_services'],
        ['name' => 'CAC Registration', 'category' => 'cac', 'is_active' => true, 'sort_order' => 10]
    );

    $nin = Service::firstOrCreate(
        ['key' => 'nin_verification'],
        ['name' => 'NIN Verification', 'category' => 'identity', 'is_active' => true, 'sort_order' => 11]
    );

    $bvn = Service::firstOrCreate(
        ['key' => 'bvn_verification'],
        ['name' => 'BVN Verification', 'category' => 'identity', 'is_active' => true, 'sort_order' => 12]
    );

    $storeCac = StoreService::create([
        'store_id' => $this->store->id,
        'service_id' => $cac->id,
        'icon' => null, // Prod default / null
    ]);

    $storeNin = StoreService::create([
        'store_id' => $this->store->id,
        'service_id' => $nin->id,
        'icon' => null, // Prod default / null
    ]);

    $storeBvn = StoreService::create([
        'store_id' => $this->store->id,
        'service_id' => $bvn->id,
        'icon' => null, // Prod default / null
    ]);

    expect($storeCac->getResolvedIcon())->toBe('cac');
    expect($storeNin->getResolvedIcon())->toBe('nimc');
    expect($storeBvn->getResolvedIcon())->toBe('nibss');
});

test('merchant can choose allowed icons for bvn and nin verification', function () {
    $bvn = Service::firstOrCreate(
        ['key' => 'bvn_verification'],
        ['name' => 'BVN Verification', 'category' => 'identity', 'is_active' => true, 'sort_order' => 12]
    );

    $storeBvn = StoreService::create([
        'store_id' => $this->store->id,
        'service_id' => $bvn->id,
        'is_enabled' => true,
        'icon' => null,
    ]);

    expect($storeBvn->getAvailableIconOptions())->toHaveKeys(['nibss', 'verification', 'default']);
    expect($storeBvn->getAvailableIconOptions())->not->toHaveKey('nimc'); // Cannot choose NIMC for BVN

    $storeBvn->update(['icon' => 'verification']);
    expect($storeBvn->fresh()->getResolvedIcon())->toBe('verification');
});

test('merchant can access and save layout settings', function () {
    Livewire::test(StoreLayoutSettings::class)
        ->assertSuccessful()
        ->assertSee('Storefront Service Layout')
        ->assertSee('4 Cards Per Row')
        ->assertSee('2 Cards Per Row')
        ->set('serviceLayout', 'cards_2')
        ->call('save');

    expect($this->store->fresh()->service_layout)->toBe('cards_2');
});

test('service with direct plan_service relation respects plan access', function () {
    $starter = Plan::create(['name' => 'Starter', 'slug' => 'starter', 'is_active' => true]);
    $pro = Plan::create(['name' => 'Pro', 'slug' => 'pro', 'price_monthly' => 5000, 'is_active' => true]);

    $service = Service::create([
        'name' => 'Custom Pro Service',
        'key' => 'custom_pro_service',
        'category' => 'identity',
        'is_active' => true,
    ]);

    // Attach only to Pro plan via direct plan_service relation
    $service->plans()->attach($pro->id);

    // Starter store should NOT have access
    expect($service->isAccessibleByStore($this->store))->toBeFalse();
    expect($service->getRequiredPlanForStore($this->store))->toBe('Pro Plan');

    // Attach to Starter plan as well
    $service->plans()->attach($starter->id);
    expect($service->isAccessibleByStore($this->store))->toBeTrue();
});
