<?php

use App\Filament\Merchant\Pages\MobileAppManager;
use App\Models\Owner;
use App\Models\Store;
use App\Models\StoreMobileApp;
use App\Models\StorePushNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;

uses(RefreshDatabase::class);

test('generates package identifier following com.affanhub convention and ensures uniqueness', function () {
    expect(StoreMobileApp::generatePackageId('Quick VTU Hub'))
        ->toBe('com.affanhub.quickvtuhub')
        ->and(StoreMobileApp::generatePackageId('affan-data-services_123'))
        ->toBe('com.affanhub.affandataservices123')
        ->and(StoreMobileApp::generatePackageId(''))
        ->toBe('com.affanhub.store');

    $owner = Owner::create([
        'name' => 'Store 1 Owner',
        'email' => 'store1@example.com',
        'password' => bcrypt('password'),
    ]);

    $store1 = Store::create([
        'name' => 'Apex Data',
        'public_id' => 'str_apex1',
        'owner_id' => $owner->id,
        'status' => 'active',
    ]);

    StoreMobileApp::create([
        'store_id' => $store1->id,
        'app_name' => 'Apex Data',
        'package_id' => 'com.affanhub.apexdata',
        'version_code' => 1,
        'version_name' => '1.0.0',
        'status' => 'ready',
    ]);

    // Second store with identical name should get an auto-incremented suffix
    $uniquePackageId = StoreMobileApp::generateUniquePackageId('Apex Data');
    expect($uniquePackageId)->toBe('com.affanhub.apexdata1');
});

test('store can have mobile app record and increment versions', function () {
    $owner = Owner::create([
        'name' => 'Apex Merchant',
        'email' => 'apex@example.com',
        'password' => bcrypt('password123'),
    ]);

    $store = Store::create([
        'name' => 'Apex Data',
        'public_id' => 'str_apex99',
        'owner_id' => $owner->id,
        'status' => 'active',
    ]);

    $app = StoreMobileApp::create([
        'store_id' => $store->id,
        'app_name' => 'Apex Data App',
        'package_id' => StoreMobileApp::generatePackageId($store->name),
        'version_code' => 1,
        'version_name' => '1.0.0',
        'status' => 'building',
    ]);

    expect($app->package_id)->toBe('com.affanhub.apexdata');
    expect($app->isBuilding())->toBeTrue();
    expect($app->isReady())->toBeFalse();

    $app->incrementVersion();
    expect($app->version_code)->toBe(2);
    expect($app->version_name)->toBe('1.0.1');

    $app->status = 'ready';
    $app->apk_download_url = 'https://github.com/nvirux/affanhub-mobile-template/releases/download/v1/app-debug.apk';
    $app->save();

    expect($app->isReady())->toBeTrue();
    expect($store->mobileApp->id)->toBe($app->id);
});

test('mobile app manager page renders cleanly without blade errors and supports free generation', function () {
    $owner = Owner::create([
        'name' => 'Demo Merchant',
        'email' => 'demo@example.com',
        'password' => bcrypt('password'),
    ]);

    $store = Store::create([
        'name' => 'Demo Storefront',
        'public_id' => 'str_demo',
        'owner_id' => $owner->id,
        'status' => 'active',
    ]);

    Filament\Facades\Filament::setCurrentPanel(Filament\Facades\Filament::getPanel('merchant'));
    $this->actingAs($owner, 'owner');
    Filament\Facades\Filament::setTenant($store);

    config(['services.github_app_builder.token' => 'mock-token']);
    Http::fake([
        'https://api.github.com/*' => Http::response([], 204),
    ]);

    Livewire\Livewire::test(MobileAppManager::class)
        ->assertSuccessful()
        ->assertSee('App Branding')
        ->assertSee('Branded Android APK')
        ->assertSee('Generate Android App (Free)')
        ->call('generateFreeApp')
        ->assertHasNoErrors();

    $store->refresh();
    expect($store->mobileApp)->not->toBeNull()
        ->and($store->mobileApp->status)->toBe('building')
        ->and($store->mobileApp->price_paid)->toEqual(0.00);
});

test('merchant can broadcast push notification to mobile app customers via onesignal', function () {
    $owner = Owner::create([
        'name' => 'Push Merchant',
        'email' => 'push@example.com',
        'password' => bcrypt('password'),
    ]);

    $store = Store::create([
        'name' => 'Fast VTU',
        'public_id' => 'str_fastvtu',
        'owner_id' => $owner->id,
        'status' => 'active',
    ]);

    StoreMobileApp::create([
        'store_id' => $store->id,
        'app_name' => 'Fast VTU App',
        'package_id' => 'com.affanhub.fastvtu',
        'version_code' => 1,
        'version_name' => '1.0.0',
        'status' => 'ready',
        'apk_download_url' => 'https://media.affanhub.com/apps/1/app.apk',
    ]);

    Filament\Facades\Filament::setCurrentPanel(Filament\Facades\Filament::getPanel('merchant'));
    $this->actingAs($owner, 'owner');
    Filament\Facades\Filament::setTenant($store);

    config([
        'services.onesignal.app_id' => '340f8d56-0123-4030-8756-3c42c9282f1a',
        'services.onesignal.rest_api_key' => 'test-rest-key',
    ]);

    Http::fake([
        'https://onesignal.com/api/v1/notifications' => Http::response([
            'id' => 'onesignal-notif-12345',
            'recipients' => 42,
            'external_id' => null,
        ], 200),
    ]);

    Livewire\Livewire::test(MobileAppManager::class)
        ->assertSuccessful()
        ->assertSee('Broadcast Push Notification')
        ->set('pushTitle', 'Weekend Airtime Bonus!')
        ->set('pushMessage', 'Get an instant 10% bonus on all MTN & Airtel airtime recharges.')
        ->set('pushActionUrl', '/dashboard/airtime')
        ->call('sendPushNotification')
        ->assertHasNoErrors();

    Http::assertSent(function (Request $request) use ($store) {
        $data = $request->data();

        return $request->url() === 'https://onesignal.com/api/v1/notifications'
            && $data['app_id'] === '340f8d56-0123-4030-8756-3c42c9282f1a'
            && $data['headings']['en'] === 'Weekend Airtime Bonus!'
            && $data['contents']['en'] === 'Get an instant 10% bonus on all MTN & Airtel airtime recharges.'
            && $data['filters'][0]['key'] === 'store_id'
            && $data['filters'][0]['value'] === (string) $store->id;
    });

    $notification = StorePushNotification::where('store_id', $store->id)->first();
    expect($notification)->not->toBeNull()
        ->and($notification->title)->toBe('Weekend Airtime Bonus!')
        ->and($notification->recipients_count)->toBe(42)
        ->and($notification->status)->toBe('sent')
        ->and($notification->onesignal_id)->toBe('onesignal-notif-12345');
});
