<?php

use App\Models\Owner;
use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Fortify\Features;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->skipUnlessFortifyHas(Features::registration());

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

test('registration screen can be rendered on tenant domain', function () {
    $response = $this->get('http://demo.localhost/register');

    $response->assertOk();
});

test('new users can register with phone number', function () {
    $response = $this->post('http://demo.localhost/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'phone' => '08012345678',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect('http://demo.localhost/dashboard');

    $user = User::where('email', 'test@example.com')->first();
    expect($user)->not->toBeNull()
        ->and($user->phone)->toBe('08012345678')
        ->and($user->store_id)->toBe($this->store->id);
});

test('registration fails when phone number is missing', function () {
    $response = $this->post('http://demo.localhost/register', [
        'name' => 'No Phone User',
        'email' => 'nophone@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertSessionHasErrors(['phone']);
    expect(User::where('email', 'nophone@example.com')->exists())->toBeFalse();
});

test('new users can register with 4-digit login pin', function () {
    $response = $this->post('http://demo.localhost/register', [
        'name' => 'PIN Newbie',
        'email' => 'pinnewbie@example.com',
        'phone' => '08099112233',
        'pin' => '5678',
        'pin_confirmation' => '5678',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect('http://demo.localhost/dashboard');

    $user = User::where('email', 'pinnewbie@example.com')->first();
    expect($user)->not->toBeNull()
        ->and($user->phone)->toBe('08099112233')
        ->and($user->login_pin_enabled)->toBeTrue()
        ->and($user->password)->toBeNull()
        ->and(Hash::check('5678', $user->login_pin_hash))->toBeTrue();
});

test('registration fails when pin is not 4 digits or does not match confirmation', function () {
    $response = $this->post('http://demo.localhost/register', [
        'name' => 'Invalid PIN User',
        'email' => 'invalidpin@example.com',
        'phone' => '08055443322',
        'pin' => '123',
        'pin_confirmation' => '1234',
    ]);

    $response->assertSessionHasErrors(['pin']);
    expect(User::where('email', 'invalidpin@example.com')->exists())->toBeFalse();
});

test('registration fails when phone number is not exactly 11 digits', function () {
    $response = $this->post('http://demo.localhost/register', [
        'name' => 'Short Phone',
        'email' => 'shortphone@example.com',
        'phone' => '080123456',
        'pin' => '1234',
        'pin_confirmation' => '1234',
    ]);

    $response->assertSessionHasErrors(['phone']);
    expect(User::where('email', 'shortphone@example.com')->exists())->toBeFalse();
});

test('step 1 validation passes for valid new user details', function () {
    $response = $this->postJson('http://demo.localhost/register/validate-step-1', [
        'name' => 'John Doe',
        'email' => 'john.valid@example.com',
        'phone' => '08012345678',
    ]);

    $response->assertOk()
        ->assertJson(['valid' => true]);
});

test('step 1 validation fails if email or phone already exists or phone length is invalid', function () {
    User::create([
        'name' => 'Existing User',
        'email' => 'existing@example.com',
        'phone' => '08011223344',
        'store_id' => $this->store->id,
    ]);

    // Test duplicate email
    $res1 = $this->postJson('http://demo.localhost/register/validate-step-1', [
        'name' => 'New Guy',
        'email' => 'existing@example.com',
        'phone' => '08099887766',
    ]);
    $res1->assertStatus(422)
        ->assertJsonValidationErrors(['email']);

    // Test duplicate phone
    $res2 = $this->postJson('http://demo.localhost/register/validate-step-1', [
        'name' => 'New Guy 2',
        'email' => 'unique@example.com',
        'phone' => '08011223344',
    ]);
    $res2->assertStatus(422)
        ->assertJsonValidationErrors(['phone']);

    // Test non-11 digit phone
    $res3 = $this->postJson('http://demo.localhost/register/validate-step-1', [
        'name' => 'New Guy 3',
        'email' => 'unique3@example.com',
        'phone' => '0801234',
    ]);
    $res3->assertStatus(422)
        ->assertJsonValidationErrors(['phone']);
});
