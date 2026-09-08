<?php

use App\Filament\Merchant\Pages\StoreSettings;
use App\Models\Feature;
use App\Models\Owner;
use App\Models\Store;
use App\Models\StoreFeatureOverride;
use App\Services\Branding\ColorHelper;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    Storage::fake('public');

    $this->owner = Owner::create([
        'name' => 'Merchant Owner',
        'email' => 'merchant@example.com',
        'password' => bcrypt('password'),
    ]);

    $this->store = Store::create([
        'name' => 'Apex VTU Store',
        'public_id' => 'str_apex123',
        'owner_id' => $this->owner->id,
        'status' => 'active',
    ]);
});

test('color helper validates hex formats correctly', function () {
    expect(ColorHelper::isValidHex('#2563EB'))->toBeTrue()
        ->and(ColorHelper::isValidHex('#10b981'))->toBeTrue()
        ->and(ColorHelper::isValidHex('10B981'))->toBeTrue()
        ->and(ColorHelper::isValidHex('#FFF'))->toBeFalse()
        ->and(ColorHelper::isValidHex('invalid'))->toBeFalse()
        ->and(ColorHelper::isValidHex(''))->toBeFalse()
        ->and(ColorHelper::isValidHex(null))->toBeFalse();
});

test('color helper rejects pure white and ultra-light colors for accessibility', function () {
    expect(ColorHelper::isAllowed('#FFFFFF'))->toBeFalse()
        ->and(ColorHelper::isAllowed('#FEFEFE'))->toBeFalse()
        ->and(ColorHelper::isAllowed('#F8FAFC'))->toBeFalse() // Light gray
        ->and(ColorHelper::isAllowed('#2563EB'))->toBeTrue()  // Sapphire Blue
        ->and(ColorHelper::isAllowed('#10B981'))->toBeTrue()  // Emerald Green
        ->and(ColorHelper::isAllowed('#F97316'))->toBeTrue()  // Sunset Orange
        ->and(ColorHelper::isAllowed('#1E293B'))->toBeTrue(); // Charcoal
});

test('color helper calculates high contrast foreground text correctly', function () {
    // Darker colors should have white text
    expect(ColorHelper::getContrastForeground('#2563EB'))->toBe('#FFFFFF')
        ->and(ColorHelper::getContrastForeground('#1E293B'))->toBe('#FFFFFF')
        ->and(ColorHelper::getContrastForeground('#000000'))->toBe('#FFFFFF')
        ->and(ColorHelper::getContrastForeground('#0D5C3A'))->toBe('#FFFFFF');

    // Bright or yellow/gold colors should have dark slate text
    expect(ColorHelper::getContrastForeground('#FACC15'))->toBe('#0F172A') // Bright yellow
        ->and(ColorHelper::getContrastForeground('#FEF08A'))->toBe('#0F172A');
});

test('color helper correctly matches preset keys', function () {
    expect(ColorHelper::findMatchingPreset('#2563EB'))->toBe('sapphire')
        ->and(ColorHelper::findMatchingPreset('#10B981'))->toBe('emerald')
        ->and(ColorHelper::findMatchingPreset('#F97316'))->toBe('sunset')
        ->and(ColorHelper::findMatchingPreset('#999999'))->toBeNull();
});

test('merchant store settings mounts and saves primary brand color to data column', function () {
    Filament::setCurrentPanel(Filament::getPanel('merchant'));
    $this->actingAs($this->owner, 'owner');
    Filament::setTenant($this->store);

    Livewire::test(StoreSettings::class)
        ->assertSet('primaryColor', '#2563EB')
        ->set('primaryColor', '#10B981')
        ->call('saveSettings')
        ->assertHasNoErrors();

    $this->store->refresh();
    expect($this->store->primary_color)->toBe('#10B981');
});

test('merchant store settings selects preset cleanly', function () {
    Filament::setCurrentPanel(Filament::getPanel('merchant'));
    $this->actingAs($this->owner, 'owner');
    Filament::setTenant($this->store);

    Livewire::test(StoreSettings::class)
        ->call('selectPreset', '#F97316')
        ->assertSet('primaryColor', '#F97316')
        ->call('saveSettings')
        ->assertHasNoErrors();

    $this->store->refresh();
    expect($this->store->primary_color)->toBe('#F97316');
});

test('merchant store settings rejects white brand color with helpful validation error', function () {
    Filament::setCurrentPanel(Filament::getPanel('merchant'));
    $this->actingAs($this->owner, 'owner');
    Filament::setTenant($this->store);

    Livewire::test(StoreSettings::class)
        ->set('primaryColor', '#FFFFFF')
        ->call('saveSettings')
        ->assertHasErrors(['primaryColor']);

    // Assert that the store color was not changed to white
    $this->store->refresh();
    expect($this->store->primary_color)->not->toBe('#FFFFFF');
});

test('merchant store settings switches tabs cleanly', function () {
    Filament::setCurrentPanel(Filament::getPanel('merchant'));
    $this->actingAs($this->owner, 'owner');
    Filament::setTenant($this->store);

    Livewire::test(StoreSettings::class)
        ->assertSet('activeTab', 'profile')
        ->call('setTab', 'branding')
        ->assertSet('activeTab', 'branding')
        ->call('setTab', 'contact')
        ->assertSet('activeTab', 'contact')
        ->call('setTab', 'chat')
        ->assertSet('activeTab', 'chat');
});

test('starter store cannot upload favicon or custom hex without pro plan', function () {
    Filament::setCurrentPanel(Filament::getPanel('merchant'));
    $this->actingAs($this->owner, 'owner');
    Filament::setTenant($this->store);

    $faviconFile = UploadedFile::fake()->create('favicon.png', 10, 'image/png');

    // Starter plan trying to upload favicon
    Livewire::test(StoreSettings::class)
        ->set('favicon', $faviconFile)
        ->call('saveSettings')
        ->assertHasErrors(['favicon']);

    // Starter plan trying to set arbitrary custom hex not in presets
    Livewire::test(StoreSettings::class)
        ->set('primaryColor', '#123456')
        ->call('saveSettings')
        ->assertHasErrors(['primaryColor']);
});

test('merchant with pro custom branding can upload favicon and use custom hex', function () {
    Filament::setCurrentPanel(Filament::getPanel('merchant'));
    $this->actingAs($this->owner, 'owner');
    Filament::setTenant($this->store);

    // Grant custom_branding entitlement
    $feature = Feature::firstOrCreate(
        ['slug' => 'custom_branding'],
        ['name' => 'Custom Branding', 'type' => 'boolean', 'default_value' => 'false']
    );

    StoreFeatureOverride::create([
        'store_id' => $this->store->id,
        'feature_id' => $feature->id,
        'value' => 'true',
    ]);

    $faviconFile = UploadedFile::fake()->create('favicon.png', 10, 'image/png');

    Livewire::test(StoreSettings::class)
        ->set('primaryColor', '#123456')
        ->set('favicon', $faviconFile)
        ->call('saveSettings')
        ->assertHasNoErrors();

    $this->store->refresh();
    expect($this->store->primary_color)->toBe('#123456')
        ->and($this->store->favicon_path)->not->toBeNull();
    Storage::disk('public')->assertExists($this->store->favicon_path);

    // Test removing favicon
    Livewire::test(StoreSettings::class)
        ->call('removeFavicon')
        ->assertHasNoErrors();

    $this->store->refresh();
    expect($this->store->favicon_path)->toBeNull();
});

test('storefront blade view injects tenant css variables into head', function () {
    $this->store->primary_color = '#10B981';
    $this->store->save();

    // Initialize tenancy for this store
    tenancy()->initialize($this->store);

    $view = view('app', [
        'page' => [
            'component' => 'Storefront/Home',
            'props' => [],
            'url' => '/',
            'version' => '',
        ],
    ])->render();

    expect($view)->toContain('<style id="tenant-brand-theme">')
        ->and($view)->toContain('--primary: #10B981 !important;')
        ->and($view)->toContain('--primary-foreground: #FFFFFF !important;')
        ->and($view)->toContain('--color-primary: #10B981 !important;');

    tenancy()->end();
});
