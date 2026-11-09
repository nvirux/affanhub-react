<?php

use App\Models\Domain;
use App\Models\Owner;
use App\Models\Store;
use App\Models\Transaction;
use App\Models\User;
use App\Services\Notification\MerchantNotificationService;
use Filament\Facades\Filament;
use Filament\Notifications\Livewire\DatabaseNotifications;
use Filament\Notifications\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->owner = Owner::create([
        'name' => 'Store Merchant',
        'email' => 'merchant@notiftest.com',
        'password' => bcrypt('password'),
    ]);

    $this->store = Store::create([
        'name' => 'Kajiji Telecom',
        'public_id' => 'str_kajiji',
        'owner_id' => $this->owner->id,
        'status' => 'active',
    ]);

    Filament::setCurrentPanel(Filament::getPanel('merchant'));
    $this->actingAs($this->owner, 'owner');
    Filament::setTenant($this->store);
});

test('merchant panel has database notifications enabled', function () {
    $panel = Filament::getPanel('merchant');

    expect($panel->hasDatabaseNotifications())->toBeTrue();
    expect($panel->getDatabaseNotificationsPollingInterval())->toBe('30s');
});

test('merchant owner can receive and view database notifications in merchant portal', function () {
    // Dispatch a notification to the merchant
    Notification::make()
        ->title('Test Merchant Alert')
        ->body('This is a test notification for the store owner.')
        ->icon('heroicon-o-bell')
        ->iconColor('success')
        ->sendToDatabase($this->owner);

    expect($this->owner->unreadNotifications()->count())->toBe(1);

    $notif = $this->owner->unreadNotifications()->first();
    expect($notif->data['title'])->toBe('Test Merchant Alert');
    expect($notif->data['body'])->toBe('This is a test notification for the store owner.');

    // Test Livewire DatabaseNotifications component
    Livewire::test(DatabaseNotifications::class)
        ->assertSee('Test Merchant Alert')
        ->call('markNotificationAsRead', $notif->id);

    expect($this->owner->fresh()->unreadNotifications()->count())->toBe(0);
});

test('customer wallet funding triggers merchant notification', function () {
    $customer = User::create([
        'name' => 'Bello Sani',
        'email' => 'bello@example.com',
        'phone' => '08033334444',
        'password' => bcrypt('password'),
        'store_id' => $this->store->id,
        'is_active' => true,
    ]);

    app(MerchantNotificationService::class)->notifyCustomerWalletFunded(
        customer: $customer,
        amount: 5000.00,
        reference: 'DEP_123456',
        description: 'Virtual Account Transfer'
    );

    expect($this->owner->unreadNotifications()->count())->toBe(1);

    $notif = $this->owner->unreadNotifications()->first();
    expect($notif->data['title'])->toContain('Customer Wallet Funded');
    expect($notif->data['title'])->toContain('5,000.00');
    expect($notif->data['body'])->toContain('Bello Sani');
    expect($notif->data['body'])->toContain('DEP_123456');
});

test('new customer registration triggers merchant notification', function () {
    $customer = User::create([
        'name' => 'Amina Adamu',
        'email' => 'amina@example.com',
        'phone' => '08055556666',
        'password' => bcrypt('password'),
        'store_id' => $this->store->id,
        'is_active' => true,
    ]);

    app(MerchantNotificationService::class)->notifyNewCustomer($customer);

    expect($this->owner->unreadNotifications()->count())->toBe(1);

    $notif = $this->owner->unreadNotifications()->first();
    expect($notif->data['title'])->toBe('New Customer Registered');
    expect($notif->data['body'])->toContain('Amina Adamu');
    expect($notif->data['body'])->toContain('amina@example.com');
});

test('transaction failure triggers danger alert notification for merchant', function () {
    $customer = User::create([
        'name' => 'Zainab Kabir',
        'email' => 'zainab@example.com',
        'phone' => '08077778888',
        'password' => bcrypt('password'),
        'store_id' => $this->store->id,
        'is_active' => true,
    ]);

    $transaction = Transaction::create([
        'user_id' => $customer->id,
        'store_id' => $this->store->id,
        'reference' => 'TX_FAIL_999',
        'service_type' => 'data',
        'amount' => 1200.00,
        'amount_paid' => 1200.00,
        'cost_price' => 1000.00,
        'profit' => 200.00,
        'recipient' => '08077778888',
        'status' => 'pending',
    ]);

    app(MerchantNotificationService::class)->notifyTransactionFailed(
        store: $this->store,
        transaction: $transaction,
        reason: 'Provider Network Timeout'
    );

    expect($this->owner->unreadNotifications()->count())->toBe(1);

    $notif = $this->owner->unreadNotifications()->first();
    expect($notif->data['title'])->toContain('Transaction Failed');
    expect($notif->data['body'])->toContain('DATA');
    expect($notif->data['body'])->toContain('08077778888');
    expect($notif->data['body'])->toContain('Provider Network Timeout');
});

test('domain verification triggers success notification for merchant', function () {
    $domain = Domain::create([
        'tenant_id' => $this->store->id,
        'domain' => 'kajijidata.ng',
        'is_primary' => true,
    ]);

    app(MerchantNotificationService::class)->notifyDomainVerified(
        store: $this->store,
        domain: $domain
    );

    expect($this->owner->unreadNotifications()->count())->toBe(1);

    $notif = $this->owner->unreadNotifications()->first();
    expect($notif->data['title'])->toBe('Custom Domain Verified!');
    expect($notif->data['body'])->toContain('kajijidata.ng');
});
