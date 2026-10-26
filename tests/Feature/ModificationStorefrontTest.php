<?php

use App\Models\ModificationRequest;
use App\Models\Owner;
use App\Models\Service;
use App\Models\Store;
use App\Models\StoreModificationService;
use App\Models\User;
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

    // Ensure services exist
    Service::firstOrCreate(['key' => 'nin_modification'], ['name' => 'NIN Modification', 'category' => 'identity']);
    Service::firstOrCreate(['key' => 'bvn_modification'], ['name' => 'BVN Modification', 'category' => 'identity']);

    Storage::fake('central_public');
});

test('guests are redirected to login on identity modification routes', function () {
    $this->get('http://demo.localhost/identity/nin-modification')->assertRedirect('http://demo.localhost/login');
    $this->get('http://demo.localhost/identity/bvn-modification')->assertRedirect('http://demo.localhost/login');
});

test('nin modification page loads with NIN services only', function () {
    $response = $this->actingAs($this->user)->get('http://demo.localhost/identity/nin-modification');
    $response->assertOk();
    $response->assertInertia(fn ($page) => $page
        ->component('Storefront/Identity/Modification')
        ->where('category', 'nin')
    );
});

test('bvn modification page loads with BVN services only', function () {
    $response = $this->actingAs($this->user)->get('http://demo.localhost/identity/bvn-modification');
    $response->assertOk();
    $response->assertInertia(fn ($page) => $page
        ->component('Storefront/Identity/Modification')
        ->where('category', 'bvn')
    );
});

test('customer can submit modification request with required documents', function () {
    // Seed services
    StoreModificationService::seedDefaultsForStore($this->store);

    $service = StoreModificationService::where('store_id', $this->store->id)
        ->where('slug', 'nin_phone_update')
        ->first();

    expect($service)->not->toBeNull();

    // Fund user wallet
    $this->walletService->credit(
        $this->mainWallet,
        10000.00,
        'deposit',
        'Initial deposit',
        [],
        'DEP_123'
    );

    // Prepare mock documents
    $files = [];
    foreach ($service->getNormalizedDocuments() as $doc) {
        $files[$doc['name']] = UploadedFile::fake()->create('proof.pdf', 100, 'application/pdf');
    }

    $response = $this->actingAs($this->user)->post('http://demo.localhost/identity/modification', [
        'service_id' => $service->id,
        'identifier' => '12345678901',
        'old_data' => ['old_phone' => '08011112222'],
        'new_data' => ['new_phone' => '08099998888'],
        'documents' => $files,
        'pin' => '1234',
    ]);

    $response->assertOk();
    $data = $response->json();
    expect($data['success'])->toBeTrue();

    // Check request record created
    $modRequest = ModificationRequest::where('reference', $data['reference'])->first();
    expect($modRequest)->not->toBeNull();
    expect($modRequest->status)->toBe('pending');
    expect($modRequest->identifier)->toBe('12345678901');

    // Check customer wallet was debited
    $this->mainWallet->refresh();
    expect((float) $this->mainWallet->balance)->toBe(10000.00 - (float) $service->price);
});

test('merchant can process, complete, and settle modification profit', function () {
    StoreModificationService::seedDefaultsForStore($this->store);
    $service = StoreModificationService::where('store_id', $this->store->id)->first();

    // Create a pending request
    $modRequest = ModificationRequest::create([
        'reference' => 'MOD_TEST1234',
        'store_id' => $this->store->id,
        'user_id' => $this->user->id,
        'store_modification_service_id' => $service->id,
        'service_id' => $service->service_id,
        'service_name' => $service->name,
        'identifier' => '12345678901',
        'old_data' => ['old_name' => 'John Doe'],
        'new_data' => ['new_name' => 'John Smith'],
        'uploaded_documents' => [],
        'amount_paid' => $service->price,
        'status' => 'pending',
        'settlement_type' => 'online_profit_credit',
    ]);

    // Store main wallet has customer funds
    $storeMainWallet = $this->store->mainWallet();
    $this->walletService->credit($storeMainWallet, 20000.00, 'deposit', 'Store float', [], 'DEP_FLOAT');

    // Merchant marks processing
    $modRequest->markProcessing('Sent to NIMC agent');
    expect($modRequest->fresh()->status)->toBe('processing');

    // Merchant completes order
    $modRequest->complete(resultFilePath: 'modifications/results/slip.pdf', notes: 'Done');
    expect($modRequest->fresh()->status)->toBe('completed');

    // Profit wallet credited
    $profitWallet = $this->store->profitWallet();
    expect((float) $profitWallet->balance)->toBe((float) $service->price);
});

test('merchant can reject modification order and refund customer wallet', function () {
    StoreModificationService::seedDefaultsForStore($this->store);
    $service = StoreModificationService::where('store_id', $this->store->id)->first();

    $this->walletService->credit($this->mainWallet, 1000.00, 'deposit', 'Initial', [], 'DEP_INIT');

    $modRequest = ModificationRequest::create([
        'reference' => 'MOD_REJ1234',
        'store_id' => $this->store->id,
        'user_id' => $this->user->id,
        'store_modification_service_id' => $service->id,
        'service_id' => $service->service_id,
        'service_name' => $service->name,
        'identifier' => '12345678901',
        'old_data' => [],
        'new_data' => ['name' => 'Test'],
        'uploaded_documents' => [],
        'amount_paid' => 3500.00,
        'status' => 'pending',
        'settlement_type' => 'online_profit_credit',
    ]);

    $balanceBefore = (float) $this->mainWallet->fresh()->balance;

    // Reject order
    $modRequest->reject('Affidavit stamp missing');

    expect($modRequest->fresh()->status)->toBe('rejected');
    expect((float) $this->mainWallet->fresh()->balance)->toBe($balanceBefore + 3500.00);
});
