<?php

use App\Filament\Merchant\Pages\StoreWallet;
use App\Filament\Resources\SettlementAccounts\Pages\ListSettlementAccounts;
use App\Filament\Resources\Withdrawals\Pages\ListWithdrawals;
use App\Mail\AdminSettlementAccountSubmittedMail;
use App\Mail\AdminWithdrawalRequestedMail;
use App\Models\Admin;
use App\Models\Owner;
use App\Models\SettlementAccount;
use App\Models\Store;
use App\Models\Withdrawal;
use App\Services\WalletService;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = Admin::create([
        'name' => 'Super Admin',
        'email' => 'admin@affanhub.com',
        'password' => bcrypt('password'),
    ]);

    $this->owner = Owner::create([
        'name' => 'John Doe Merchant',
        'email' => 'johndoe@example.com',
        'password' => bcrypt('password'),
    ]);

    $this->store = Store::create([
        'name' => 'Apex Telecom Store',
        'public_id' => 'str_apex123',
        'owner_id' => $this->owner->id,
        'status' => 'active',
    ]);
});

test('admin can see fee, net amount, ref code and view action in withdrawals list', function () {
    Filament::setCurrentPanel(Filament::getPanel('admin'));
    $this->actingAs($this->admin, 'admin');

    Withdrawal::create([
        'store_id' => $this->store->id,
        'amount' => 5000.00,
        'fee' => 100.00,
        'net_amount' => 4900.00,
        'bank_name' => 'Access Bank',
        'account_number' => '0123456789',
        'account_name' => 'Apex Telecom Enterprises',
        'status' => 'pending',
        'reference' => 'WTH_TEST12345',
    ]);

    Livewire::test(ListWithdrawals::class)
        ->assertSuccessful()
        ->assertSee('WTH_TEST12345')
        ->assertSee('Apex Telecom Store')
        ->assertSee('5,000.00')
        ->assertSee('100.00')
        ->assertSee('4,900.00')
        ->assertSee('Access Bank')
        ->assertSee('0123456789')
        ->assertTableActionExists('view');
});

test('admin can see view action in settlement accounts list', function () {
    Filament::setCurrentPanel(Filament::getPanel('admin'));
    $this->actingAs($this->admin, 'admin');

    SettlementAccount::create([
        'store_id' => $this->store->id,
        'bank_name' => 'Zenith Bank',
        'account_number' => '1002003004',
        'account_name' => 'Apex Store Global',
        'status' => 'pending',
        'is_active' => true,
    ]);

    Livewire::test(ListSettlementAccounts::class)
        ->assertSuccessful()
        ->assertSee('Apex Telecom Store')
        ->assertSee('Zenith Bank')
        ->assertSee('1002003004')
        ->assertTableActionExists('view');
});

test('merchant withdrawal request sends email and in-app database notification to super admins', function () {
    Mail::fake();

    // Credit profit wallet so merchant can request payout
    $walletService = app(WalletService::class);
    $profitWallet = $this->store->profitWallet();
    $walletService->credit($profitWallet, 10000.00, 'test_profit', 'Test Profit Earnings');

    // Create an approved settlement account for store
    SettlementAccount::create([
        'store_id' => $this->store->id,
        'bank_name' => 'First Bank',
        'account_number' => '3099998888',
        'account_name' => 'Apex Store Enterprise',
        'status' => 'approved',
        'is_active' => true,
    ]);

    Filament::setCurrentPanel(Filament::getPanel('merchant'));
    $this->actingAs($this->owner, 'owner');
    Filament::setTenant($this->store);

    Livewire::test(StoreWallet::class)
        ->set('withdrawalAmount', 5000)
        ->call('requestProfitWithdrawal')
        ->assertHasNoErrors();

    // Verify Mail was queued to Admin
    Mail::assertQueued(AdminWithdrawalRequestedMail::class, function ($mail) {
        return $mail->hasTo('admin@affanhub.com')
            && $mail->withdrawal->amount == 5000.00
            && $mail->withdrawal->net_amount == 4900.00;
    });

    // Verify in-app database notification exists for admin
    $notification = $this->admin->notifications()->first();
    expect($notification)->not->toBeNull()
        ->and($notification->data['title'])->toContain('New Payout Request')
        ->and($notification->data['body'])->toContain('4,900.00');
});

test('merchant settlement account submission sends email and in-app database notification to super admins', function () {
    Mail::fake();

    Filament::setCurrentPanel(Filament::getPanel('merchant'));
    $this->actingAs($this->owner, 'owner');
    Filament::setTenant($this->store);

    Livewire::test(StoreWallet::class)
        ->set('settlementBankName', 'Guaranty Trust Bank')
        ->set('settlementAccountNumber', '0112233445')
        ->set('settlementAccountName', 'John Apex Ventures')
        ->call('saveSettlementAccount')
        ->assertHasNoErrors();

    // Verify Mail was queued to Admin
    Mail::assertQueued(AdminSettlementAccountSubmittedMail::class, function ($mail) {
        return $mail->hasTo('admin@affanhub.com')
            && $mail->account->bank_name === 'Guaranty Trust Bank'
            && $mail->account->account_number === '0112233445';
    });

    // Verify in-app database notification exists for admin
    $notification = $this->admin->notifications()->first();
    expect($notification)->not->toBeNull()
        ->and($notification->data['title'])->toContain('Settlement Bank Submitted')
        ->and($notification->data['body'])->toContain('Guaranty Trust Bank');
});
