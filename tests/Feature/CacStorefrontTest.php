<?php

use App\Models\CacRequest;
use App\Models\Owner;
use App\Models\Service;
use App\Models\Store;
use App\Models\StoreCacService;
use App\Models\User;
use App\Services\Cac\CacService;
use App\Services\WalletService;
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
        'name' => 'Affan Telecom',
        'owner_id' => $this->owner->id,
    ]);

    DB::table('domains')->insert([
        'tenant_id' => $this->store->id,
        'domain' => 'demo.localhost',
        'is_primary' => true,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $this->user = User::factory()->withTransactionPin('1234')->create([
        'store_id' => $this->store->id,
    ]);

    $this->walletService = app(WalletService::class);
    $this->mainWallet = $this->user->wallet('main') ?? $this->user->wallets()->create([
        'type' => 'main',
        'balance' => 0.00,
    ]);

    // Ensure central CAC service exists
    Service::firstOrCreate(
        ['key' => 'cac_services'],
        ['name' => 'CAC Services', 'category' => 'identity']
    );

    Storage::fake('public');
});

test('guests are redirected to login on cac route', function () {
    $this->get('http://demo.localhost/cac')->assertRedirect('http://demo.localhost/login');
});

test('cac page loads successfully and auto-seeds default packages', function () {
    expect(StoreCacService::where('store_id', $this->store->id)->count())->toBe(0);

    $response = $this->actingAs($this->user)->get('http://demo.localhost/cac');
    $response->assertOk();
    $response->assertInertia(fn ($page) => $page
        ->component('Storefront/Cac/Index')
        ->has('packages')
        ->has('recentOrders')
        ->has('walletBalance')
    );

    expect(StoreCacService::where('store_id', $this->store->id)->count())->toBeGreaterThanOrEqual(3);
});

test('cac service validates required dynamic fields', function () {
    $package = StoreCacService::create([
        'store_id' => $this->store->id,
        'name' => 'Business Name Reg',
        'slug' => 'cac_bn_test',
        'price' => 20000.00,
        'processing_time' => '3 - 5 Days',
        'fields' => [
            ['label' => 'Proposed Business Name', 'type' => 'text', 'is_required' => true],
            ['label' => 'Alternative Name', 'type' => 'text', 'is_required' => false],
        ],
        'required_documents' => [],
        'is_active' => true,
    ]);

    $this->walletService->credit($this->mainWallet, 50000, 'test_fund', 'Test funding');

    // Missing 'Proposed Business Name'
    $response = $this->actingAs($this->user)->post('http://demo.localhost/cac', [
        'service_id' => $package->id,
        'transaction_pin' => '1234',
        'fields' => [
            'Alternative Name' => 'Alternative LLC',
        ],
    ]);

    $response->assertSessionHasErrors();
    expect(CacRequest::count())->toBe(0);
});

test('cac submission fails if transaction pin is invalid', function () {
    $package = StoreCacService::create([
        'store_id' => $this->store->id,
        'name' => 'Name Reservation',
        'slug' => 'cac_res_test',
        'price' => 4000.00,
        'processing_time' => '24 Hours',
        'fields' => [],
        'required_documents' => [],
        'is_active' => true,
    ]);

    $this->walletService->credit($this->mainWallet, 10000, 'test_fund', 'Test funding');

    $response = $this->actingAs($this->user)->post('http://demo.localhost/cac', [
        'service_id' => $package->id,
        'transaction_pin' => '9999', // Incorrect PIN
    ]);

    $response->assertSessionHasErrors(['transaction_pin']);
    expect(CacRequest::count())->toBe(0);
});

test('cac submission fails if wallet balance is insufficient', function () {
    $package = StoreCacService::create([
        'store_id' => $this->store->id,
        'name' => 'Company Registration',
        'slug' => 'cac_comp_test',
        'price' => 60000.00,
        'processing_time' => '5 - 7 Days',
        'fields' => [],
        'required_documents' => [],
        'is_active' => true,
    ]);

    // Only 10,000 balance for a 60,000 service
    $this->walletService->credit($this->mainWallet, 10000, 'test_fund', 'Test funding');

    $response = $this->actingAs($this->user)->post('http://demo.localhost/cac', [
        'service_id' => $package->id,
        'transaction_pin' => '1234',
    ]);

    $response->assertSessionHasErrors(['balance']);
    expect(CacRequest::count())->toBe(0);
});

test('customer can submit cac application and wallet is debited atomically', function () {
    $package = StoreCacService::create([
        'store_id' => $this->store->id,
        'name' => 'Business Name Registration',
        'slug' => 'cac_bn_full_test',
        'price' => 25000.00,
        'processing_time' => '3 - 5 Days',
        'fields' => [
            ['label' => 'Proposed Business Name', 'type' => 'text', 'is_required' => true],
            ['label' => 'Nature of Business', 'type' => 'text', 'is_required' => true],
        ],
        'required_documents' => [
            ['name' => 'NIN Slip', 'is_required' => true, 'description' => 'Valid NIN'],
            ['name' => 'Specimen Signature', 'is_required' => false],
        ],
        'is_active' => true,
    ]);

    $this->walletService->credit($this->mainWallet, 30000.00, 'test_fund', 'Test funding');

    $fakeFile = UploadedFile::fake()->create('nin_slip.pdf', 300, 'application/pdf');

    $response = $this->actingAs($this->user)->post('http://demo.localhost/cac', [
        'service_id' => $package->id,
        'transaction_pin' => '1234',
        'fields' => [
            'Proposed Business Name' => 'Apex Cloud Technologies',
            'Nature of Business' => 'Information Technology',
        ],
        'documents' => [
            'NIN Slip' => $fakeFile,
        ],
    ]);

    $response->assertSessionHasNoErrors();

    // Verify order created
    $order = CacRequest::first();
    expect($order)->not->toBeNull();
    expect($order->business_name)->toBe('Apex Cloud Technologies');
    expect($order->status)->toBe('pending');
    expect((float) $order->amount_paid)->toBe(25000.00);

    // Verify wallet debited: 30,000 - 25,000 = 5,000
    $this->mainWallet->refresh();
    expect((float) $this->mainWallet->balance)->toBe(5000.00);
});

test('merchant can complete cac order and attach certificate', function () {
    $package = StoreCacService::create([
        'store_id' => $this->store->id,
        'name' => 'Business Name Reg',
        'slug' => 'cac_bn_complete_test',
        'price' => 25000.00,
        'processing_time' => '3 - 5 Days',
        'fields' => [],
        'required_documents' => [],
        'is_active' => true,
    ]);

    $order = CacRequest::create([
        'reference' => CacRequest::generateReference(),
        'store_id' => $this->store->id,
        'user_id' => $this->user->id,
        'store_cac_service_id' => $package->id,
        'service_name' => 'Business Name Reg',
        'business_name' => 'Apex Cloud',
        'amount_paid' => 25000.00,
        'status' => 'pending',
    ]);

    $cacService = app(CacService::class);
    $cacService->markProcessing($order, 'Submitted to CAC agent');

    $order->refresh();
    expect($order->isProcessing())->toBeTrue();

    // Store main wallet is 0.00
    expect((float) ($this->store->mainWallet()?->balance ?? 0))->toBe(0.00);

    $cacService->complete($order, 'cac/certificates/cert_123.pdf', 'Registration approved');

    $order->refresh();
    expect($order->isCompleted())->toBeTrue();
    expect($order->result_file_path)->toBe('cac/certificates/cert_123.pdf');

    // Merchant profit wallet should now have 25,000.00 credited
    $profitWallet = $this->store->profitWallet();
    expect((float) $profitWallet->balance)->toBe(25000.00);
});

test('merchant can reject cac order and funds are refunded to customer wallet', function () {
    $this->mainWallet->update(['balance' => 1000.00]);

    $package = StoreCacService::create([
        'store_id' => $this->store->id,
        'name' => 'Business Name Reg',
        'slug' => 'cac_bn_reject_test',
        'price' => 25000.00,
        'processing_time' => '3 - 5 Days',
        'fields' => [],
        'required_documents' => [],
        'is_active' => true,
    ]);

    $order = CacRequest::create([
        'reference' => CacRequest::generateReference(),
        'store_id' => $this->store->id,
        'user_id' => $this->user->id,
        'store_cac_service_id' => $package->id,
        'service_name' => 'Business Name Reg',
        'business_name' => 'Taken Name Enterprise',
        'amount_paid' => 25000.00,
        'status' => 'pending',
    ]);

    $cacService = app(CacService::class);
    $cacService->reject($order, 'Proposed name was rejected by CAC');

    $order->refresh();
    expect($order->isRejected())->toBeTrue();
    expect($order->rejection_reason)->toBe('Proposed name was rejected by CAC');

    // Wallet balance should be refunded: 1,000 + 25,000 = 26,000
    $this->mainWallet->refresh();
    expect((float) $this->mainWallet->balance)->toBe(26000.00);
});
