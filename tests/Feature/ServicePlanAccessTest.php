<?php

use App\Models\Feature;
use App\Models\Owner;
use App\Models\Plan;
use App\Models\PlanFeature;
use App\Models\Service;
use App\Models\Store;
use App\Models\StoreService;
use App\Models\Subscription;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->owner = Owner::create([
        'name' => 'Test Owner',
        'email' => 'owner@example.com',
        'password' => bcrypt('password'),
    ]);
});

test('service with no feature gating is accessible to all stores', function () {
    $store = Store::create([
        'name' => 'Demo Store',
        'owner_id' => $this->owner->id,
        'public_id' => 'demo_str',
    ]);

    $service = Service::create([
        'name' => 'Free Airtime Topup',
        'key' => 'free_airtime',
        'category' => 'vtu',
        'is_active' => true,
        'feature_id' => null,
    ]);

    expect($service->isAccessibleByStore($store))->toBeTrue();
});

test('service gated by feature respects store plan entitlements', function () {
    $starterPlan = Plan::create(['name' => 'Starter', 'slug' => 'starter', 'is_active' => true]);
    $proPlan = Plan::create(['name' => 'Pro', 'slug' => 'pro', 'is_active' => true]);

    $feature = Feature::create([
        'name' => 'Airtime Cash Gating',
        'slug' => 'vtu_airtime_cash_test',
        'type' => 'boolean',
        'default_value' => 'false',
    ]);

    // Give Pro plan access, but not Starter
    PlanFeature::create(['plan_id' => $proPlan->id, 'feature_id' => $feature->id, 'value' => 'true']);
    PlanFeature::create(['plan_id' => $starterPlan->id, 'feature_id' => $feature->id, 'value' => 'false']);

    $service = Service::create([
        'name' => 'Airtime to Cash',
        'key' => 'airtime_cash_test',
        'category' => 'vtu',
        'is_active' => true,
        'feature_id' => $feature->id,
    ]);

    // Store on Starter plan
    $starterStore = Store::create(['name' => 'Starter Store', 'owner_id' => $this->owner->id, 'public_id' => 'starter_str']);
    Subscription::create([
        'store_id' => $starterStore->id,
        'plan_id' => $starterPlan->id,
        'status' => 'active',
        'price' => 0.00,
        'starts_at' => now(),
    ]);

    // Store on Pro plan
    $proStore = Store::create(['name' => 'Pro Store', 'owner_id' => $this->owner->id, 'public_id' => 'pro_str']);
    Subscription::create([
        'store_id' => $proStore->id,
        'plan_id' => $proPlan->id,
        'status' => 'active',
        'price' => 5000.00,
        'starts_at' => now(),
    ]);

    expect($service->isAccessibleByStore($starterStore))->toBeFalse()
        ->and($service->isAccessibleByStore($proStore))->toBeTrue();
});

test('promotional free window unlocks gated service for all stores', function () {
    $starterPlan = Plan::create(['name' => 'Starter', 'slug' => 'starter', 'is_active' => true]);
    $feature = Feature::create(['name' => 'Promo Feature', 'slug' => 'promo_feat', 'type' => 'boolean', 'default_value' => 'false']);

    $store = Store::create(['name' => 'Starter Store', 'owner_id' => $this->owner->id, 'public_id' => 'promo_str']);
    Subscription::create(['store_id' => $store->id, 'plan_id' => $starterPlan->id, 'status' => 'active', 'price' => 0.00, 'starts_at' => now()]);

    $service = Service::create([
        'name' => 'Promo Service',
        'key' => 'promo_service',
        'category' => 'vtu',
        'is_active' => true,
        'feature_id' => $feature->id,
        'promo_until' => now()->addDays(7),
    ]);

    expect($service->isInPromo())->toBeTrue()
        ->and($service->isAccessibleByStore($store))->toBeTrue();

    // Expire promo
    $service->update(['promo_until' => now()->subDay()]);
    expect($service->isInPromo())->toBeFalse()
        ->and($service->isAccessibleByStore($store))->toBeFalse();
});

test('new store trial days grants access during trial period', function () {
    $feature = Feature::create(['name' => 'Trial Feature', 'slug' => 'trial_feat', 'type' => 'boolean', 'default_value' => 'false']);

    $service = Service::create([
        'name' => 'Trial Service',
        'key' => 'trial_service',
        'category' => 'vtu',
        'is_active' => true,
        'feature_id' => $feature->id,
        'trial_days' => 14,
    ]);

    $newStore = Store::create(['name' => 'New Store', 'owner_id' => $this->owner->id, 'public_id' => 'new_str']);
    expect($service->isAccessibleByStore($newStore))->toBeTrue();

    // Old store beyond 14 days
    $oldStore = Store::create(['name' => 'Old Store', 'owner_id' => $this->owner->id, 'public_id' => 'old_str']);
    $oldStore->created_at = now()->subDays(20);
    $oldStore->saveQuietly();

    expect($service->isAccessibleByStore($oldStore))->toBeFalse();
});

test('syncPlansAccess updates plan feature rows properly', function () {
    $plan1 = Plan::create(['name' => 'Starter', 'slug' => 'starter', 'is_active' => true]);
    $plan2 = Plan::create(['name' => 'Pro', 'slug' => 'pro', 'is_active' => true]);
    $plan3 = Plan::create(['name' => 'Enterprise', 'slug' => 'enterprise', 'is_active' => true]);

    $feature = Feature::create(['name' => 'Sync Feature', 'slug' => 'sync_feat', 'type' => 'boolean', 'default_value' => 'false']);

    $service = Service::create([
        'name' => 'Sync Service',
        'key' => 'sync_service',
        'category' => 'vtu',
        'is_active' => true,
        'feature_id' => $feature->id,
    ]);

    // Give access to Pro and Enterprise
    $service->syncPlansAccess([$plan2->id, $plan3->id]);

    expect(PlanFeature::where('plan_id', $plan1->id)->where('feature_id', $feature->id)->value('value'))->toBe('false')
        ->and(PlanFeature::where('plan_id', $plan2->id)->where('feature_id', $feature->id)->value('value'))->toBe('true')
        ->and(PlanFeature::where('plan_id', $plan3->id)->where('feature_id', $feature->id)->value('value'))->toBe('true')
        ->and($service->getAllowedPlanIds())->toEqualCanonicalizing([$plan2->id, $plan3->id])
        ->and($service->getAllowedPlansSummary())->toBe('Pro, Enterprise');
});

test('syncToAllStores creates store service entries across all stores', function () {
    $storeA = Store::create(['name' => 'Store A', 'owner_id' => $this->owner->id, 'public_id' => 'str_a']);
    $storeB = Store::create(['name' => 'Store B', 'owner_id' => $this->owner->id, 'public_id' => 'str_b']);

    $service = Service::create([
        'name' => 'Universal Service',
        'key' => 'universal_service',
        'category' => 'vtu',
        'is_active' => true,
        'feature_id' => null,
    ]);

    $synced = $service->syncToAllStores();
    expect($synced)->toBe(2);

    expect(StoreService::where('service_id', $service->id)->count())->toBe(2);
});
