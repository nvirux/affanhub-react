<?php

use App\Models\Owner;
use App\Models\Service;
use App\Models\Store;
use App\Models\User;
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
        'name' => 'Demo Telecom',
        'owner_id' => $this->owner->id,
    ]);

    DB::table('domains')->insert([
        'tenant_id' => $this->store->id,
        'domain' => 'demo.localhost',
        'is_primary' => true,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $this->user = User::factory()->create([
        'store_id' => $this->store->id,
    ]);

    // Seed test services
    Service::firstOrCreate(['key' => 'airtime'], [
        'name' => 'Airtime Topup',
        'category' => 'vtu',
        'icon' => 'Smartphone',
        'sort_order' => 1,
        'is_active' => true,
    ]);

    Service::firstOrCreate(['key' => 'data'], [
        'name' => 'Data Bundle',
        'category' => 'vtu',
        'icon' => 'Wifi',
        'sort_order' => 2,
        'is_active' => true,
    ]);

    Service::firstOrCreate(['key' => 'nin_verification'], [
        'name' => 'NIN Verification',
        'category' => 'identity',
        'icon' => 'IdCard',
        'sort_order' => 3,
        'is_active' => true,
    ]);
});

test('authenticated user can view /services page with active services', function () {
    $response = $this->actingAs($this->user)
        ->get('http://demo.localhost/services');

    $response->assertOk();
    $response->assertInertia(fn (Assert $page) => $page
        ->component('Storefront/Services')
        ->has('services', 3)
        ->where('services.0.key', 'airtime')
        ->where('services.1.key', 'data')
        ->where('services.2.key', 'nin_verification')
    );
});

test('unauthenticated guest is redirected to login from /services', function () {
    $response = $this->get('http://demo.localhost/services');

    $response->assertRedirect('/login');
});
