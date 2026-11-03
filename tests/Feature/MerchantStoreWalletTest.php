<?php

use App\Filament\Merchant\Pages\StoreWallet;
use App\Models\Owner;
use App\Models\Plan;
use App\Models\Store;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->owner = Owner::create([
        'name' => 'Wallet Merchant',
        'email' => 'walletmerchant@example.com',
        'password' => bcrypt('password'),
    ]);

    $this->store = Store::create([
        'name' => 'Wallet Store',
        'owner_id' => $this->owner->id,
    ]);

    Plan::firstOrCreate(
        ['slug' => 'starter'],
        [
            'name' => 'Starter',
            'price_monthly' => 0.00,
            'is_active' => true,
        ]
    );

    Filament::setCurrentPanel(Filament::getPanel('merchant'));
    $this->actingAs($this->owner, 'owner');
    Filament::setTenant($this->store);
});

test('merchant can access store wallet page successfully', function () {
    Livewire::test(StoreWallet::class)
        ->assertSuccessful()
        ->assertSee('Wallet');
});
