<?php

use App\Models\Owner;
use App\Models\Store;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('authenticated merchant owner can view profile page without tenant billing error', function () {
    $owner = Owner::create([
        'name' => 'Profile Test Merchant',
        'email' => 'profile_test@example.com',
        'phone' => '08012345678',
        'email_verified_at' => now(),
        'password' => bcrypt('password123'),
    ]);

    $store = Store::create([
        'name' => 'Profile Store',
        'public_id' => 'str_proftest',
        'owner_id' => $owner->id,
        'status' => 'active',
    ]);

    $owner->stores()->attach($store->id, ['role' => 'owner']);
    $owner->recordActiveStore($store->id);

    $baseDomain = parse_url(config('app.url'), PHP_URL_HOST) ?? 'localhost';
    $merchantHost = 'merchant.'.$baseDomain;

    $response = $this->actingAs($owner, 'owner')
        ->get("http://{$merchantHost}/profile");

    $response->assertSuccessful();
});

test('authenticated merchant owner without stores visiting profile does not throw TypeError', function () {
    $owner = Owner::create([
        'name' => 'Storeless Merchant',
        'email' => 'storeless@example.com',
        'phone' => '08099887766',
        'email_verified_at' => now(),
        'password' => bcrypt('password123'),
    ]);

    $baseDomain = parse_url(config('app.url'), PHP_URL_HOST) ?? 'localhost';
    $merchantHost = 'merchant.'.$baseDomain;

    $response = $this->actingAs($owner, 'owner')
        ->get("http://{$merchantHost}/profile");

    // Can either succeed or redirect to /new, but must NOT throw TypeError 500
    expect($response->status())->toBeIn([200, 302]);
});
