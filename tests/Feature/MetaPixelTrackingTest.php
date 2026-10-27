<?php

use App\Filament\Merchant\Pages\Auth\Register;
use App\Models\Owner;
use App\Models\Plan;
use App\Models\Store;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Cache;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;
use Livewire\Livewire;
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

test('merchant registration queues CompleteRegistration pixel event in session', function () {
    Filament::setCurrentPanel(Filament::getPanel('merchant'));

    Livewire::test(Register::class)
        ->fillForm([
            'name' => 'Al-Amin Test Owner',
            'email' => 'alamin_pixel@test.com',
            'phone' => '08012345679',
            'password' => 'secret12345',
            'passwordConfirmation' => 'secret12345',
            'terms' => true,
        ])
        ->call('register')
        ->assertHasNoFormErrors();

    expect(session('pixel_event'))->toBe([
        'event' => 'CompleteRegistration',
        'params' => [
            'content_name' => 'Merchant Account Registered',
            'status' => true,
        ],
    ]);
});

test('failed merchant registration does not queue CompleteRegistration pixel event', function () {
    Filament::setCurrentPanel(Filament::getPanel('merchant'));

    Livewire::test(Register::class)
        ->fillForm([
            'name' => 'Al-Amin Test Owner',
            'email' => 'alamin_fail@test.com',
            'phone' => '08012345679',
            'password' => 'secret12345',
            'passwordConfirmation' => 'secret12345',
            'terms' => false,
        ])
        ->call('register')
        ->assertHasFormErrors(['terms' => 'accepted']);

    expect(session('pixel_event'))->toBeNull();
});

test('meta pixel component renders queued event and pulls it so it does not fire twice', function () {
    session()->put('pixel_event', [
        'event' => 'CompleteRegistration',
        'params' => [
            'content_name' => 'Merchant Account Registered',
            'status' => true,
        ],
    ]);

    // First render: must output the track call
    $renderedFirst = Blade::render("@include('filament.merchant.components.meta-pixel')");

    expect($renderedFirst)
        ->toContain("fbq('track', 'CompleteRegistration',")
        ->toContain('"content_name":"Merchant Account Registered"');

    // After first render: session pixel_event has been pulled/consumed
    expect(session('pixel_event'))->toBeNull();

    // Second render (simulating page reload): must NOT output the track call again
    $renderedSecond = Blade::render("@include('filament.merchant.components.meta-pixel')");

    expect($renderedSecond)
        ->not->toContain("fbq('track', 'CompleteRegistration',");
});

test('google oauth signup queues CompleteRegistration event only for brand new owners', function () {
    $socialiteUser = Mockery::mock(SocialiteUser::class);
    $socialiteUser->shouldReceive('getId')->andReturn('google_unique_123');
    $socialiteUser->shouldReceive('getEmail')->andReturn('google_new_merchant@example.com');
    $socialiteUser->shouldReceive('getName')->andReturn('Google Merchant');
    $socialiteUser->shouldReceive('getAvatar')->andReturn(null);

    Socialite::shouldReceive('driver->user')->once()->andReturn($socialiteUser);

    $response = $this->get(route('merchant.google.callback'));

    $response->assertRedirect(route('merchant.phone.complete'));
    expect(session('pixel_event'))->toBe([
        'event' => 'CompleteRegistration',
        'params' => [
            'content_name' => 'Merchant Account Registered (Google OAuth)',
            'status' => true,
        ],
    ]);

    // Existing owner logging in again via Google OAuth must NOT queue CompleteRegistration
    session()->forget('pixel_event');

    Socialite::shouldReceive('driver->user')->once()->andReturn($socialiteUser);

    $responseSecond = $this->get(route('merchant.google.callback'));
    expect(session('pixel_event'))->toBeNull();
});

test('successful subscription checkout verification queues Subscribe meta pixel event', function () {
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
    expect(session('pixel_event'))->toBe([
        'event' => 'Subscribe',
        'params' => [
            'value' => 5000.00,
            'currency' => 'NGN',
            'predicted_ltv' => 5000.00,
            'content_name' => 'Plan: Pro Plan',
        ],
    ]);
});

test('failed subscription checkout verification does not queue Subscribe meta pixel event', function () {
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

    expect(session('pixel_event'))->toBeNull();
});
