<?php

use App\Models\Domain;
use App\Models\Owner;
use App\Models\Store;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

uses(RefreshDatabase::class);

beforeEach(function () {
    Config::set('services.cloudflare.client_id', 'test_client_id');
    Config::set('services.cloudflare.client_secret', 'test_client_secret');
    Config::set('services.cloudflare.redirect_uri', 'http://localhost:8000/merchant/cloudflare/callback');
    Config::set('services.cloudflare.fallback_cname', 'cname.affanhub.com');

    $this->owner = Owner::create([
        'name' => 'Merchant Test',
        'email' => 'merchant@example.com',
        'password' => bcrypt('password'),
    ]);

    $this->store = Store::create([
        'name' => 'Test Store',
        'owner_id' => $this->owner->id,
    ]);
});

test('unauthenticated users are redirected to login when accessing cloudflare connect', function () {
    $domain = Domain::create([
        'tenant_id' => $this->store->id,
        'domain' => 'testcustom.com',
        'is_verified' => false,
        'verification_token' => 'tok_123',
    ]);

    $response = $this->get(route('merchant.cloudflare.connect', ['domain' => $domain->id]));

    $response->assertRedirect(route('filament.merchant.auth.login'));
});

test('merchant owner can initiate cloudflare oauth redirect', function () {
    $domain = Domain::create([
        'tenant_id' => $this->store->id,
        'domain' => 'testcustom.com',
        'is_verified' => false,
        'verification_token' => 'tok_123',
    ]);

    $response = $this->actingAs($this->owner, 'owner')
        ->get(route('merchant.cloudflare.connect', ['domain' => $domain->id]));

    $response->assertRedirect();
    $location = $response->headers->get('Location');
    expect($location)->toContain('dash.cloudflare.com/oauth2/auth');
    expect($location)->toContain('client_id=test_client_id');
    expect($location)->toContain('code_challenge=');
    expect($location)->toContain('code_challenge_method=S256');
});

test('cloudflare callback successfully configures dns and verifies domain', function () {
    $domain = Domain::create([
        'tenant_id' => $this->store->id,
        'domain' => 'example.com',
        'is_verified' => false,
        'verification_token' => 'tok_abc',
    ]);

    $state = 'test_state_12345';
    session(["cf_oauth_{$state}" => [
        'domain_id' => $domain->id,
        'tenant_id' => $this->store->id,
        'tenant_public_id' => $this->store->public_id,
        'owner_id' => $this->owner->id,
        'code_verifier' => 'test_code_verifier_mock',
        'created_at' => now()->timestamp,
    ]]);

    Http::fake([
        'https://dash.cloudflare.com/oauth2/token' => Http::response([
            'access_token' => 'mock_cf_access_token',
        ], 200),
        'https://api.cloudflare.com/client/v4/zones?status=active&per_page=50' => Http::response([
            'result' => [
                ['id' => 'zone_123', 'name' => 'example.com'],
            ],
        ], 200),
        'https://api.cloudflare.com/client/v4/zones/zone_123/dns_records*' => Http::response([
            'result' => [],
        ], 200),
    ]);

    $response = $this->actingAs($this->owner, 'owner')
        ->get(route('merchant.cloudflare.callback', [
            'state' => $state,
            'code' => 'mock_auth_code',
        ]));

    $response->assertRedirect();
    $domain->refresh();
    expect($domain->cloudflare_detected)->toBeTrue();
});
