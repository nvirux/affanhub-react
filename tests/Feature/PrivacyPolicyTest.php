<?php

use App\Models\Owner;
use App\Models\Store;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->owner = Owner::create([
        'name' => 'Store Owner',
        'email' => 'storeowner@example.com',
        'password' => bcrypt('password'),
    ]);

    $this->store = Store::create([
        'name' => 'Swift Telecom',
        'owner_id' => $this->owner->id,
    ]);

    $this->store->contact_email = 'support@swifttelecom.com';
    $this->store->contact_phone = '08012345678';
    $this->store->save();

    DB::table('domains')->insert([
        'tenant_id' => $this->store->id,
        'domain' => 'swift.localhost',
        'is_primary' => true,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
});

test('any tenant store can access its privacy policy page', function () {
    $response = $this->get('http://swift.localhost/privacy-policy');

    $response->assertOk();
    $response->assertInertia(fn (Assert $page) => $page
        ->component('Storefront/PrivacyPolicy')
        ->has('store')
        ->where('store.name', 'Swift Telecom')
        ->where('store.contact_email', 'support@swifttelecom.com')
        ->where('store.contact_phone', '08012345678')
    );
});

test('tenant privacy redirect redirects to privacy-policy', function () {
    $response = $this->get('http://swift.localhost/privacy');

    $response->assertRedirect('http://swift.localhost/privacy-policy');
});

test('central domain serves privacy policy page', function () {
    $response = $this->get('http://localhost/privacy-policy');

    $response->assertOk();
    $response->assertInertia(fn (Assert $page) => $page
        ->component('Storefront/PrivacyPolicy')
    );
});
