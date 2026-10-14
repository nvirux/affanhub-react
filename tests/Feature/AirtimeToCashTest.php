<?php

use App\Filament\Merchant\Resources\AirtimeCashRequestResource\Pages\ListAirtimeCashRequests;
use App\Filament\Merchant\Widgets\AirtimeCashOverviewWidget;
use App\Models\AirtimeCashRequest;
use App\Models\Network;
use App\Models\Owner;
use App\Models\Store;
use App\Models\StoreAirtimeCashSetting;
use App\Models\User;
use Filament\Facades\Filament;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->owner = Owner::create([
        'name' => 'Store Owner',
        'email' => 'storeowner@example.com',
        'password' => bcrypt('password'),
    ]);

    $this->store = Store::create([
        'name' => 'Demo Telecom',
        'owner_id' => $this->owner->id,
    ]);

    DB::table('domains')->insert([
        'tenant_id' => $this->store->id,
        'domain' => 'demo.localhost',
        'is_primary' => true,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $this->network = Network::create([
        'name' => 'MTN',
        'slug' => 'mtn',
        'is_active' => true,
        'sort_order' => 1,
    ]);

    $this->storeSetting = StoreAirtimeCashSetting::create([
        'store_id' => $this->store->id,
        'network_id' => $this->network->id,
        'exchange_rate' => 85.00,
        'min_amount' => 500.00,
        'max_amount' => 50000.00,
        'receiving_phone' => '08031234567',
        'is_enabled' => true,
    ]);

    $this->user = User::factory()->withTransactionPin('1234')->create([
        'store_id' => $this->store->id,
        'phone' => '08098765432',
    ]);
});

test('can render airtime to cash page on customer storefront', function () {
    $response = $this->actingAs($this->user)
        ->get('http://demo.localhost/vtu/airtime-to-cash');

    $response->assertOk();
    $response->assertInertia(fn ($page) => $page
        ->component('Storefront/Vtu/AirtimeToCash')
        ->has('networks')
        ->has('recent_requests')
    );
});

test('customer can submit an airtime to cash request with wallet payout', function () {
    $response = $this->actingAs($this->user)
        ->post('http://demo.localhost/vtu/airtime-to-cash/submit', [
            'network_id' => $this->network->id,
            'airtime_amount' => 2000,
            'sender_phone' => '08098765432',
            'payout_method' => 'wallet',
        ]);

    $response->assertRedirect('http://demo.localhost/vtu/airtime-to-cash');
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('airtime_cash_requests', [
        'store_id' => $this->store->id,
        'user_id' => $this->user->id,
        'network_id' => $this->network->id,
        'airtime_amount' => 2000,
        'cash_amount' => 1700, // 85% of 2000
        'sender_phone' => '08098765432',
        'payout_method' => 'wallet',
        'status' => 'pending',
    ]);
});

test('customer can submit an airtime to cash request with bank payout', function () {
    $response = $this->actingAs($this->user)
        ->post('http://demo.localhost/vtu/airtime-to-cash/submit', [
            'network_id' => $this->network->id,
            'airtime_amount' => 5000,
            'sender_phone' => '08098765432',
            'payout_method' => 'bank',
            'bank_name' => 'OPay',
            'account_number' => '8098765432',
            'account_name' => 'John Doe',
        ]);

    $response->assertRedirect('http://demo.localhost/vtu/airtime-to-cash');

    $this->assertDatabaseHas('airtime_cash_requests', [
        'store_id' => $this->store->id,
        'airtime_amount' => 5000,
        'cash_amount' => 4250,
        'bank_name' => 'OPay',
        'account_number' => '8098765432',
        'account_name' => 'John Doe',
        'status' => 'pending',
    ]);
});

test('merchant approval of wallet payout credits customer balance', function () {
    $request = AirtimeCashRequest::create([
        'reference' => AirtimeCashRequest::generateReference(),
        'store_id' => $this->store->id,
        'user_id' => $this->user->id,
        'network_id' => $this->network->id,
        'airtime_amount' => 1000,
        'cash_amount' => 850,
        'exchange_rate' => 85.00,
        'sender_phone' => '08098765432',
        'receiving_phone' => '08031234567',
        'payout_method' => 'wallet',
        'status' => 'pending',
    ]);

    expect($this->user->wallet('main')->balance)->toEqual(0);

    $request->approve('Verified SMS transfer');

    expect($request->fresh()->status)->toBe('approved');
    expect((float) $this->user->wallet('main')->fresh()->balance)->toEqual(850.0);

    // Verify storefront Transaction history entry was created
    $this->assertDatabaseHas('transactions', [
        'store_id' => $this->store->id,
        'user_id' => $this->user->id,
        'service_type' => 'airtime_to_cash',
        'reference' => $request->reference,
        'amount' => 1000.00,
        'amount_paid' => 850.00,
        'profit' => 150.00,
        'status' => 'successful',
    ]);
});

test('airtime cash details modal view renders formatted values', function () {
    $request = AirtimeCashRequest::create([
        'reference' => 'ATC-TEST1234',
        'store_id' => $this->store->id,
        'user_id' => $this->user->id,
        'network_id' => $this->network->id,
        'airtime_amount' => 5000,
        'cash_amount' => 4250,
        'exchange_rate' => 85.00,
        'sender_phone' => '08098765432',
        'receiving_phone' => '08031234567',
        'payout_method' => 'bank',
        'bank_name' => 'Kuda Bank',
        'account_number' => '1234567890',
        'account_name' => 'Jane Doe',
        'status' => 'pending',
    ]);

    $view = $this->view('filament.merchant.components.airtime-cash-details-modal', [
        'record' => $request,
    ]);

    $view->assertSee('ATC-TEST1234');
    $view->assertSee('5,000.00');
    $view->assertSee('4,250.00');
    $view->assertSee('Kuda Bank');
    $view->assertSee('1234567890');
    $view->assertSee('Jane Doe');
});

test('merchant list airtime cash requests tabs render correctly', function () {
    Filament::setCurrentPanel(Filament::getPanel('merchant'));
    $this->actingAs($this->owner, 'owner');
    Filament::setTenant($this->store);
    $page = new ListAirtimeCashRequests;
    $tabs = $page->getTabs();

    expect($tabs)->toHaveKeys(['pending', 'approved', 'rejected', 'all']);
    expect($tabs['pending'])->toBeInstanceOf(Tab::class);
});

test('customer cannot submit airtime amount below min or above max', function () {
    // Below min (< 500)
    $responseBelow = $this->actingAs($this->user)
        ->post('http://demo.localhost/vtu/airtime-to-cash/submit', [
            'network_id' => $this->network->id,
            'airtime_amount' => 200,
            'sender_phone' => '08098765432',
            'payout_method' => 'wallet',
        ]);

    $responseBelow->assertSessionHasErrors('airtime_amount');

    // Above max (> 50,000)
    $responseAbove = $this->actingAs($this->user)
        ->post('http://demo.localhost/vtu/airtime-to-cash/submit', [
            'network_id' => $this->network->id,
            'airtime_amount' => 60000,
            'sender_phone' => '08098765432',
            'payout_method' => 'wallet',
        ]);

    $responseAbove->assertSessionHasErrors('airtime_amount');
});

test('customer can submit with optional proof of transfer image', function () {
    Storage::fake('public');

    $file = UploadedFile::fake()->image('ussd_receipt.jpg');

    $response = $this->actingAs($this->user)
        ->post('http://demo.localhost/vtu/airtime-to-cash/submit', [
            'network_id' => $this->network->id,
            'airtime_amount' => 2500,
            'sender_phone' => '08098765432',
            'payout_method' => 'wallet',
            'proof_image' => $file,
        ]);

    $response->assertRedirect('http://demo.localhost/vtu/airtime-to-cash');
    $response->assertSessionHas('success');

    $created = AirtimeCashRequest::where('airtime_amount', 2500)->first();
    expect($created)->not->toBeNull();
    expect($created->proof_image)->not->toBeNull();
    Storage::disk('public')->assertExists($created->proof_image);
});

test('airtime cash dashboard widget calculates pending orders and profit', function () {
    AirtimeCashRequest::create([
        'reference' => 'ATC-WIDGET-01',
        'store_id' => $this->store->id,
        'user_id' => $this->user->id,
        'network_id' => $this->network->id,
        'airtime_amount' => 10000,
        'cash_amount' => 8500,
        'exchange_rate' => 85.00,
        'sender_phone' => '08011112222',
        'receiving_phone' => '08031234567',
        'payout_method' => 'wallet',
        'status' => 'pending',
    ]);

    Filament::setCurrentPanel(Filament::getPanel('merchant'));
    $this->actingAs($this->owner, 'owner');
    Filament::setTenant($this->store);

    $widget = new AirtimeCashOverviewWidget;
    $reflection = new ReflectionClass($widget);
    $method = $reflection->getMethod('getStats');
    $method->setAccessible(true);
    $stats = $method->invoke($widget);

    expect($stats)->toBeArray()->toHaveCount(3);
    expect($stats[0]->getValue())->toBe('1');
    expect($stats[0]->getDescription())->toContain('8,500.00');
});
