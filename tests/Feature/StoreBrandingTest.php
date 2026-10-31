<?php

use App\Filament\Merchant\Pages\StoreSettings;
use App\Http\Controllers\StorefrontController;
use App\Models\Feature;
use App\Models\Owner;
use App\Models\Store;
use App\Models\StoreFeatureOverride;
use App\Services\Branding\ColorHelper;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Inertia\Response;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    Storage::fake('public');
    Storage::fake(config('filesystems.default'));

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
        ->call('setTab', 'email')
        ->assertSet('activeTab', 'email');
});

test('starter store can upload favicon but cannot use custom hex without pro plan', function () {
    Filament::setCurrentPanel(Filament::getPanel('merchant'));
    $this->actingAs($this->owner, 'owner');
    Filament::setTenant($this->store);

    $faviconFile = UploadedFile::fake()->create('favicon.png', 10, 'image/png');

    // Starter plan can upload favicon
    Livewire::test(StoreSettings::class)
        ->set('favicon', $faviconFile)
        ->call('saveSettings')
        ->assertHasNoErrors();

    $this->store->refresh();
    expect($this->store->favicon_path)->not->toBeNull();

    // Starter plan trying to set arbitrary custom hex not in presets requires Pro
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
    Storage::disk(config('filesystems.default'))->assertExists($this->store->favicon_path);

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
            'component' => 'Storefront/Themes/Default',
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

test('merchant can choose among multiple landing page templates in store settings', function () {
    Filament::setCurrentPanel(Filament::getPanel('merchant'));
    $this->actingAs($this->owner, 'owner');
    Filament::setTenant($this->store);

    Livewire::test(StoreSettings::class)
        ->assertSet('landingPageTemplate', 'default')
        ->call('selectTemplate', 'cobalt')
        ->assertSet('landingPageTemplate', 'cobalt')
        ->call('saveSettings')
        ->assertHasNoErrors();

    $this->store->refresh();
    expect($this->store->landing_page_template)->toBe('cobalt');

    // Switch to vantage
    Livewire::test(StoreSettings::class)
        ->call('selectTemplate', 'vantage')
        ->call('saveSettings')
        ->assertHasNoErrors();

    $this->store->refresh();
    expect($this->store->landing_page_template)->toBe('vantage');

    // Switch to refract
    Livewire::test(StoreSettings::class)
        ->call('selectTemplate', 'refract')
        ->call('saveSettings')
        ->assertHasNoErrors();

    // Switch to yushpay
    Livewire::test(StoreSettings::class)
        ->call('selectTemplate', 'yushpay')
        ->call('saveSettings')
        ->assertHasNoErrors();

    $this->store->refresh();
    expect($this->store->landing_page_template)->toBe('yushpay');
});

test('storefront routes to appropriate theme when merchant selects template', function () {
    // Yushpay
    $this->store->landing_page_template = 'yushpay';
    $this->store->save();

    tenancy()->initialize($this->store);
    $responseYushpay = (new StorefrontController)->index(request());
    $property = (new ReflectionClass($responseYushpay))->getProperty('component');
    $property->setAccessible(true);
    expect($property->getValue($responseYushpay))->toBe('Storefront/Themes/Default');

    // Cobalt
    $this->store->landing_page_template = 'cobalt';
    $this->store->save();

    $response = (new StorefrontController)->index(request());
    expect($response)->toBeInstanceOf(Response::class);
    expect($property->getValue($response))->toBe('Storefront/Themes/Cobalt');

    // Vantage
    $this->store->landing_page_template = 'vantage';
    $this->store->save();
    $responseVantage = (new StorefrontController)->index(request());
    expect($property->getValue($responseVantage))->toBe('Storefront/Themes/Vantage');

    // Refract
    $this->store->landing_page_template = 'refract';
    $this->store->save();
    $responseRefract = (new StorefrontController)->index(request());
    expect($property->getValue($responseRefract))->toBe('Storefront/Themes/Refract');

    // Default (modern hub theme)
    $this->store->landing_page_template = 'default';
    $this->store->save();
    $responseDefault = (new StorefrontController)->index(request());
    expect($property->getValue($responseDefault))->toBe('Storefront/Themes/Default');

    // Classic / Legacy / v1 (renders Classic multi-section theme)
    $this->store->landing_page_template = 'classic';
    $this->store->save();
    $responseClassic = (new StorefrontController)->index(request());
    expect($property->getValue($responseClassic))->toBe('Storefront/Themes/Classic');

    tenancy()->end();
});

test('merchant can save primary and secondary contact phone numbers and whatsapp settings', function () {
    Filament::setCurrentPanel(Filament::getPanel('merchant'));
    $this->actingAs($this->owner, 'owner');
    Filament::setTenant($this->store);

    Livewire::test(StoreSettings::class)
        ->set('contactPhone', '08101360986')
        ->set('contactPhone2', '09061730442')
        ->set('whatsappChatPhone', '09025293759')
        ->set('whatsappChatMessage', 'Hello from test')
        ->set('socialWhatsapp', 'https://chat.whatsapp.com/testgroup')
        ->call('saveSettings')
        ->assertHasNoErrors();

    $this->store->refresh();
    expect($this->store->contact_phone)->toBe('08101360986')
        ->and($this->store->contact_phone_2)->toBe('09061730442')
        ->and($this->store->whatsapp_chat_phone)->toBe('09025293759')
        ->and($this->store->whatsapp_chat_enabled)->toBeTrue()
        ->and($this->store->whatsapp_chat_message)->toBe('Hello from test')
        ->and($this->store->social_whatsapp)->toBe('https://chat.whatsapp.com/testgroup');
});
