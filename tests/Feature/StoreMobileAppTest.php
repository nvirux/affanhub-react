<?php

use App\Filament\Merchant\Pages\MobileAppManager;
use App\Models\Owner;
use App\Models\Store;
use App\Models\StoreMobileApp;
use Illuminate\Foundation\Testing\RefreshDatabase;

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

test('mobile app manager page renders cleanly without blade errors', function () {
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

    Livewire\Livewire::test(MobileAppManager::class)
        ->assertSuccessful()
        ->assertSee('App Customization')
        ->assertSee('Official Signed Production Build');
});
