<?php

use App\Models\Store;
use App\Models\Plan;
use App\Models\Feature;
use App\Models\Owner;
use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('it resolves default global feature values if store has no subscription', function () {
    // 1. Create a global feature
    $feature = Feature::create([
        'name' => 'Staff Limit',
        'slug' => 'staff_limit',
        'type' => 'integer',
        'default_value' => '3',
    ]);

    // 2. Create a store with no subscription
    $owner = Owner::create([
        'name' => 'Test Owner',
        'email' => 'owner@example.com',
        'password' => bcrypt('password'),
    ]);
    
    $store = Store::create([
        'id' => 'test-store',
        'name' => 'Test Store',
        'owner_id' => $owner->id,
    ]);

    // 3. Resolve feature
    expect($store->getFeatureValue('staff_limit'))->toBe(3);
    expect($store->hasEntitlement('staff_limit'))->toBe(true);
});

test('it resolves plan feature values when store has active subscription', function () {
    // 1. Create global feature
    $feature = Feature::create([
        'name' => 'Staff Limit',
        'slug' => 'staff_limit',
        'type' => 'integer',
        'default_value' => '3',
    ]);

    // 2. Create Plan with different limit
    $plan = Plan::create([
        'name' => 'Pro',
        'slug' => 'pro',
        'price_monthly' => 5000.00,
        'price_yearly' => 50000.00,
        'trial_days' => 14,
        'is_active' => true,
    ]);

    $plan->planFeatures()->create([
        'feature_id' => $feature->id,
        'value' => '15',
    ]);

    // 3. Create Store and Subscribe
    $owner = Owner::create([
        'name' => 'Test Owner',
        'email' => 'owner@example.com',
        'password' => bcrypt('password'),
    ]);
    
    $store = Store::create([
        'id' => 'test-store',
        'name' => 'Test Store',
        'owner_id' => $owner->id,
    ]);

    $store->subscriptions()->create([
        'plan_id' => $plan->id,
        'price' => 5000.00,
        'billing_interval' => 'month',
        'starts_at' => now()->subDay(),
        'ends_at' => now()->addMonth(),
        'status' => 'active',
    ]);

    // 4. Resolve feature - should get 15 (plan value) instead of 3 (global default)
    expect($store->getFeatureValue('staff_limit'))->toBe(15);
});

test('it resolves store-specific custom overrides regardless of plan features', function () {
    // 1. Create global feature
    $feature = Feature::create([
        'name' => 'Staff Limit',
        'slug' => 'staff_limit',
        'type' => 'integer',
        'default_value' => '3',
    ]);

    // 2. Create Plan
    $plan = Plan::create([
        'name' => 'Pro',
        'slug' => 'pro',
        'price_monthly' => 5000.00,
        'price_yearly' => 50000.00,
        'trial_days' => 14,
        'is_active' => true,
    ]);

    $plan->planFeatures()->create([
        'feature_id' => $feature->id,
        'value' => '15',
    ]);

    // 3. Create Store, Admin, and Subscribe
    $owner = Owner::create([
        'name' => 'Test Owner',
        'email' => 'owner@example.com',
        'password' => bcrypt('password'),
    ]);
    
    $store = Store::create([
        'id' => 'test-store',
        'name' => 'Test Store',
        'owner_id' => $owner->id,
    ]);

    $store->subscriptions()->create([
        'plan_id' => $plan->id,
        'price' => 5000.00,
        'billing_interval' => 'month',
        'starts_at' => now()->subDay(),
        'ends_at' => now()->addMonth(),
        'status' => 'active',
    ]);

    $admin = Admin::create([
        'name' => 'Superadmin',
        'email' => 'admin@affanhub.com',
        'password' => bcrypt('password'),
        'role' => 'superadmin',
    ]);

    // 4. Create custom override
    $store->featureOverrides()->create([
        'feature_id' => $feature->id,
        'value' => '50',
        'reason' => 'VIP store override request',
        'granted_by' => $admin->id,
    ]);

    // 5. Resolve feature - should get 50 (override value) instead of 15 (plan value)
    expect($store->getFeatureValue('staff_limit'))->toBe(50);
});

test('it falls back to global default if subscription is expired', function () {
    // 1. Create global feature
    $feature = Feature::create([
        'name' => 'Staff Limit',
        'slug' => 'staff_limit',
        'type' => 'integer',
        'default_value' => '3',
    ]);

    // 2. Create Plan
    $plan = Plan::create([
        'name' => 'Pro',
        'slug' => 'pro',
        'price_monthly' => 5000.00,
        'price_yearly' => 50000.00,
        'trial_days' => 14,
        'is_active' => true,
    ]);

    $plan->planFeatures()->create([
        'feature_id' => $feature->id,
        'value' => '15',
    ]);

    // 3. Create Store, Subscribe but set ends_at to past (expired)
    $owner = Owner::create([
        'name' => 'Test Owner',
        'email' => 'owner@example.com',
        'password' => bcrypt('password'),
    ]);
    
    $store = Store::create([
        'id' => 'test-store',
        'name' => 'Test Store',
        'owner_id' => $owner->id,
    ]);

    $store->subscriptions()->create([
        'plan_id' => $plan->id,
        'price' => 5000.00,
        'billing_interval' => 'month',
        'starts_at' => now()->subMonths(2),
        'ends_at' => now()->subMonth(), // Expired 1 month ago
        'status' => 'expired',
    ]);

    // 4. Resolve feature - should get 3 (global default) instead of 15 (plan value) since subscription is expired
    expect($store->getFeatureValue('staff_limit'))->toBe(3);
});
