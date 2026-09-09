<?php

use App\Models\Owner;
use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Inertia\Testing\AssertableInertia as Assert;
use Laravel\Fortify\Features;

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

test('security page is displayed', function () {
    $this->skipUnlessFortifyHas(Features::twoFactorAuthentication());

    Features::twoFactorAuthentication([
        'confirm' => true,
        'confirmPassword' => true,
    ]);
    $user = User::factory()->create(['store_id' => $this->store->id]);

    $this->actingAs($user)
        ->withSession(['auth.password_confirmed_at' => time()])
        ->get('http://demo.localhost/settings/security')
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('settings/security')
            ->where('canManagePasskeys', false)
            ->where('passkeys', [])
            ->where('canManageTwoFactor', true)
            ->where('twoFactorEnabled', false),
        );
});

test('security page requires password confirmation when enabled', function () {
    $this->skipUnlessFortifyHas(Features::twoFactorAuthentication());

    $user = User::factory()->create(['store_id' => $this->store->id]);

    Features::twoFactorAuthentication([
        'confirm' => true,
        'confirmPassword' => true,
    ]);

    $response = $this->actingAs($user)
        ->get('http://demo.localhost/settings/security');

    $response->assertRedirect('http://demo.localhost/user/confirm-password');
});

test('security page renders without two factor when feature is disabled', function () {
    $this->skipUnlessFortifyHas(Features::twoFactorAuthentication());

    config(['fortify.features' => []]);

    $user = User::factory()->create(['store_id' => $this->store->id]);

    $this->actingAs($user)
        ->withSession(['auth.password_confirmed_at' => time()])
        ->get('http://demo.localhost/settings/security')
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('settings/security')
            ->where('canManagePasskeys', false)
            ->where('passkeys', [])
            ->where('canManageTwoFactor', false)
            ->missing('twoFactorEnabled')
            ->missing('requiresConfirmation'),
        );
});

test('password can be updated', function () {
    $user = User::factory()->create(['store_id' => $this->store->id]);

    $response = $this
        ->actingAs($user)
        ->from('http://demo.localhost/settings/security')
        ->put('http://demo.localhost/settings/password', [
            'current_password' => 'password',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('http://demo.localhost/settings/security');

    expect(Hash::check('new-password', $user->refresh()->password))->toBeTrue();
});

test('correct password must be provided to update password', function () {
    $user = User::factory()->create(['store_id' => $this->store->id]);

    $response = $this
        ->actingAs($user)
        ->from('http://demo.localhost/settings/security')
        ->put('http://demo.localhost/settings/password', [
            'current_password' => 'wrong-password',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);

    $response
        ->assertSessionHasErrors('current_password')
        ->assertRedirect('http://demo.localhost/settings/security');
});

test('login PIN can be updated with valid current PIN', function () {
    $user = User::factory()->withLoginPin('1234')->create(['store_id' => $this->store->id]);

    $response = $this
        ->actingAs($user)
        ->from('http://demo.localhost/settings/security')
        ->put('http://demo.localhost/settings/login-pin', [
            'current_pin' => '1234',
            'pin' => '9876',
            'pin_confirmation' => '9876',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('http://demo.localhost/settings/security');

    expect(Hash::check('9876', $user->refresh()->login_pin_hash))->toBeTrue();
});

test('login PIN cannot be updated with incorrect current PIN', function () {
    $user = User::factory()->withLoginPin('1234')->create(['store_id' => $this->store->id]);

    $response = $this
        ->actingAs($user)
        ->from('http://demo.localhost/settings/security')
        ->put('http://demo.localhost/settings/login-pin', [
            'current_pin' => '0000',
            'pin' => '9876',
            'pin_confirmation' => '9876',
        ]);

    $response
        ->assertSessionHasErrors('current_pin')
        ->assertRedirect('http://demo.localhost/settings/security');
});

test('transaction PIN can be updated with valid current PIN', function () {
    $user = User::factory()->withTransactionPin('5555')->create(['store_id' => $this->store->id]);

    $response = $this
        ->actingAs($user)
        ->from('http://demo.localhost/settings/security')
        ->put('http://demo.localhost/settings/transaction-pin', [
            'current_pin' => '5555',
            'pin' => '7777',
            'pin_confirmation' => '7777',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('http://demo.localhost/settings/security');

    expect(Hash::check('7777', $user->refresh()->transaction_pin_hash))->toBeTrue();
});

test('transaction PIN can be set for the first time using login PIN authorization', function () {
    $user = User::factory()->withLoginPin('1234')->create([
        'store_id' => $this->store->id,
        'transaction_pin_hash' => null,
    ]);

    $response = $this
        ->actingAs($user)
        ->from('http://demo.localhost/settings/security')
        ->put('http://demo.localhost/settings/transaction-pin', [
            'login_pin' => '1234',
            'pin' => '8888',
            'pin_confirmation' => '8888',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('http://demo.localhost/settings/security');

    expect(Hash::check('8888', $user->refresh()->transaction_pin_hash))->toBeTrue();
});

test('passwordless user can create a password by authorizing with login PIN', function () {
    $user = User::factory()
        ->withoutPassword()
        ->withLoginPin('1234')
        ->create(['store_id' => $this->store->id]);

    $response = $this
        ->actingAs($user)
        ->from('http://demo.localhost/settings/security')
        ->put('http://demo.localhost/settings/password', [
            'login_pin' => '1234',
            'password' => 'BrandNewPassword123!',
            'password_confirmation' => 'BrandNewPassword123!',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('http://demo.localhost/settings/security');

    expect(Hash::check('BrandNewPassword123!', $user->refresh()->password))->toBeTrue();
});
