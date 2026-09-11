<?php

use App\Filament\Merchant\Pages\Auth\Register;
use App\Filament\Merchant\Pages\OnboardingPlan;
use App\Filament\Merchant\Pages\RegisterStore;
use App\Models\Owner;
use App\Models\Plan;
use App\Models\Store;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('owner can register with full name, email, phone, and terms acceptance', function () {
    Filament::setCurrentPanel(Filament::getPanel('merchant'));

    Livewire::test(Register::class)
        ->fillForm([
            'name' => 'Al-Amin Test Owner',
            'email' => 'alamin@test.com',
            'phone' => '08012345678',
            'password' => 'secret12345',
            'passwordConfirmation' => 'secret12345',
            'terms' => true,
        ])
        ->call('register')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas('owners', [
        'name' => 'Al-Amin Test Owner',
        'email' => 'alamin@test.com',
        'phone' => '08012345678',
    ]);

    $this->assertAuthenticatedAs(Owner::where('email', 'alamin@test.com')->first(), 'owner');
});

test('owner registration fails if terms are not accepted', function () {
    Filament::setCurrentPanel(Filament::getPanel('merchant'));

    Livewire::test(Register::class)
        ->fillForm([
            'name' => 'Al-Amin Test Owner',
            'email' => 'alamin2@test.com',
            'phone' => '08087654321',
            'password' => 'secret12345',
            'passwordConfirmation' => 'secret12345',
            'terms' => false,
        ])
        ->call('register')
        ->assertHasFormErrors(['terms' => 'accepted']);

    $this->assertDatabaseMissing('owners', [
        'email' => 'alamin2@test.com',
    ]);
});

test('owner can complete store setup wizard and create store with branding', function () {
    $owner = Owner::create([
        'name' => 'Merchant Owner',
        'email' => 'owner@example.com',
        'phone' => '08011223344',
        'password' => bcrypt('password'),
    ]);

    $starterPlan = Plan::create([
        'name' => 'Starter',
        'slug' => 'starter',
        'description' => 'Starter plan',
        'price_monthly' => 0.00,
        'price_yearly' => 0.00,
        'trial_days' => 0,
        'is_active' => true,
    ]);

    Filament::setCurrentPanel(Filament::getPanel('merchant'));
    $this->actingAs($owner, 'owner');

    Livewire::test(RegisterStore::class)
        ->fillForm([
            'name' => 'Annur Data Services',
            'subdomain' => 'annur',
            'whatsapp_chat_phone' => '08011223344',
            'description' => 'Wholesale data bundles & VTU services.',
            'primary_color' => '#f59e0b',
        ])
        ->call('register')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas('stores', [
        'name' => 'Annur Data Services',
        'owner_id' => $owner->id,
    ]);

    $store = Store::where('name', 'Annur Data Services')->first();
    expect($store)->not->toBeNull();
    expect($store->whatsapp_chat_phone)->toBe('08011223344');
    expect($store->description)->toBe('Wholesale data bundles & VTU services.');
    expect($store->primary_color)->toBe('#f59e0b');

    $baseDomain = parse_url(config('app.url'), PHP_URL_HOST) ?? 'localhost';
    $this->assertDatabaseHas('domains', [
        'domain' => 'annur.'.$baseDomain,
        'is_primary' => true,
    ]);

    expect($store->activeSubscription)->not->toBeNull();
    expect($store->activeSubscription->plan_id)->toBe($starterPlan->id);
});

test('store registration prevents duplicate subdomains', function () {
    $owner = Owner::create([
        'name' => 'Merchant Owner',
        'email' => 'owner2@example.com',
        'password' => bcrypt('password'),
    ]);

    $baseDomain = parse_url(config('app.url'), PHP_URL_HOST) ?? 'localhost';

    $existingStore = Store::create([
        'name' => 'Existing Store',
        'owner_id' => $owner->id,
    ]);

    $existingStore->domains()->create([
        'domain' => 'existing.'.$baseDomain,
        'is_primary' => true,
    ]);

    Filament::setCurrentPanel(Filament::getPanel('merchant'));
    $this->actingAs($owner, 'owner');

    Livewire::test(RegisterStore::class)
        ->fillForm([
            'name' => 'Another Store',
            'subdomain' => 'existing',
        ])
        ->call('register')
        ->assertHasFormErrors(['subdomain']);
});

test('owner can view onboarding plan selection page and continue with starter plan', function () {
    $owner = Owner::create([
        'name' => 'Merchant Owner',
        'email' => 'owner3@example.com',
        'password' => bcrypt('password'),
    ]);

    $store = Store::create([
        'name' => 'Test Store',
        'owner_id' => $owner->id,
    ]);

    Plan::create([
        'name' => 'Starter',
        'slug' => 'starter',
        'description' => 'Starter plan',
        'price_monthly' => 0.00,
        'price_yearly' => 0.00,
        'trial_days' => 0,
        'is_active' => true,
    ]);

    Plan::create([
        'name' => 'Pro',
        'slug' => 'pro',
        'description' => 'Pro plan',
        'price_monthly' => 5000.00,
        'price_yearly' => 50000.00,
        'trial_days' => 0,
        'is_active' => true,
    ]);

    Filament::setCurrentPanel(Filament::getPanel('merchant'));
    $this->actingAs($owner, 'owner');
    Filament::setTenant($store);

    Livewire::test(OnboardingPlan::class)
        ->assertSuccessful()
        ->assertSee('Starter')
        ->assertSee('Pro')
        ->call('continueStarter')
        ->assertRedirect(route('filament.merchant.pages.dashboard', ['tenant' => $store->public_id]));
});

test('merchant can trigger enterprise inquiry via whatsapp', function () {
    $owner = Owner::create([
        'name' => 'Enterprise Buyer',
        'email' => 'buyer@example.com',
        'password' => bcrypt('password'),
    ]);

    $store = Store::create([
        'name' => 'Apex Mega Hub',
        'owner_id' => $owner->id,
    ]);

    Filament::setCurrentPanel(Filament::getPanel('merchant'));
    $this->actingAs($owner, 'owner');
    Filament::setTenant($store);

    Livewire::test(OnboardingPlan::class)
        ->call('contactEnterprise')
        ->assertNotified();
});

test('owner can create multiple stores up to their max_stores limit and each gets starter plan', function () {
    $owner = Owner::create([
        'name' => 'Multi Store Owner',
        'email' => 'multi@example.com',
        'password' => bcrypt('password'),
        'max_stores' => 3,
    ]);

    $starterPlan = Plan::create([
        'name' => 'Starter',
        'slug' => 'starter',
        'description' => 'Starter plan',
        'price_monthly' => 0.00,
        'price_yearly' => 0.00,
        'trial_days' => 0,
        'is_active' => true,
    ]);

    Filament::setCurrentPanel(Filament::getPanel('merchant'));
    $this->actingAs($owner, 'owner');

    // Store 1
    Livewire::test(RegisterStore::class)
        ->fillForm(['name' => 'Store One', 'subdomain' => 'store-one'])
        ->call('register')
        ->assertHasNoFormErrors();

    // Store 2
    Livewire::test(RegisterStore::class)
        ->fillForm(['name' => 'Store Two', 'subdomain' => 'store-two'])
        ->call('register')
        ->assertHasNoFormErrors();

    expect($owner->ownedStores()->count())->toBe(2);

    $storeTwo = Store::where('name', 'Store Two')->first();
    expect($storeTwo->activeSubscription)->not->toBeNull();
    expect($storeTwo->activeSubscription->plan_id)->toBe($starterPlan->id);
});

test('owner cannot exceed their max_stores limit', function () {
    $owner = Owner::create([
        'name' => 'Capped Owner',
        'email' => 'capped@example.com',
        'password' => bcrypt('password'),
        'max_stores' => 2,
    ]);

    Plan::create([
        'name' => 'Starter',
        'slug' => 'starter',
        'description' => 'Starter plan',
        'price_monthly' => 0.00,
        'price_yearly' => 0.00,
        'trial_days' => 0,
        'is_active' => true,
    ]);

    // Create 2 existing stores
    $store1 = Store::create(['name' => 'Existing 1', 'owner_id' => $owner->id]);
    $store2 = Store::create(['name' => 'Existing 2', 'owner_id' => $owner->id]);
    $owner->stores()->attach([$store1->id, $store2->id], ['role' => 'owner']);

    expect($owner->canCreateMoreStores())->toBeFalse();

    Filament::setCurrentPanel(Filament::getPanel('merchant'));
    $this->actingAs($owner, 'owner');

    // Visiting /new detects limit, shows notification and redirects to first store dashboard
    Livewire::test(RegisterStore::class)
        ->assertNotified()
        ->assertRedirect(route('filament.merchant.pages.dashboard', ['tenant' => $store1->public_id]));
});

test('being a staff member in other stores does not count against owner store limit', function () {
    $owner = Owner::create([
        'name' => 'Staff and Owner',
        'email' => 'staffowner@example.com',
        'password' => bcrypt('password'),
        'max_stores' => 1,
    ]);

    $otherOwner = Owner::create([
        'name' => 'Another Merchant',
        'email' => 'another@example.com',
        'password' => bcrypt('password'),
    ]);

    // Other owner has 3 stores where our user is added as staff
    for ($i = 1; $i <= 3; $i++) {
        $store = Store::create([
            'name' => "Other Store {$i}",
            'owner_id' => $otherOwner->id,
        ]);
        $owner->stores()->attach($store->id, ['role' => 'staff']);
    }

    // Even though $owner has access to 3 stores as staff, their ownedStores is 0!
    expect($owner->stores()->count())->toBe(3);
    expect($owner->ownedStores()->count())->toBe(0);
    expect($owner->canCreateMoreStores())->toBeTrue();
});
