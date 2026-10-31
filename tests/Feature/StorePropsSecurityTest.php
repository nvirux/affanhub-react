<?php

use App\Models\Domain;
use App\Models\Owner;
use App\Models\Store;
use App\Models\User;
use App\Models\VirtualAccount;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->owner = Owner::create([
        'name' => 'Store Owner',
        'email' => 'owner@security-props-test.com',
        'password' => bcrypt('password'),
    ]);

    $this->store = Store::create([
        'name' => 'Demo Security Store',
        'owner_id' => $this->owner->id,
        'resend_api_key' => 'secret_re_123456789_leaked_test',
        'resend_from_email' => 'admin@secretstore.com',
        'resend_from_name' => 'Secret Admin',
    ]);

    Domain::create([
        'tenant_id' => $this->store->id,
        'domain' => 'secure-store.test',
        'is_primary' => true,
    ]);
});

test('store model toArray hides sensitive credentials', function () {
    $array = $this->store->toArray();

    expect($array)->not->toHaveKey('resend_api_key')
        ->and($array)->not->toHaveKey('resend_from_email')
        ->and($array)->not->toHaveKey('resend_from_name')
        ->and($array)->not->toHaveKey('owner_id')
        ->and($array)->not->toHaveKey('data');
});

test('storefront landing page props do not leak api keys, internal domains, or database ids', function () {
    $response = $this->get('http://secure-store.test/');

    $response->assertStatus(200);

    $page = $response->viewData('page');
    $storeProp = $page['props']['store'];

    expect($storeProp)->not->toHaveKey('resend_api_key')
        ->and($storeProp)->not->toHaveKey('resend_from_email')
        ->and($storeProp)->not->toHaveKey('resend_from_name')
        ->and($storeProp)->not->toHaveKey('owner_id')
        ->and($storeProp)->not->toHaveKey('domains')
        ->and($storeProp)->not->toHaveKey('id')
        ->and($storeProp)->not->toHaveKey('public_id')
        ->and($storeProp)->toHaveKey('name')
        ->and($storeProp)->toHaveKey('primary_color')
        ->and($storeProp)->toHaveKey('logo_url');
});

test('virtual account model toArray hides internal columns', function () {
    $va = new VirtualAccount([
        'holder_type' => 'App\\Models\\User',
        'holder_id' => 1,
        'bank_name' => 'Palmpay',
        'account_number' => '1234567890',
        'account_name' => 'John Doe',
        'email_alias' => 'alias@va.test',
        'provider' => 'paymint',
        'status' => 'active',
        'reference' => 'ref_123',
        'meta' => ['secret' => 'provider_data'],
    ]);

    $array = $va->toArray();

    expect($array)->not->toHaveKey('holder_type')
        ->and($array)->not->toHaveKey('holder_id')
        ->and($array)->not->toHaveKey('email_alias')
        ->and($array)->not->toHaveKey('provider')
        ->and($array)->not->toHaveKey('reference')
        ->and($array)->not->toHaveKey('meta')
        ->and($array)->toHaveKey('bank_name')
        ->and($array)->toHaveKey('account_number')
        ->and($array)->toHaveKey('account_name');
});

test('user model toArray hides nin and bvn alongside credentials', function () {
    $user = new User([
        'name' => 'Test User',
        'email' => 'user@test.com',
        'nin' => '12345678901',
        'bvn' => '98765432109',
        'password' => 'secret',
    ]);

    $array = $user->toArray();

    expect($array)->not->toHaveKey('nin')
        ->and($array)->not->toHaveKey('bvn')
        ->and($array)->not->toHaveKey('password')
        ->and($array)->toHaveKey('name')
        ->and($array)->toHaveKey('email');
});
