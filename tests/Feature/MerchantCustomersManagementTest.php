<?php

use App\Filament\Merchant\Resources\CustomerResource\Pages\ListCustomers;
use App\Mail\CustomerDirectMessageMail;
use App\Models\Owner;
use App\Models\Store;
use App\Models\User;
use App\Models\VirtualAccount;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->owner = Owner::create([
        'name' => 'Store Merchant',
        'email' => 'merchant@customerstest.com',
        'password' => bcrypt('password'),
    ]);

    $this->store = Store::create([
        'name' => 'Apex Hub Store',
        'public_id' => 'str_apexhub',
        'owner_id' => $this->owner->id,
        'status' => 'active',
    ]);

    Filament::setCurrentPanel(Filament::getPanel('merchant'));
    $this->actingAs($this->owner, 'owner');
    Filament::setTenant($this->store);
});

test('merchant can view customers list with tabs for funded, zero balance, and suspended', function () {
    // 1. Funded Customer
    $fundedUser = User::create([
        'name' => 'Funded Customer',
        'email' => 'funded@example.com',
        'phone' => '08011112222',
        'password' => bcrypt('password'),
        'store_id' => $this->store->id,
        'is_active' => true,
    ]);
    $fundedUser->wallet('main')->update(['balance' => 2500.00]);

    // 2. Zero Balance Customer
    $zeroUser = User::create([
        'name' => 'Zero Customer',
        'email' => 'zero@example.com',
        'phone' => '08022223333',
        'password' => bcrypt('password'),
        'store_id' => $this->store->id,
        'is_active' => true,
    ]);

    // 3. Suspended Customer
    $suspendedUser = User::create([
        'name' => 'Suspended Customer',
        'email' => 'banned@example.com',
        'phone' => '08033334444',
        'password' => bcrypt('password'),
        'store_id' => $this->store->id,
        'is_active' => false,
    ]);

    VirtualAccount::create([
        'holder_type' => User::class,
        'holder_id' => $fundedUser->id,
        'bank_name' => 'Wema Bank',
        'account_number' => '1234567890',
        'account_name' => 'Apex - Funded Customer',
        'provider' => 'monnify',
        'status' => 'active',
        'reference' => 'ref_apex_01',
    ]);

    Livewire::test(ListCustomers::class)
        ->assertSuccessful()
        ->assertSee('Funded Customer')
        ->assertSee('Zero Customer')
        ->assertSee('Suspended Customer')
        ->assertSee('Wema Bank: 1234567890')
        // Funded tab
        ->set('activeTab', 'funded')
        ->assertSee('Funded Customer')
        ->assertDontSee('Zero Customer')
        // Zero balance tab
        ->set('activeTab', 'zero_balance')
        ->assertSee('Zero Customer')
        ->assertDontSee('Funded Customer')
        // Suspended tab
        ->set('activeTab', 'suspended')
        ->assertSee('Suspended Customer')
        ->assertDontSee('Funded Customer');
});

test('merchant can credit and debit customer wallet from more actions dropdown', function () {
    $user = User::create([
        'name' => 'Wallet Customer',
        'email' => 'wallet@example.com',
        'phone' => '08055556666',
        'password' => bcrypt('password'),
        'store_id' => $this->store->id,
        'is_active' => true,
    ]);

    Livewire::test(ListCustomers::class)
        ->callTableAction('fund_wallet', $user, [
            'operation' => 'credit',
            'amount' => 1000,
            'reason' => 'Cash received in shop',
        ])
        ->assertHasNoTableActionErrors();

    expect((float) $user->wallet('main')->fresh()->balance)->toBe(1000.00);

    Livewire::test(ListCustomers::class)
        ->callTableAction('fund_wallet', $user, [
            'operation' => 'debit',
            'amount' => 400,
            'reason' => 'Manual reversal',
        ])
        ->assertHasNoTableActionErrors();

    expect((float) $user->wallet('main')->fresh()->balance)->toBe(600.00);
});

test('merchant can ban and reactivate customer', function () {
    $user = User::create([
        'name' => 'Target Customer',
        'email' => 'target@example.com',
        'phone' => '08077778888',
        'password' => bcrypt('password'),
        'store_id' => $this->store->id,
        'is_active' => true,
    ]);

    // Ban
    Livewire::test(ListCustomers::class)
        ->callTableAction('toggle_ban', $user)
        ->assertHasNoTableActionErrors();

    expect($user->fresh()->isActive())->toBeFalse();

    // Reactivate
    Livewire::test(ListCustomers::class)
        ->callTableAction('toggle_ban', $user)
        ->assertHasNoTableActionErrors();

    expect($user->fresh()->isActive())->toBeTrue();
});

test('merchant can send direct email to customer', function () {
    Mail::fake();

    $user = User::create([
        'name' => 'Email Customer',
        'email' => 'customer@example.com',
        'phone' => '08099990000',
        'password' => bcrypt('password'),
        'store_id' => $this->store->id,
        'is_active' => true,
    ]);

    Livewire::test(ListCustomers::class)
        ->callTableAction('send_email', $user, [
            'subject' => 'Weekend Promotion',
            'body' => 'Enjoy 10% bonus data this weekend on our store!',
        ])
        ->assertHasNoTableActionErrors();

    Mail::assertSent(CustomerDirectMessageMail::class, function ($mail) use ($user) {
        return $mail->hasTo($user->email) && $mail->customSubject === 'Weekend Promotion';
    });
});
