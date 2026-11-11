<?php

use App\Filament\Merchant\Resources\StoreModificationServiceResource\Pages\ListStoreModificationServices;
use App\Models\ModificationRequest;
use App\Models\Owner;
use App\Models\Service;
use App\Models\Store;
use App\Models\StoreModificationService;
use App\Models\User;
use App\Services\WalletService;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

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

test('customer without valid pin or with empty pin cannot submit modification request', function () {
    StoreModificationService::seedDefaultsForStore($this->store);
    $service = StoreModificationService::where('store_id', $this->store->id)->first();

    $this->walletService->credit($this->mainWallet, 10000.00, 'deposit', 'Initial deposit', [], 'DEP_PIN_TEST');

    // 1. Submit with empty pin
    $response = $this->actingAs($this->user)->postJson('http://demo.localhost/identity/modification', [
        'service_id' => $service->id,
        'identifier' => '12345678901',
        'fields' => ['Full Name' => 'John Doe'],
        'pin' => '',
    ]);

    $response->assertStatus(422);

    // 2. Submit with wrong pin
    $responseWrong = $this->actingAs($this->user)->postJson('http://demo.localhost/identity/modification', [
        'service_id' => $service->id,
        'identifier' => '12345678901',
        'fields' => ['Full Name' => 'John Doe'],
        'pin' => '0000',
    ]);

    $responseWrong->assertStatus(422);
});

test('modification upload rejects disguised HTML file and saves valid documents with safe extension', function () {
    StoreModificationService::seedDefaultsForStore($this->store);
    $service = StoreModificationService::where('store_id', $this->store->id)->first();

    $this->walletService->credit($this->mainWallet, 10000.00, 'deposit', 'Initial deposit', [], 'DEP_UPLOAD_TEST');

    // Upload an HTML file disguised with png extension
    $htmlProbe = UploadedFile::fake()->createWithContent('exploit.png', '<!DOCTYPE html><html><body><script>alert(1)</script></body></html>');

    $docs = [];
    foreach ($service->getNormalizedDocuments() as $doc) {
        $docs[$doc['name']] = $htmlProbe;
    }

    $response = $this->actingAs($this->user)->postJson('http://demo.localhost/identity/modification', [
        'service_id' => $service->id,
        'identifier' => '12345678901',
        'fields' => ['Full Name' => 'John Doe'],
        'documents' => $docs,
        'pin' => '1234',
    ]);

    // Must be rejected because mime is text/html
    $response->assertStatus(422);
});

test('store modification service automatically generates unique slug from name if slug is not provided', function () {
    $ninService = Service::where('key', 'nin_modification')->first();

    $created = StoreModificationService::create([
        'store_id' => $this->store->id,
        'service_id' => $ninService->id,
        'name' => 'Change of State and LGA',
        'price' => 5000,
        'processing_time' => '24 - 48 Hours',
        'is_active' => true,
    ]);

    expect($created->slug)->toBe('nin_change_of_state_and_lga');

    // Duplicate name should generate unique counter
    $duplicate = StoreModificationService::create([
        'store_id' => $this->store->id,
        'service_id' => $ninService->id,
        'name' => 'Change of State and LGA',
        'price' => 5000,
        'processing_time' => '24 - 48 Hours',
        'is_active' => true,
    ]);

    expect($duplicate->slug)->toBe('nin_change_of_state_and_lga_1');
});

test('merchant can create custom modification service without providing slug in filament', function () {
    Filament::setCurrentPanel(Filament::getPanel('merchant'));
    $this->actingAs($this->owner, 'owner');
    Filament::setTenant($this->store);

    $ninService = Service::where('key', 'nin_modification')->first();

    Livewire::test(ListStoreModificationServices::class)
        ->callAction('create', [
            'service_id' => $ninService->id,
            'name' => 'Custom Name Correction',
            'price' => 2500,
            'processing_time' => '24 - 48 Hours',
            'is_active' => true,
            'fields' => [
                ['label' => 'New Name', 'type' => 'text', 'is_required' => true],
            ],
            'required_documents' => [
                ['name' => 'Court Affidavit', 'is_required' => true],
            ],
        ])
        ->assertHasNoActionErrors();

    $service = StoreModificationService::where('store_id', $this->store->id)
        ->where('name', 'Custom Name Correction')
        ->first();

    expect($service)->not->toBeNull()
        ->and($service->slug)->toBe('nin_custom_name_correction')
        ->and((float) $service->price)->toBe(2500.00);
});
