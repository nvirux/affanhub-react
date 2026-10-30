<?php

use App\Models\Domain;
use App\Models\Owner;
use App\Models\Store;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->owner = Owner::create([
        'name' => 'Merchant Owner',
        'email' => 'merchant@redirect-test.com',
        'password' => bcrypt('password'),
    ]);

    $this->store = Store::create([
        'name' => 'Kajiji Data Sub',
        'owner_id' => $this->owner->id,
    ]);

    Domain::create([
        'tenant_id' => $this->store->id,
        'domain' => 'kajijidatasub.com',
        'is_primary' => true,
    ]);
});

test('visiting www custom domain automatically 301 redirects to root domain on home route', function () {
    $response = $this->get('http://www.kajijidatasub.com/');

    $response->assertStatus(301);
    $response->assertRedirect('http://kajijidatasub.com/');
});

test('visiting www custom domain on inner routes automatically 301 redirects to root domain', function () {
    $response = $this->get('http://www.kajijidatasub.com/login');

    $response->assertStatus(301);
    $response->assertRedirect('http://kajijidatasub.com/login');
});

test('visiting an unregistered domain still renders store-not-found 404', function () {
    $response = $this->get('http://unregistered-unknown-store.com/');

    $response->assertStatus(404);
    $response->assertSee('Store Not Found');
});
