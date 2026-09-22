<?php

use App\Actions\Fortify\CreateNewUser;
use App\Models\Network;
use App\Models\Owner;
use App\Models\Store;
use App\Models\User;
use App\Services\Vtu\AirtimeService;
use App\Services\WalletService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

uses(RefreshDatabase::class);

beforeEach(function () {
    Cache::flush();
    RateLimiter::clear('register_ip:127.0.0.1');

    $this->owner = Owner::create([
        'name' => 'Owner Admin',
        'email' => 'owner@example.com',
        'password' => bcrypt('password'),
    ]);

    $this->store = Store::create([
        'name' => 'Secure Telecom',
        'owner_id' => $this->owner->id,
    ]);

    $this->network = Network::create([
        'name' => 'MTN',
        'slug' => 'mtn',
        'is_active' => true,
    ]);

    $this->store->domains()->create([
        'domain' => 'secure.localhost',
    ]);
});

test('registration rejects disposable or throwaway email domains', function () {
    $action = app(CreateNewUser::class);

    $disposableEmails = [
        'bot1@tempmail.com',
        'bot2@guerrillamail.com',
        'bot3@10minutemail.com',
        'bot4@mailinator.com',
        'bot5@yopmail.com',
    ];

    foreach ($disposableEmails as $email) {
        expect(fn () => $action->create([
            'name' => 'Fake Bot',
            'email' => $email,
            'phone' => '080'.rand(10000000, 99999999),
            'pin' => '1234',
            'pin_confirmation' => '1234',
            'store_id' => $this->store->id,
        ]))->toThrow(ValidationException::class);
    }
});

test('registration enforces IP rate limit of maximum 3 registrations per hour', function () {
    $action = app(CreateNewUser::class);

    // First 3 registrations succeed
    for ($i = 1; $i <= 3; $i++) {
        $user = $action->create([
            'name' => "Legit User {$i}",
            'email' => "user{$i}@gmail.com",
            'phone' => "0801111222{$i}",
            'pin' => '1234',
            'pin_confirmation' => '1234',
            'store_id' => $this->store->id,
        ]);
        expect($user)->toBeInstanceOf(User::class);
    }

    // 4th registration from same IP is blocked by RateLimiter
    expect(fn () => $action->create([
        'name' => 'Excess User 4',
        'email' => 'user4@gmail.com',
        'phone' => '08011112224',
        'pin' => '1234',
        'pin_confirmation' => '1234',
        'store_id' => $this->store->id,
    ]))->toThrow(ValidationException::class);
});

test('suspended user is rejected during login check-identifier', function () {
    $user = User::factory()->withTransactionPin('1234')->create([
        'store_id' => $this->store->id,
        'email' => 'gambari@example.com',
        'is_active' => false,
    ]);

    $response = $this->postJson('http://secure.localhost/login/check-identifier', [
        'identifier' => 'gambari@example.com',
    ]);

    $response->assertStatus(403)
        ->assertJson([
            'status' => 'suspended',
            'message' => 'Your account has been suspended. Please contact store support.',
        ]);
});

test('suspended user cannot authenticate or login via fortify', function () {
    $user = User::create([
        'name' => 'Gambari Suspended',
        'store_id' => $this->store->id,
        'email' => 'gambari2@example.com',
        'phone' => '08099881122',
        'login_pin_hash' => '1234',
        'login_pin_enabled' => true,
        'is_active' => false, // Suspended!
    ]);

    $response = $this->post('http://secure.localhost/login', [
        'email' => 'gambari2@example.com',
        'pin' => '1234',
        'password' => '1234',
    ]);

    $response->assertSessionHasErrors(['email']);
    $this->assertGuest();
});

test('banning a user sets is_active false and destroys active sessions', function () {
    $user = User::factory()->create([
        'store_id' => $this->store->id,
        'is_active' => true,
    ]);

    // Insert mock session
    DB::table('sessions')->insert([
        'id' => 'sess_mock_123',
        'user_id' => $user->id,
        'ip_address' => '127.0.0.1',
        'user_agent' => 'Browser',
        'payload' => 'payload',
        'last_activity' => time(),
    ]);

    expect(DB::table('sessions')->where('user_id', $user->id)->exists())->toBeTrue();

    // Ban user
    $user->ban('Abuse detected');

    expect($user->fresh()->is_active)->toBeFalse();
    expect(DB::table('sessions')->where('user_id', $user->id)->exists())->toBeFalse();

    // Reactivate
    $user->activate();
    expect($user->fresh()->is_active)->toBeTrue();
});

test('suspended user is immediately logged out by EnsureUserIsActive middleware', function () {
    $user = User::factory()->create([
        'store_id' => $this->store->id,
        'is_active' => false,
    ]);

    $response = $this->actingAs($user)->get('http://secure.localhost/dashboard');

    $response->assertRedirect('http://secure.localhost/login');
    $this->assertGuest();
});

test('suspended user cannot make vtu purchases', function () {
    $user = User::factory()->create([
        'store_id' => $this->store->id,
        'is_active' => false,
    ]);

    $walletService = app(WalletService::class);
    $walletService->credit($user->wallet('main'), 5000.00, 'deposit', 'Funded');
    $walletService->credit($this->store->wallet('main'), 10000.00, 'deposit', 'Store wholesale');

    $airtimeService = app(AirtimeService::class);

    expect(fn () => $airtimeService->buyAirtime($user, $this->network, 1000.00, '08012345678'))
        ->toThrow(Exception::class, 'Your account has been suspended.');
});
