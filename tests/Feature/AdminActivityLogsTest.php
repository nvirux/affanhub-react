<?php

use App\Filament\Resources\ActivityLogs\Pages\ListActivityLogs;
use App\Models\ActivityLog;
use App\Models\Admin;
use App\Models\Owner;
use App\Models\Store;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = Admin::create([
        'name' => 'Super Admin',
        'email' => 'admin@affanhub.com',
        'password' => bcrypt('password'),
    ]);

    Filament::setCurrentPanel(Filament::getPanel('admin'));
    $this->actingAs($this->admin, 'admin');
});

test('admin can view activity logs with store name instead of store id', function () {
    $owner = Owner::create([
        'name' => 'Demo Owner',
        'email' => 'owner@example.com',
        'password' => bcrypt('password'),
    ]);

    $store = Store::create([
        'name' => 'Adeola VTU Express',
        'public_id' => 'str_adeola',
        'owner_id' => $owner->id,
        'status' => 'active',
    ]);

    ActivityLog::create([
        'tenant_id' => $store->id,
        'event' => 'manual_withdrawal',
        'description' => 'Manual withdrawal requested',
    ]);

    Livewire::test(ListActivityLogs::class)
        ->assertSuccessful()
        ->assertSee('Adeola VTU Express')
        ->assertSee('manual_withdrawal');
});
