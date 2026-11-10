<?php

use App\Models\Owner;
use App\Models\Store;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->owner = Owner::create([
        'name' => 'Support Demo Merchant',
        'email' => 'merchant-support@example.com',
        'password' => bcrypt('password'),
    ]);

    $this->store = Store::create([
        'name' => 'Support Store',
        'public_id' => 'str_supp123',
        'owner_id' => $this->owner->id,
        'status' => 'active',
    ]);
});

test('header support menu component renders all support channels correctly', function () {
    Filament::setCurrentPanel(Filament::getPanel('merchant'));
    $this->actingAs($this->owner, 'owner');
    Filament::setTenant($this->store);

    $html = view('filament.merchant.components.header-support-menu')->render();

    expect($html)
        ->toContain('fi-topbar-support-btn')
        ->toContain('Help & Support')
        ->toContain('Talk to Support (WhatsApp)')
        ->toContain('https://wa.me/2347011507950')
        ->toContain('07011507950')
        ->toContain('WhatsApp Community Group')
        ->toContain('https://chat.whatsapp.com/L12iIqD89F82T04aD02yXN')
        ->toContain('Help & FAQs')
        ->toContain('help-faq')
        ->toContain('Guides & Tutorials')
        ->toContain('mailto:support@affanhub.com');
});
