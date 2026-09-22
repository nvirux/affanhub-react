<?php

use App\Models\Owner;
use App\Models\Store;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\GoogleProvider;
use Laravel\Socialite\Two\User as SocialiteUser;

uses(RefreshDatabase::class);

test('merchant google redirect initiates oauth flow', function () {
    $provider = Mockery::mock(GoogleProvider::class);
    $provider->shouldReceive('redirect')
        ->once()
        ->andReturn(redirect('https://accounts.google.com/o/oauth2/auth'));

    Socialite::shouldReceive('driver')
        ->with('google')
        ->once()
        ->andReturn($provider);

    $response = $this->get(route('merchant.google.redirect'));

    $response->assertRedirect('https://accounts.google.com/o/oauth2/auth');
});

test('merchant google callback registers new owner and redirects to phone completion', function () {
    $googleUser = Mockery::mock(SocialiteUser::class);
    $googleUser->shouldReceive('getId')->andReturn('google-id-123');
    $googleUser->shouldReceive('getEmail')->andReturn('newmerchant@example.com');
    $googleUser->shouldReceive('getName')->andReturn('New Merchant');
    $googleUser->shouldReceive('getAvatar')->andReturn('https://google.com/avatar.png');

    $provider = Mockery::mock(GoogleProvider::class);
    $provider->shouldReceive('user')->once()->andReturn($googleUser);

    Socialite::shouldReceive('driver')->with('google')->once()->andReturn($provider);

    $response = $this->get(route('merchant.google.callback'));

    $this->assertDatabaseHas('owners', [
        'email' => 'newmerchant@example.com',
        'google_id' => 'google-id-123',
        'name' => 'New Merchant',
    ]);

    $owner = Owner::where('email', 'newmerchant@example.com')->first();
    expect($owner->email_verified_at)->not->toBeNull()
        ->and($owner->phone)->toBeNull();

    $this->assertAuthenticatedAs($owner, 'owner');

    // Must be redirected to phone completion
    $response->assertRedirect(route('merchant.phone.complete'));
});

test('merchant must enter a valid phone number before accessing store setup', function () {
    $owner = Owner::create([
        'name' => 'Pending Phone Merchant',
        'email' => 'phonepending@example.com',
        'google_id' => 'google-id-pending',
        'email_verified_at' => now(),
        'phone' => null,
    ]);

    $this->actingAs($owner, 'owner');

    // Phone completion form renders cleanly
    $getResponse = $this->get(route('merchant.phone.complete'));
    $getResponse->assertSuccessful();
    $getResponse->assertSee('One Last Step');
    $getResponse->assertSee('Phone Number');

    // Submitting with empty phone fails validation
    $failResponse = $this->post(route('merchant.phone.store'), ['phone' => '']);
    $failResponse->assertSessionHasErrors('phone');

    // Submitting with a valid phone number succeeds and routes to tenant registration
    $successResponse = $this->post(route('merchant.phone.store'), [
        'phone' => '08012345678',
    ]);

    $panel = Filament::getPanel('merchant');
    $successResponse->assertRedirect($panel->getTenantRegistrationUrl());

    expect($owner->fresh()->phone)->toBe('08012345678');
});

test('merchant cannot use a duplicate phone number', function () {
    Owner::create([
        'name' => 'Existing Merchant',
        'email' => 'other@example.com',
        'phone' => '08099998888',
        'password' => bcrypt('secret123'),
    ]);

    $owner = Owner::create([
        'name' => 'New Merchant',
        'email' => 'newwithdup@example.com',
        'phone' => null,
    ]);

    $response = $this->actingAs($owner, 'owner')
        ->post(route('merchant.phone.store'), [
            'phone' => '08099998888',
        ]);

    $response->assertSessionHasErrors('phone');
    expect($owner->fresh()->phone)->toBeNull();
});

test('middleware intercepts owner with missing phone when accessing merchant panel', function () {
    $owner = Owner::create([
        'name' => 'No Phone Owner',
        'email' => 'nophone@example.com',
        'phone' => null,
    ]);

    $panel = Filament::getPanel('merchant');

    // Attempting to access store registration while phone is empty redirects to phone completion
    $response = $this->actingAs($owner, 'owner')->get($panel->getTenantRegistrationUrl());
    $response->assertRedirect(route('merchant.phone.complete'));
});

test('merchant google callback authenticates existing owner with phone directly to store', function () {
    $owner = Owner::create([
        'name' => 'Store Merchant',
        'email' => 'storemerchant@example.com',
        'phone' => '08055554444',
        'google_id' => 'google-id-with-store',
        'email_verified_at' => now(),
    ]);

    $store = Store::create([
        'name' => 'Merchant Test Store',
        'public_id' => 'store_test123',
        'owner_id' => $owner->id,
        'email' => 'storemerchant@example.com',
    ]);

    $owner->stores()->attach($store->id, ['role' => 'owner']);
    $owner->recordActiveStore($store->id);

    $googleUser = Mockery::mock(SocialiteUser::class);
    $googleUser->shouldReceive('getId')->andReturn('google-id-with-store');
    $googleUser->shouldReceive('getEmail')->andReturn('storemerchant@example.com');
    $googleUser->shouldReceive('getName')->andReturn('Store Merchant');
    $googleUser->shouldReceive('getAvatar')->andReturn(null);

    $provider = Mockery::mock(GoogleProvider::class);
    $provider->shouldReceive('user')->once()->andReturn($googleUser);

    Socialite::shouldReceive('driver')->with('google')->once()->andReturn($provider);

    $response = $this->get(route('merchant.google.callback'));

    $this->assertAuthenticatedAs($owner, 'owner');

    $panel = Filament::getPanel('merchant');
    $response->assertRedirect($panel->getUrl($store));
});

test('merchant google callback handles oauth failure gracefully', function () {
    $provider = Mockery::mock(GoogleProvider::class);
    $provider->shouldReceive('user')->once()->andThrow(new Exception('OAuth access denied'));

    Socialite::shouldReceive('driver')->with('google')->once()->andReturn($provider);

    $response = $this->get(route('merchant.google.callback'));

    $panel = Filament::getPanel('merchant');
    $response->assertRedirect($panel->getLoginUrl());
    $response->assertSessionHas('error');
    $this->assertGuest('owner');
});

test('merchant login and register pages display continue with google button', function () {
    $panel = Filament::getPanel('merchant');

    $loginResponse = $this->get($panel->getLoginUrl());
    $loginResponse->assertSuccessful();
    $loginResponse->assertSee('Log in with Google');
    $loginResponse->assertSee(route('merchant.google.redirect'));

    $registerResponse = $this->get($panel->getRegistrationUrl());
    $registerResponse->assertSuccessful();
    $registerResponse->assertSee('Sign up with Google');
    $registerResponse->assertSee(route('merchant.google.redirect'));
});
