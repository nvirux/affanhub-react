<?php

use App\Models\Owner;
use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->owner = Owner::create([
        'name' => 'Store Owner',
        'email' => 'owner@example.com',
        'password' => bcrypt('password'),
    ]);

    $this->store = Store::create([
        'name' => 'Demo Store',
        'public_id' => 'str_demo',
        'owner_id' => $this->owner->id,
        'status' => 'active',
    ]);

    $this->store->domains()->create([
        'domain' => 'demo.localhost',
    ]);
});

test('login screen can be rendered on tenant domain', function () {
    $response = $this->get('http://demo.localhost/login');

    $response->assertOk();
});

test('check identifier returns 404 when user does not exist', function () {
    $response = $this->postJson('http://demo.localhost/login/check-identifier', [
        'identifier' => 'nonexistent@example.com',
    ]);

    $response->assertNotFound()
        ->assertJson([
            'status' => 'not_found',
        ]);
});

test('check identifier detects user with login PIN enabled', function () {
    $user = User::create([
        'name' => 'PIN User',
        'email' => 'pinuser@example.com',
        'phone' => '08011223344',
        'login_pin_hash' => '1234',
        'login_pin_enabled' => true,
        'store_id' => $this->store->id,
    ]);

    $response = $this->postJson('http://demo.localhost/login/check-identifier', [
        'identifier' => '08011223344',
    ]);

    $response->assertOk()
        ->assertJson([
            'status' => 'found',
            'login_pin_enabled' => true,
            'name' => 'PIN User',
        ]);
});

test('check identifier detects user with legacy password', function () {
    $user = User::create([
        'name' => 'Password User',
        'email' => 'legacy@example.com',
        'phone' => '08099887766',
        'password' => 'secret123',
        'login_pin_enabled' => false,
        'store_id' => $this->store->id,
    ]);

    $response = $this->postJson('http://demo.localhost/login/check-identifier', [
        'identifier' => 'legacy@example.com',
    ]);

    $response->assertOk()
        ->assertJson([
            'status' => 'found',
            'login_pin_enabled' => false,
            'name' => 'Password User',
        ]);
});

test('users can authenticate using 4-digit login PIN via phone number', function () {
    $user = User::create([
        'name' => 'PIN Customer',
        'email' => 'pincustomer@example.com',
        'phone' => '08055667788',
        'login_pin_hash' => '4321',
        'login_pin_enabled' => true,
        'store_id' => $this->store->id,
    ]);

    $response = $this->post('http://demo.localhost/login', [
        'email' => '08055667788',
        'pin' => '4321',
        'password' => '4321',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect('http://demo.localhost/dashboard');
});

test('users can authenticate using 4-digit login PIN via email', function () {
    $user = User::create([
        'name' => 'PIN Customer 2',
        'email' => 'pin2@example.com',
        'phone' => '08044332211',
        'login_pin_hash' => '9988',
        'login_pin_enabled' => true,
        'store_id' => $this->store->id,
    ]);

    $response = $this->post('http://demo.localhost/login', [
        'email' => 'pin2@example.com',
        'pin' => '9988',
        'password' => '9988',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect('http://demo.localhost/dashboard');
});

test('legacy users authenticating with password are redirected to setup-pin', function () {
    $user = User::create([
        'name' => 'Legacy User',
        'email' => 'legacyuser@example.com',
        'phone' => '08033221100',
        'password' => 'legacy-secret',
        'login_pin_enabled' => false,
        'store_id' => $this->store->id,
    ]);

    $response = $this->post('http://demo.localhost/login', [
        'email' => 'legacyuser@example.com',
        'password' => 'legacy-secret',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect('http://demo.localhost/setup-pin');
});

test('legacy users can set up 4-digit PIN after password login and reach dashboard', function () {
    $user = User::create([
        'name' => 'Setting Up User',
        'email' => 'settingup@example.com',
        'phone' => '08099881122',
        'password' => 'password',
        'login_pin_enabled' => false,
        'store_id' => $this->store->id,
    ]);

    $loginResponse = $this->post('http://demo.localhost/login', [
        'email' => 'settingup@example.com',
        'password' => 'password',
    ]);

    $loginResponse->assertRedirect('http://demo.localhost/setup-pin');
    $loginResponse->assertSessionHas('needs_pin_setup', true);

    $response = $this->actingAs($user)->withSession(['needs_pin_setup' => true])->post('http://demo.localhost/setup-pin', [
        'pin' => '7788',
        'pin_confirmation' => '7788',
    ]);

    $response->assertRedirect('http://demo.localhost/dashboard');
    expect($user->fresh()->login_pin_enabled)->toBeTrue()
        ->and(Hash::check('7788', $user->fresh()->login_pin_hash))->toBeTrue();
});

test('authenticated users without needs_pin_setup session flag cannot access setup-pin', function () {
    $user = User::create([
        'name' => 'Unauthorized Setup User',
        'email' => 'unauthsetup@example.com',
        'phone' => '08099881144',
        'password' => 'password',
        'login_pin_enabled' => false,
        'store_id' => $this->store->id,
    ]);

    $response = $this->actingAs($user)->get('http://demo.localhost/setup-pin');

    $response->assertRedirect('http://demo.localhost/dashboard');
});

test('users with PIN already set up are redirected from setup-pin to dashboard', function () {
    $user = User::create([
        'name' => 'Ready User',
        'email' => 'ready@example.com',
        'phone' => '08099881133',
        'login_pin_hash' => '1234',
        'login_pin_enabled' => true,
        'store_id' => $this->store->id,
    ]);

    $response = $this->actingAs($user)->withSession(['needs_pin_setup' => true])->get('http://demo.localhost/setup-pin');

    $response->assertRedirect('http://demo.localhost/dashboard');
});

test('users with PIN can also authenticate using password fallback', function () {
    $user = User::create([
        'name' => 'Fallback User',
        'email' => 'fallback@example.com',
        'phone' => '08055443311',
        'password' => 'fallback-password',
        'login_pin_hash' => '1234',
        'login_pin_enabled' => true,
        'store_id' => $this->store->id,
    ]);

    $response = $this->post('http://demo.localhost/login', [
        'email' => 'fallback@example.com',
        'password' => 'fallback-password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect('http://demo.localhost/dashboard');
});

test('users cannot authenticate with wrong PIN', function () {
    $user = User::create([
        'name' => 'Wrong PIN User',
        'email' => 'wrongpin@example.com',
        'phone' => '08077665544',
        'login_pin_hash' => '1234',
        'login_pin_enabled' => true,
        'store_id' => $this->store->id,
    ]);

    $response = $this->post('http://demo.localhost/login', [
        'email' => '08077665544',
        'pin' => '9999',
        'password' => '9999',
    ]);

    $this->assertGuest();
    $response->assertSessionHasErrors();
});

test('users can logout', function () {
    $user = User::create([
        'name' => 'Logout User',
        'email' => 'logout@example.com',
        'phone' => '08011112222',
        'login_pin_hash' => '1234',
        'login_pin_enabled' => true,
        'store_id' => $this->store->id,
    ]);

    $response = $this->actingAs($user)->post('http://demo.localhost/logout');

    $response->assertRedirect('http://demo.localhost');
    $this->assertGuest();
});

test('users are rate limited after 5 failed PIN attempts', function () {
    $user = User::create([
        'name' => 'Rate Limit PIN User',
        'email' => 'ratelimit@example.com',
        'phone' => '08012344321',
        'login_pin_hash' => '1234',
        'login_pin_enabled' => true,
        'store_id' => $this->store->id,
    ]);

    for ($i = 0; $i < 5; $i++) {
        $this->post('http://demo.localhost/login', [
            'email' => '08012344321',
            'pin' => '0000',
            'password' => '0000',
        ]);
        $this->assertGuest();
    }

    // 6th attempt is throttled
    $response = $this->post('http://demo.localhost/login', [
        'email' => '08012344321',
        'pin' => '0000',
        'password' => '0000',
    ]);

    $response->assertTooManyRequests();
});
