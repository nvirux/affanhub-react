<?php

use App\Models\Owner;
use App\Models\Store;
use App\Models\StoreAnnouncement;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->owner = Owner::create([
        'name' => 'Test Owner',
        'email' => 'owner@announcement.test',
        'password' => bcrypt('password'),
    ]);
    $this->store = Store::create([
        'name' => 'Announcement Test Store',
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

test('store announcement model scopes active announcements correctly', function () {
    // 1. Inactive announcement
    StoreAnnouncement::create([
        'store_id' => $this->store->id,
        'title' => 'Paused Notice',
        'message' => 'This is paused',
        'is_active' => false,
    ]);

    // 2. Expired announcement
    StoreAnnouncement::create([
        'store_id' => $this->store->id,
        'title' => 'Expired Notice',
        'message' => 'This was yesterday',
        'is_active' => true,
        'expires_at' => now()->subHour(),
    ]);

    // 3. Future scheduled announcement
    StoreAnnouncement::create([
        'store_id' => $this->store->id,
        'title' => 'Future Notice',
        'message' => 'This is tomorrow',
        'is_active' => true,
        'starts_at' => now()->addDay(),
    ]);

    // Currently active query should be null
    expect(StoreAnnouncement::getActiveForStore($this->store))->toBeNull();

    // 4. Create currently active announcement
    $active = StoreAnnouncement::create([
        'store_id' => $this->store->id,
        'title' => 'Live Alert',
        'message' => 'MTN delay from provider',
        'type' => 'warning',
        'display_style' => 'modal',
        'action_label' => 'Buy Corporate',
        'action_url' => '/vtu/data',
        'is_active' => true,
        'starts_at' => now()->subMinutes(10),
        'expires_at' => now()->addHours(2),
    ]);

    $retrieved = StoreAnnouncement::getActiveForStore($this->store);
    expect($retrieved)->not->toBeNull();
    expect($retrieved->id)->toEqual($active->id);
    expect($retrieved->title)->toEqual('Live Alert');
    expect($retrieved->display_style)->toEqual('modal');
});

test('active store announcement is shared with storefront via inertia', function () {
    StoreAnnouncement::create([
        'store_id' => $this->store->id,
        'title' => 'Popup Welcome Promo',
        'message' => 'Get 10% bonus today only!',
        'type' => 'promo',
        'display_style' => 'modal',
        'action_label' => 'Claim Bonus',
        'action_url' => '/wallet',
        'is_active' => true,
    ]);

    $response = $this->actingAs($this->user)
        ->get('http://demo.localhost/dashboard');

    $response->assertOk();
    $response->assertInertia(fn ($page) => $page
        ->component('Storefront/Dashboard')
        ->has('store.announcement', fn ($ann) => $ann
            ->where('title', 'Popup Welcome Promo')
            ->where('text', 'Get 10% bonus today only!')
            ->where('type', 'promo')
            ->where('style', 'modal')
            ->where('action_label', 'Claim Bonus')
            ->where('action_url', '/wallet')
            ->etc()
        )
    );
});
