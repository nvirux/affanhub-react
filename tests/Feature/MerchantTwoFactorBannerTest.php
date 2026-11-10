<?php

use App\Models\Owner;
use App\Models\Store;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->owner = Owner::create([
        'name' => 'Security Minded Merchant',
        'email' => 'merchant2fa@example.com',
        'password' => bcrypt('password'),
    ]);

    $this->store = Store::create([
        'name' => 'Security Demo Store',
        'public_id' => 'str_sec999',
        'owner_id' => $this->owner->id,
        'status' => 'active',
    ]);
});

test('two-factor security banner is rendered when merchant has not set up 2FA', function () {
    Filament::setCurrentPanel(Filament::getPanel('merchant'));
    $this->actingAs($this->owner, 'owner');
    Filament::setTenant($this->store);

    $html = view('filament.merchant.components.two-factor-banner')->render();

    expect($html)
        ->toContain('Setup Two-Factor Authenticator')
        ->toContain('Adds an extra layer of security to your account to protect you from unauthorised transactions.')
        ->toContain('Setup 2FA Now');
});

test('two-factor security banner is hidden when merchant has already set up 2FA', function () {
    $this->owner->update([
        'app_authentication_secret' => 'encrypted_secret_123',
    ]);

    Filament::setCurrentPanel(Filament::getPanel('merchant'));
    $this->actingAs($this->owner, 'owner');
    Filament::setTenant($this->store);

    expect($this->owner->hasAppAuthentication())->toBeTrue();

    $html = view('filament.merchant.components.two-factor-banner')->render();

    expect($html)->toBeEmpty();
});

test('two-factor security banner is hidden when super admin is impersonating', function () {
    Filament::setCurrentPanel(Filament::getPanel('merchant'));
    $this->actingAs($this->owner, 'owner');
    Filament::setTenant($this->store);

    session(['impersonated_by_admin' => 1]);

    $twoFaHtml = view('filament.merchant.components.two-factor-banner')->render();
    expect($twoFaHtml)->toBeEmpty();

    $impHtml = view('filament.merchant.components.impersonation-banner')->render();
    expect($impHtml)
        ->toContain('Super Admin Impersonation')
        ->toContain('Exit')
        ->toContain(route('impersonate.leave'));
});

test('impersonation banner is empty when not impersonating', function () {
    session()->forget('impersonated_by_admin');

    $html = view('filament.merchant.components.impersonation-banner')->render();
    expect($html)->toBeEmpty();
});
