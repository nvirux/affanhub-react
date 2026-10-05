<?php

use App\Models\DataPlan;
use App\Models\DataType;
use App\Models\Network;
use App\Models\Owner;
use App\Models\Plan;
use App\Models\PlanDataPrice;
use App\Models\Store;
use App\Models\StoreDataPlan;
use App\Models\Subscription;
use App\Models\Transaction;
use App\Models\User;
use App\Services\Vtu\DataService;
use App\Services\Vtu\VtuLabService;
use App\Services\WalletService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->owner = Owner::create([
        'name' => 'Store Owner',
        'email' => 'merchant@example.com',
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

    $this->dataType = DataType::create([
        'name' => 'SME Data',
        'slug' => 'sme',
        'is_active' => true,
    ]);

    $this->dataPlan = DataPlan::create([
        'network_id' => $this->network->id,
        'data_type_id' => $this->dataType->id,
        'name' => 'MTN 1GB SME',
        'size_mb' => 1024,
        'validity' => '30 Days',
        'cost_price' => 400.00,
        'selling_price' => 499.00, // Default Starter Base Wholesale Price
        'default_retail_price' => 550.00,
        'plan_code' => 'MTN_1GB_SME',
        'is_active' => true,
    ]);

    $this->storeDataPlan = StoreDataPlan::create([
        'store_id' => $this->store->id,
        'data_plan_id' => $this->dataPlan->id,
        'selling_price' => 500.00, // Merchant set customer retail price
        'is_active' => true,
    ]);

    $this->user = User::factory()->withTransactionPin('1234')->create([
        'store_id' => $this->store->id,
    ]);
});

test('store subscription alias returns active subscription', function () {
    $proPlan = Plan::create([
        'name' => 'Pro Plan',
        'slug' => 'pro',
        'price' => 5000,
        'is_active' => true,
    ]);

    $subscription = Subscription::create([
        'store_id' => $this->store->id,
        'plan_id' => $proPlan->id,
        'price' => 5000,
        'status' => 'active',
        'starts_at' => now(),
    ]);

    expect($this->store->subscription)->not->toBeNull();
    expect($this->store->subscription->id)->toBe($subscription->id);
    expect($this->store->subscription->plan_id)->toBe($proPlan->id);
});

test('merchant on pro plan is debited exact tier wholesale price instead of base starter rate', function () {
    // 1. Create Pro Plan with custom wholesale price ₦450 (base is ₦499)
    $proPlan = Plan::create([
        'name' => 'Pro Plan',
        'slug' => 'pro',
        'price' => 5000,
        'is_active' => true,
    ]);

    Subscription::create([
        'store_id' => $this->store->id,
        'plan_id' => $proPlan->id,
        'price' => 5000,
        'status' => 'active',
        'starts_at' => now(),
    ]);

    PlanDataPrice::create([
        'plan_id' => $proPlan->id,
        'data_plan_id' => $this->dataPlan->id,
        'wholesale_price' => 450.00,
    ]);

    // Verify storeDataPlan getWholesaleCost resolves to ₦450.00
    expect((float) $this->storeDataPlan->getWholesaleCost())->toBe(450.00);

    // 2. Fund Wallets
    $walletService = app(WalletService::class);
    $customerWallet = $this->user->wallet('main');
    $storeMainWallet = $this->store->mainWallet();
    $storeProfitWallet = $this->store->wallet('profit');

    $walletService->credit($customerWallet, 5000, 'deposit', 'Customer deposit');
    $walletService->credit($storeMainWallet, 5000, 'deposit', 'Store wholesale fund');

    // 3. Mock VtuLabService
    $mockVtuLab = Mockery::mock(VtuLabService::class);
    $mockVtuLab->shouldReceive('purchaseData')
        ->once()
        ->andReturn([
            'success' => true,
            'pending' => false,
            'status' => 'successful',
            'message' => 'Data delivered successfully.',
            'raw' => ['status' => 'successful'],
        ]);
    $this->app->instance(VtuLabService::class, $mockVtuLab);

    // 4. Purchase Data Plan (Customer Retail: ₦500.00, Wholesale: ₦450.00, Profit: ₦50.00)
    $dataService = app(DataService::class);
    $result = $dataService->buyDataPlan($this->user, $this->storeDataPlan, '08012345678');

    expect($result['success'])->toBeTrue();

    // Customer balance: 5000 - 500 = 4500.00
    expect((float) $customerWallet->fresh()->balance)->toBe(4500.00);

    // Merchant main wallet debited wholesale cost (₦450.00) and swept profit (₦50.00): 5000 - 450 - 50 = 4500.00
    expect((float) $storeMainWallet->fresh()->balance)->toBe(4500.00);

    // Merchant profit wallet should receive exactly ₦50.00 (500 retail - 450 wholesale)
    expect((float) $storeProfitWallet->fresh()->balance)->toBe(50.00);

    // Check transaction cost_price and profit
    $transaction = Transaction::where('reference', $result['reference'])->first();
    expect($transaction)->not->toBeNull();
    expect((float) $transaction->amount_paid)->toBe(500.00);
    expect((float) $transaction->cost_price)->toBe(450.00);
    expect((float) $transaction->profit)->toBe(50.00);
});
