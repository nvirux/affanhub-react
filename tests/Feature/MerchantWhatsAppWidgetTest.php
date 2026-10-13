<?php

use App\Filament\Merchant\Widgets\WhatsAppChannelWidget;
use App\Models\Owner;
use App\Models\Store;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->owner = Owner::create([
        'name' => 'Widget Merchant',
        'email' => 'widgetmerchant@example.com',
        'password' => bcrypt('password'),
    ]);

    $this->store = Store::create([
        'name' => 'Widget Store',
        'owner_id' => $this->owner->id,
    ]);

    Filament::setCurrentPanel(Filament::getPanel('merchant'));
    $this->actingAs($this->owner, 'owner');
    Filament::setTenant($this->store);
});

test('whatsapp community widget renders with 7-day dismissable controls', function () {
    Livewire::test(WhatsAppChannelWidget::class)
        ->assertSuccessful()
        ->assertSee('Official WhatsApp Community')
        ->assertSee('Join Community Group')
        ->assertSee('affan_merchant_wa_dismissed_until')
        ->assertSee('Dismiss for 7 days')
        ->assertSee('https://chat.whatsapp.com/BRaczSTbj3fG5CmG3qttf1');
});
