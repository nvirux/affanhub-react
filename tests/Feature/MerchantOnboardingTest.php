<?php

use App\Filament\Merchant\Pages\Auth\EditProfile;
use App\Filament\Merchant\Pages\Auth\Register;
use App\Filament\Merchant\Pages\RegisterStore;
use App\Models\Owner;
use App\Models\Plan;
use App\Models\Store;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('owner can register with full name, email, phone, and terms acceptance', function () {
    Filament::setCurrentPanel(Filament::getPanel('merchant'));

    Livewire::test(Register::class)
        ->fillForm([
            'name' => 'Al-Amin Test Owner',
            'email' => 'alamin@gmail.com',
            'phone' => '08012345678',
            'password' => 'secret12345',
            'passwordConfirmation' => 'secret12345',
            'terms' => true,
        ])
        ->call('register')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas('owners', [
        'name' => 'Al-Amin Test Owner',
        'email' => 'alamin@gmail.com',
        'phone' => '08012345678',
    ]);

    $this->assertAuthenticatedAs(Owner::where('email', 'alamin@gmail.com')->first(), 'owner');
});

test('owner registration fails if terms are not accepted', function () {
    Filament::setCurrentPanel(Filament::getPanel('merchant'));

    Livewire::test(Register::class)
        ->fillForm([
            'name' => 'Al-Amin Test Owner',
            'email' => 'alamin2@gmail.com',
            'phone' => '08087654321',
            'password' => 'secret12345',
            'passwordConfirmation' => 'secret12345',
            'terms' => false,
        ])
        ->call('register')
        ->assertHasFormErrors(['terms' => 'accepted']);

    $this->assertDatabaseMissing('owners', [
        'email' => 'alamin2@gmail.com',
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

test('store registration rejects invalid or numeric subdomains and requires starting with a letter', function (string $invalidSubdomain) {
    $owner = Owner::create([
        'name' => 'Subdomain Test Owner',
        'email' => 'subdomain_test_'.uniqid().'@example.com',
        'password' => bcrypt('password'),
    ]);

    Filament::setCurrentPanel(Filament::getPanel('merchant'));
    $this->actingAs($owner, 'owner');

    Livewire::test(RegisterStore::class)
        ->fillForm([
            'name' => 'Subdomain Test Store',
            'subdomain' => $invalidSubdomain,
        ])
        ->call('register')
        ->assertHasFormErrors(['subdomain']);
})->with([
    'pure numeric 5 digits' => '12345',
    'ip-like number' => '167',
    'starts with number' => '9store',
    'starts with hyphen' => '-store',
    'ends with hyphen' => 'store-',
    'too short' => 'ab',
]);

test('store creation automatically activates starter plan and redirects straight to dashboard', function () {
    $owner = Owner::create([
        'name' => 'Fast Onboarding Owner',
        'email' => 'fast_onboarding@example.com',
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
            'name' => 'Direct Dashboard Store',
            'subdomain' => 'directdash',
        ])
        ->call('register')
        ->assertHasNoFormErrors();

    $store = Store::where('name', 'Direct Dashboard Store')->first();
    expect($store)->not->toBeNull();
    expect($store->activeSubscription)->not->toBeNull();
    expect($store->activeSubscription->plan_id)->toBe($starterPlan->id);
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

test('merchant registration rejects disposable email domains including fldubai.com', function () {
    Filament::setCurrentPanel(Filament::getPanel('merchant'));

    Livewire::test(Register::class)
        ->fillForm([
            'name' => 'Bot Tester',
            'email' => 'ptcp2tsyt3fy@fldubai.com',
            'phone' => '08090519568',
            'password' => 'secret12345',
            'passwordConfirmation' => 'secret12345',
            'terms' => true,
        ])
        ->call('register')
        ->assertHasFormErrors(['email']);

    $this->assertDatabaseMissing('owners', [
        'email' => 'ptcp2tsyt3fy@fldubai.com',
    ]);
});

test('merchant registration blocks more than 3 accounts from same IP per 24 hours', function () {
    Filament::setCurrentPanel(Filament::getPanel('merchant'));
    RateLimiter::clear('merchant_register_ip:127.0.0.1');

    // Simulate 3 previous registrations from this IP within 24 hours
    RateLimiter::hit('merchant_register_ip:127.0.0.1', 86400);
    RateLimiter::hit('merchant_register_ip:127.0.0.1', 86400);
    RateLimiter::hit('merchant_register_ip:127.0.0.1', 86400);

    // 4th attempt from same IP must be blocked by rate limiter
    Livewire::test(Register::class)
        ->fillForm([
            'name' => 'Fourth Merchant',
            'email' => 'merchant4@gmail.com',
            'phone' => '08099998888',
            'password' => 'secret12345',
            'passwordConfirmation' => 'secret12345',
            'terms' => true,
        ])
        ->call('register')
        ->assertHasFormErrors(['email']);

    $this->assertDatabaseMissing('owners', [
        'email' => 'merchant4@gmail.com',
    ]);
});

test('merchant registration rejects phone numbers not exactly 11 digits', function () {
    Filament::setCurrentPanel(Filament::getPanel('merchant'));

    // Less than 11 digits
    Livewire::test(Register::class)
        ->fillForm([
            'name' => 'Valid Name',
            'email' => 'validname@gmail.com',
            'phone' => '08012345',
            'password' => 'secret12345',
            'passwordConfirmation' => 'secret12345',
            'terms' => true,
        ])
        ->call('register')
        ->assertHasFormErrors(['phone']);

    // More than 11 digits
    Livewire::test(Register::class)
        ->fillForm([
            'name' => 'Valid Name',
            'email' => 'validname2@gmail.com',
            'phone' => '0801234567890123',
            'password' => 'secret12345',
            'passwordConfirmation' => 'secret12345',
            'terms' => true,
        ])
        ->call('register')
        ->assertHasFormErrors(['phone']);
});

test('merchant registration rejects untrusted email domains and quoted emails', function (string $invalidEmail) {
    Filament::setCurrentPanel(Filament::getPanel('merchant'));

    Livewire::test(Register::class)
        ->fillForm([
            'name' => 'Valid Owner Name',
            'email' => $invalidEmail,
            'phone' => '08012345678',
            'password' => 'secret12345',
            'passwordConfirmation' => 'secret12345',
            'terms' => true,
        ])
        ->call('register')
        ->assertHasFormErrors(['email']);
})->with([
    'untrusted custom domain' => 'attacker@fldubai.com',
    'quoted local-part fldubai' => '"authb82e4u0fef2"@fldubai.com',
    'temp mail 1' => 'bot@mailinator.com',
    'temp mail 2' => 'bot@guerrillamail.com',
]);

test('merchant profile has email disabled and cannot update email directly', function () {
    $owner = Owner::create([
        'name' => 'Profile Owner',
        'email' => 'original@gmail.com',
        'phone' => '08011112222',
        'password' => bcrypt('password123'),
        'email_verified_at' => now(),
    ]);

    Filament::setCurrentPanel(Filament::getPanel('merchant'));
    $this->actingAs($owner, 'owner');

    Livewire::test(EditProfile::class)
        ->assertFormFieldIsDisabled('email')
        ->fillForm([
            'name' => 'Updated Profile Name',
            'phone' => '08099998888',
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($owner->fresh()->name)->toBe('Updated Profile Name');
    expect($owner->fresh()->phone)->toBe('08099998888');
    expect($owner->fresh()->email)->toBe('original@gmail.com');
});
