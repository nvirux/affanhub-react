<?php

use App\Models\Owner;
use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
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

test('setup transaction pin page can be rendered for user without transaction pin', function () {
    $user = User::factory()->create([
        'store_id' => $this->store->id,
        'transaction_pin_hash' => null,
    ]);

    $response = $this->actingAs($user)
        ->get('http://demo.localhost/setup-transaction-pin');

    $response->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('auth/setup-transaction-pin')
        );
});

test('user with transaction pin is redirected away from setup page', function () {
    $user = User::factory()->withTransactionPin('1234')->create([
        'store_id' => $this->store->id,
    ]);

    $response = $this->actingAs($user)
        ->get('http://demo.localhost/setup-transaction-pin');

    $response->assertRedirect('http://demo.localhost/dashboard');
});

test('user can set transaction pin and is redirected to intended URL', function () {
    $user = User::factory()->create([
        'store_id' => $this->store->id,
        'transaction_pin_hash' => null,
    ]);

    $response = $this->actingAs($user)
        ->withSession(['url.intended' => 'http://demo.localhost/vtu/airtime'])
        ->post('http://demo.localhost/setup-transaction-pin', [
            'pin' => '8888',
            'pin_confirmation' => '8888',
        ]);

    $response->assertSessionHasNoErrors()
        ->assertRedirect('http://demo.localhost/vtu/airtime');

    expect(Hash::check('8888', $user->refresh()->transaction_pin_hash))->toBeTrue();
});

test('transaction PIN must be exactly 4 digits', function () {
    $user = User::factory()->create([
        'store_id' => $this->store->id,
        'transaction_pin_hash' => null,
    ]);

    $response = $this->actingAs($user)
        ->post('http://demo.localhost/setup-transaction-pin', [
            'pin' => '123',
            'pin_confirmation' => '123',
        ]);

    $response->assertSessionHasErrors('pin');
});

test('transaction PIN must match confirmation', function () {
    $user = User::factory()->create([
        'store_id' => $this->store->id,
        'transaction_pin_hash' => null,
    ]);

    $response = $this->actingAs($user)
        ->post('http://demo.localhost/setup-transaction-pin', [
            'pin' => '1234',
            'pin_confirmation' => '4321',
        ]);

    $response->assertSessionHasErrors('pin');
});

test('EnsureTransactionPinSet middleware redirects user without PIN and remembers intended URL', function () {
    $user = User::factory()->create([
        'store_id' => $this->store->id,
        'transaction_pin_hash' => null,
    ]);

    $response = $this->actingAs($user)
        ->get('http://demo.localhost/vtu/airtime');

    $response->assertRedirect('http://demo.localhost/setup-transaction-pin');
    expect(session('url.intended'))->toBe('http://demo.localhost/vtu/airtime');
});

test('EnsureTransactionPinSet middleware allows user with PIN through', function () {
    $user = User::factory()->withTransactionPin('1234')->create([
        'store_id' => $this->store->id,
    ]);

    $response = $this->actingAs($user)
        ->get('http://demo.localhost/vtu/airtime');

    $response->assertOk();
});
