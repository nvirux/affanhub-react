<?php

use App\Models\Domain;
use App\Models\Owner;
use App\Models\Store;
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

test('storefront landing page props do not leak api keys or internal domains', function () {
    $response = $this->get('http://secure-store.test/');

    $response->assertStatus(200);

    $page = $response->viewData('page');
    $storeProp = $page['props']['store'];

    expect($storeProp)->not->toHaveKey('resend_api_key')
        ->and($storeProp)->not->toHaveKey('resend_from_email')
        ->and($storeProp)->not->toHaveKey('resend_from_name')
        ->and($storeProp)->not->toHaveKey('owner_id')
        ->and($storeProp)->not->toHaveKey('domains')
        ->and($storeProp)->toHaveKey('name')
        ->and($storeProp)->toHaveKey('primary_color')
        ->and($storeProp)->toHaveKey('logo_url');
});
