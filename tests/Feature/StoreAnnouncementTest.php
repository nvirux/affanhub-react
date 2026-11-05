<?php

use App\Filament\Merchant\Resources\StoreAnnouncementResource\Pages\ListStoreAnnouncements;
use App\Models\Owner;
use App\Models\Store;
use App\Models\StoreAnnouncement;
use App\Models\StoreMobileApp;
use App\Models\StorePushNotification;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

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

test('merchant announcements page renders with storefront notices and mobile push tabs', function () {
    Filament\Facades\Filament::setCurrentPanel(Filament\Facades\Filament::getPanel('merchant'));
    $this->actingAs($this->owner, 'owner');
    Filament\Facades\Filament::setTenant($this->store);

    Livewire\Livewire::test(ListStoreAnnouncements::class)
        ->assertSuccessful()
        ->assertSee('Storefront Notices')
        ->assertSee('App Push Notifications')
        ->set('activeTab', 'push')
        ->assertSee('Compose App Broadcast')
        ->assertSee('Live Phone Preview');
});

test('merchant can broadcast mobile push notification directly from announcements page', function () {
    Filament\Facades\Filament::setCurrentPanel(Filament\Facades\Filament::getPanel('merchant'));
    $this->actingAs($this->owner, 'owner');
    Filament\Facades\Filament::setTenant($this->store);

    StoreMobileApp::create([
        'store_id' => $this->store->id,
        'app_name' => 'Announcement App',
        'package_id' => 'com.affanhub.announcementapp',
        'version_code' => 1,
        'version_name' => '1.0.0',
        'status' => 'ready',
        'apk_download_url' => 'https://media.affanhub.com/apps/str_ann/app.apk',
    ]);

    config([
        'services.onesignal.app_id' => '340f8d56-0123-4030-8756-3c42c9282f1a',
        'services.onesignal.rest_api_key' => 'test-rest-key',
    ]);

    Http::fake([
        'https://onesignal.com/api/v1/notifications' => Http::response([
            'id' => 'onesignal-announcement-notif-1',
            'recipients' => 15,
        ], 200),
    ]);

    Livewire\Livewire::test(ListStoreAnnouncements::class)
        ->set('activeTab', 'push')
        ->set('pushTitle', 'Flash Sale on Airtime!')
        ->set('pushMessage', 'Get 5% instant cashback now.')
        ->set('destinationPreset', 'airtime')
        ->call('sendBroadcast')
        ->assertHasNoErrors();

    $notification = StorePushNotification::where('store_id', $this->store->id)->first();
    expect($notification)->not->toBeNull()
        ->and($notification->title)->toBe('Flash Sale on Airtime!')
        ->and($notification->recipients_count)->toBe(15)
        ->and($notification->status)->toBe('sent');
});
