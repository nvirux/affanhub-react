<?php

use App\Models\Owner;
use App\Models\Store;
use App\Models\User;
use App\Notifications\ResetLoginPinNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Inertia\Testing\AssertableInertia as Assert;

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

test('forgot pin screen can be rendered', function () {
    $response = $this->get('http://demo.localhost/forgot-pin');

    $response->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('auth/forgot-pin')
        );
});

test('reset pin link can be requested with registered email', function () {
    Notification::fake();

    $user = User::factory()->withLoginPin('1234')->create([
        'store_id' => $this->store->id,
        'email' => 'customer@example.com',
    ]);

    $response = $this->from('http://demo.localhost/forgot-pin')
        ->post('http://demo.localhost/forgot-pin', [
            'identifier' => 'customer@example.com',
        ]);

    $response->assertSessionHasNoErrors()
        ->assertRedirect('http://demo.localhost/forgot-pin');

    Notification::assertSentTo($user, ResetLoginPinNotification::class);
});

test('reset pin link can be requested with registered phone number', function () {
    Notification::fake();

    $user = User::factory()->withLoginPin('1234')->create([
        'store_id' => $this->store->id,
        'phone' => '08012345678',
    ]);

    $response = $this->from('http://demo.localhost/forgot-pin')
        ->post('http://demo.localhost/forgot-pin', [
            'identifier' => '08012345678',
        ]);

    $response->assertSessionHasNoErrors()
        ->assertRedirect('http://demo.localhost/forgot-pin');

    Notification::assertSentTo($user, ResetLoginPinNotification::class);
});

test('reset pin link request fails for non-existent identifier', function () {
    $response = $this->from('http://demo.localhost/forgot-pin')
        ->post('http://demo.localhost/forgot-pin', [
            'identifier' => 'unknown@example.com',
        ]);

    $response->assertSessionHasErrors('identifier');
});

test('reset pin screen can be rendered with valid token and email', function () {
    $user = User::factory()->withLoginPin('1234')->create([
        'store_id' => $this->store->id,
        'email' => 'customer@example.com',
    ]);

    $token = Password::broker()->createToken($user);

    $response = $this->get("http://demo.localhost/reset-pin/{$token}?email=".urlencode($user->email));

    $response->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('auth/reset-pin')
            ->where('token', $token)
            ->where('email', $user->email)
        );
});

test('reset pin screen redirects to login when email is missing', function () {
    $response = $this->get('http://demo.localhost/reset-pin/some-token');

    $response->assertRedirect('http://demo.localhost/login');
});

test('PIN can be reset with valid token and confirmed 4-digit PIN', function () {
    $user = User::factory()->withLoginPin('1234')->create([
        'store_id' => $this->store->id,
        'email' => 'customer@example.com',
    ]);

    $token = Password::broker()->createToken($user);

    $response = $this->post('http://demo.localhost/reset-pin', [
        'token' => $token,
        'email' => $user->email,
        'pin' => '4321',
        'pin_confirmation' => '4321',
    ]);

    $response->assertSessionHasNoErrors()
        ->assertRedirect('http://demo.localhost/login');

    $user->refresh();
    expect(Hash::check('4321', $user->login_pin_hash))->toBeTrue();
    expect($user->login_pin_enabled)->toBeTrue();
});

test('PIN cannot be reset with invalid token', function () {
    $user = User::factory()->withLoginPin('1234')->create([
        'store_id' => $this->store->id,
        'email' => 'customer@example.com',
    ]);

    $response = $this->post('http://demo.localhost/reset-pin', [
        'token' => 'invalid-token-123',
        'email' => $user->email,
        'pin' => '4321',
        'pin_confirmation' => '4321',
    ]);

    $response->assertSessionHasErrors('pin');
    expect(Hash::check('1234', $user->refresh()->login_pin_hash))->toBeTrue();
});

test('PIN cannot be reset with mismatched confirmation', function () {
    $user = User::factory()->withLoginPin('1234')->create([
        'store_id' => $this->store->id,
        'email' => 'customer@example.com',
    ]);

    $token = Password::broker()->createToken($user);

    $response = $this->post('http://demo.localhost/reset-pin', [
        'token' => $token,
        'email' => $user->email,
        'pin' => '4321',
        'pin_confirmation' => '9999',
    ]);

    $response->assertSessionHasErrors('pin');
});

test('PIN cannot be reset with non-4-digit input', function () {
    $user = User::factory()->withLoginPin('1234')->create([
        'store_id' => $this->store->id,
        'email' => 'customer@example.com',
    ]);

    $token = Password::broker()->createToken($user);

    $response = $this->post('http://demo.localhost/reset-pin', [
        'token' => $token,
        'email' => $user->email,
        'pin' => '123',
        'pin_confirmation' => '123',
    ]);

    $response->assertSessionHasErrors('pin');
});
