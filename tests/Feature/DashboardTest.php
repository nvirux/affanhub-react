<?php

use App\Models\Owner;
use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

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
});

test('guests are redirected to the login page on tenant domain', function () {
    $response = $this->get('http://demo.localhost/dashboard');
    $response->assertRedirect('http://demo.localhost/login');
});

test('authenticated users can visit the dashboard', function () {
    $this->actingAs($this->user);

    $response = $this->get('http://demo.localhost/dashboard');
    $response->assertOk();
});
