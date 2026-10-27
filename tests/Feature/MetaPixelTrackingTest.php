<?php

use App\Models\Owner;
use App\Models\Plan;
use App\Models\Store;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use PayMint\Laravel\Facades\PayMint;
use PayMint\Resources\Checkout;

uses(RefreshDatabase::class);

test('central website renders meta pixel base code with pixel id 1955741159167125', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
    $response->assertSee('1955741159167125');
    $response->assertSee('https://connect.facebook.net/en_US/fbevents.js');
    $response->assertSee("fbq('init', '1955741159167125');", false);
});

test('tenant website isolates and does not render meta pixel base code', function () {
    $owner = Owner::create([
        'name' => 'Store Owner',
        'email' => 'owner@example.com',
        'password' => bcrypt('password'),
    ]);

    $store = Store::create([
        'name' => 'Al-Amin Telecom',
        'owner_id' => $owner->id,
    ]);

    $store->domains()->create([
        'domain' => 'alamin.localhost',
    ]);

    $response = $this->get('http://alamin.localhost/');

    $response->assertStatus(200);
    $response->assertDontSee('1955741159167125');
    $response->assertDontSee('connect.facebook.net/en_US/fbevents.js');
});

test('successful subscription checkout verification flashes Subscribe meta pixel event', function () {
    $owner = Owner::create([
        'name' => 'Subscriber Owner',
        'email' => 'sub@example.com',
        'password' => bcrypt('password'),
    ]);

    $store = Store::create([
        'name' => 'Sub Store',
        'owner_id' => $owner->id,
    ]);

    $plan = Plan::create([
        'name' => 'Pro Plan',
        'slug' => 'pro',
        'price_monthly' => 5000.00,
        'price_yearly' => 50000.00,
        'is_active' => true,
    ]);

    $reference = 'PAYMINT_SUB_OK_123';

    Cache::put("billing_checkout_{$reference}", [
        'store_id' => $store->id,
        'plan_id' => $plan->id,
        'interval' => 'month',
        'price' => 5000.00,
    ], now()->addMinutes(30));

    $checkoutMock = Mockery::mock(Checkout::class);
    $checkoutMock->shouldReceive('verify')
        ->with($reference)
        ->once()
        ->andReturn([
            'status' => 'successful',
            'data' => [
                'status' => 'successful',
                'reference' => $reference,
                'amount' => 5000.00,
            ],
        ]);

    PayMint::shouldReceive('checkout')
        ->once()
        ->andReturn($checkoutMock);

    $response = $this->get(route('merchant.billing.callback', [
        'tenant' => $store->public_id,
        'reference' => $reference,
    ]));

    $response->assertRedirect(route('filament.merchant.pages.billing', ['tenant' => $store->public_id]));
    $response->assertSessionHas('pixel_event', [
        'event' => 'Subscribe',
        'params' => [
            'value' => 5000.00,
            'currency' => 'NGN',
            'predicted_ltv' => 5000.00,
            'content_name' => 'Plan: Pro Plan',
        ],
    ]);
});

test('failed subscription checkout verification does not flash Subscribe meta pixel event', function () {
    $owner = Owner::create([
        'name' => 'Failed Owner',
        'email' => 'fail@example.com',
        'password' => bcrypt('password'),
    ]);

    $store = Store::create([
        'name' => 'Fail Store',
        'owner_id' => $owner->id,
    ]);

    $reference = 'PAYMINT_SUB_FAIL_456';

    $checkoutMock = Mockery::mock(Checkout::class);
    $checkoutMock->shouldReceive('verify')
        ->with($reference)
        ->once()
        ->andReturn([
            'status' => 'failed',
            'data' => [
                'status' => 'failed',
                'reference' => $reference,
            ],
        ]);

    PayMint::shouldReceive('checkout')
        ->once()
        ->andReturn($checkoutMock);

    $response = $this->get(route('merchant.billing.callback', [
        'tenant' => $store->public_id,
        'reference' => $reference,
    ]));

    $response->assertSessionMissing('pixel_event');
});
