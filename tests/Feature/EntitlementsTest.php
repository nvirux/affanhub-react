<?php

use App\Models\Admin;
use App\Models\Feature;
use App\Models\Owner;
use App\Models\Plan;
use App\Models\Store;
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

test('it enforces custom email feature and encrypts resend api key', function () {
    $feature = Feature::create([
        'name' => 'Custom Email (Resend)',
        'slug' => 'custom_email',
        'type' => 'boolean',
        'default_value' => 'false',
    ]);

    $proPlan = Plan::create([
        'name' => 'Pro',
        'slug' => 'pro',
        'price_monthly' => 5000.00,
        'price_yearly' => 50000.00,
        'trial_days' => 14,
        'is_active' => true,
    ]);

    $proPlan->planFeatures()->create([
        'feature_id' => $feature->id,
        'value' => 'true',
    ]);

    $owner = Owner::create([
        'name' => 'Test Owner',
        'email' => 'owner@example.com',
        'password' => bcrypt('password'),
    ]);

    $store = Store::create([
        'name' => 'Test Store',
        'owner_id' => $owner->id,
    ]);

    // Starter/default store cannot use custom email
    expect($store->hasFeature('custom_email'))->toBe(false);
    expect($store->hasCustomEmail())->toBe(false);

    // Subscribe to Pro
    $store->subscriptions()->create([
        'plan_id' => $proPlan->id,
        'price' => 5000.00,
        'billing_interval' => 'month',
        'starts_at' => now(),
        'ends_at' => now()->addMonth(),
        'status' => 'active',
    ]);
    $store->refresh();

    expect($store->hasFeature('custom_email'))->toBe(true);
    expect($store->hasCustomEmail())->toBe(false);

    // Configure Resend credentials
    $store->resend_api_key = 're_test_1234567890';
    $store->resend_from_email = 'support@teststore.com';
    $store->resend_from_name = 'Test Store Support';
    $store->save();

    // Re-fetch from database to verify encryption and accessor
    $freshStore = $store->fresh();
    expect($freshStore->resend_api_key)->toBe('re_test_1234567890');
    expect($freshStore->resend_from_email)->toBe('support@teststore.com');
    expect($freshStore->hasCustomEmail())->toBe(true);

    // Verify raw data in JSON column is encrypted (not plain text)
    $rawData = $freshStore->getAttributes()['data'] ?? null;
    expect(json_encode($rawData))->not->toContain('re_test_1234567890');
});
