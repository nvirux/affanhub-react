<?php

use App\Models\DataPlan;
use App\Models\DataType;
use App\Models\Network;
use App\Models\Plan;
use App\Models\PlanDataPrice;
use App\Services\Vtu\DataPlanSyncService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;

uses(RefreshDatabase::class);

test('it syncs data plans from upstream VTU provider API payload', function () {
    $sampleJson = [
        [
            'id' => 5,
            'name' => '500MB',
            'description' => null,
            'network' => [
                'name' => 'MTN',
                'code' => 'mtn',
            ],
            'type' => 'data share ',
            'size_mb' => 500,
            'price' => 230,
            'regular_price' => 245,
            'validity' => '7 days',
            'is_best_offer' => true,
            'is_available' => true,
        ],
        [
            'id' => 47,
            'name' => '200MB',
            'description' => null,
            'network' => [
                'name' => 'Glo',
                'code' => 'glo',
            ],
            'type' => 'corporate gifting',
            'size_mb' => 200,
            'price' => 90,
            'regular_price' => 90,
            'validity' => '14 days',
            'is_best_offer' => false,
            'is_available' => true,
        ],
        [
            'id' => 64,
            'name' => '150MB',
            'description' => null,
            'network' => [
                'name' => 'Airtel',
                'code' => 'airtel',
            ],
            'type' => 'sme',
            'size_mb' => 150,
            'price' => 60,
            'regular_price' => 60,
            'validity' => '1 days',
            'is_best_offer' => false,
            'is_available' => true,
        ],
        [
            'id' => 118,
            'name' => '100MB',
            'description' => null,
            'network' => [
                'name' => '9mobile',
                'code' => '9mobile',
            ],
            'type' => 'corporate',
            'size_mb' => 100,
            'price' => 100,
            'regular_price' => 100,
            'validity' => '30 days',
            'is_best_offer' => false,
            'is_available' => true,
        ],
    ];

    /** @var DataPlanSyncService $syncService */
    $syncService = app(DataPlanSyncService::class);

    $result = $syncService->sync($sampleJson);

    expect($result['success'])->toBeTrue();
    expect($result['count'])->toBe(4);
    expect($result['networks'])->toBe(4);

    // Verify Networks
    expect(Network::where('slug', 'mtn')->exists())->toBeTrue();
    expect(Network::where('slug', 'glo')->exists())->toBeTrue();
    expect(Network::where('slug', 'airtel')->exists())->toBeTrue();
    expect(Network::where('slug', '9mobile')->exists())->toBeTrue();

    // Verify DataTypes
    expect(DataType::where('slug', 'data-share')->exists())->toBeTrue();
    expect(DataType::where('slug', 'corporate-gifting')->exists())->toBeTrue();

    // Verify DataPlan records
    $mtnPlan = DataPlan::where('plan_code', '5')->first();
    expect($mtnPlan)->not->toBeNull();
    expect($mtnPlan->name)->toBe('500MB');
    expect($mtnPlan->size_mb)->toBe(500);
    expect($mtnPlan->validity)->toBe('7 days');
    expect((float) $mtnPlan->cost_price)->toBe(230.0);
    expect((float) $mtnPlan->default_retail_price)->toBe(245.0);
    expect($mtnPlan->is_best_offer)->toBeTrue();
    expect($mtnPlan->is_active)->toBeTrue();

    // Verify idempotency (re-sync doesn't duplicate)
    $resync = $syncService->sync($sampleJson);
    expect($resync['success'])->toBeTrue();
    expect(DataPlan::count())->toBe(4);
});

test('it handles live API HTTP response structure', function () {
    Http::fake([
        '*/data/plans' => Http::response([
            'success' => true,
            'message' => 'Data plans retrieved successfully.',
            'code' => 'SUCCESS',
            'data' => [
                [
                    'id' => 5,
                    'name' => '500MB',
                    'network' => ['name' => 'MTN', 'code' => 'mtn'],
                    'type' => 'data share',
                    'size_mb' => 500,
                    'price' => 230,
                    'regular_price' => 245,
                    'validity' => '7 days',
                    'is_best_offer' => true,
                    'is_available' => true,
                ],
            ],
        ], 200),
    ]);

    /** @var DataPlanSyncService $syncService */
    $syncService = app(DataPlanSyncService::class);
    $result = $syncService->sync();

    expect($result['success'])->toBeTrue();
    expect($result['count'])->toBe(1);
});

test('it deletes stale data plans that are no longer returned by the provider', function () {
    /** @var DataPlanSyncService $syncService */
    $syncService = app(DataPlanSyncService::class);

    // Initial sync with 2 MTN plans
    $initialData = [
        [
            'id' => 5,
            'name' => '500MB',
            'network' => ['name' => 'MTN', 'code' => 'mtn'],
            'type' => 'data share',
            'size_mb' => 500,
            'price' => 230,
            'regular_price' => 245,
            'validity' => '7 days',
            'is_best_offer' => true,
            'is_available' => true,
        ],
        [
            'id' => 6,
            'name' => '1GB',
            'network' => ['name' => 'MTN', 'code' => 'mtn'],
            'type' => 'data share',
            'size_mb' => 1024,
            'price' => 280,
            'regular_price' => 300,
            'validity' => '30 days',
            'is_best_offer' => false,
            'is_available' => true,
        ],
    ];

    $syncService->sync($initialData);
    expect(DataPlan::count())->toBe(2);

    // Re-sync where plan 6 was deleted by VTULab (only plan 5 returned)
    $updatedData = [
        [
            'id' => 5,
            'name' => '500MB',
            'network' => ['name' => 'MTN', 'code' => 'mtn'],
            'type' => 'data share',
            'size_mb' => 500,
            'price' => 230,
            'regular_price' => 245,
            'validity' => '7 days',
            'is_best_offer' => true,
            'is_available' => true,
        ],
    ];

    $resync = $syncService->sync($updatedData);

    expect($resync['success'])->toBeTrue();
    expect($resync['count'])->toBe(1);
    expect($resync['deleted'])->toBe(1);
    expect(DataPlan::count())->toBe(1);
    expect(DataPlan::where('plan_code', '6')->exists())->toBeFalse();
    expect(DataPlan::where('plan_code', '5')->exists())->toBeTrue();
});

test('it syncs with automated tier pricing and rounding', function () {
    Plan::create(['name' => 'Starter', 'slug' => 'starter', 'price_monthly' => 0, 'price_yearly' => 0]);
    Plan::create(['name' => 'Pro', 'slug' => 'pro', 'price_monthly' => 5000, 'price_yearly' => 50000]);
    Plan::create(['name' => 'Enterprise', 'slug' => 'enterprise', 'price_monthly' => 15000, 'price_yearly' => 150000]);

    $data = [
        [
            'id' => 10,
            'name' => '1GB',
            'network' => ['name' => 'MTN', 'code' => 'mtn'],
            'type' => 'sme',
            'size_mb' => 1024,
            'price' => 250, // Provider cost
            'regular_price' => 260,
            'validity' => '30 days',
        ],
    ];

    /** @var DataPlanSyncService $syncService */
    $syncService = app(DataPlanSyncService::class);

    $result = $syncService->sync($data, [
        'scope' => 'all',
        'apply_pricing' => true,
        'margin_type' => 'fixed',
        'starter_margin' => 15.00,
        'pro_margin' => 10.00,
        'enterprise_margin' => 5.00,
        'retail_margin' => 40.00,
        'round_to' => '5',
    ]);

    expect($result['success'])->toBeTrue();

    $plan = DataPlan::where('plan_code', '10')->first();
    expect($plan)->not->toBeNull();
    // Starter price: 250 + 15 = 265
    expect((float) $plan->selling_price)->toBe(265.0);
    // Retail price: 250 + 40 = 290
    expect((float) $plan->default_retail_price)->toBe(290.0);

    // Verify PlanDataPrice records for tiers
    $proPlan = Plan::where('slug', 'pro')->first();
    $proPrice = PlanDataPrice::where('plan_id', $proPlan->id)->where('data_plan_id', $plan->id)->first();
    expect($proPrice)->not->toBeNull();
    // Pro price: 250 + 10 = 260
    expect((float) $proPrice->wholesale_price)->toBe(260.0);

    $entPlan = Plan::where('slug', 'enterprise')->first();
    $entPrice = PlanDataPrice::where('plan_id', $entPlan->id)->where('data_plan_id', $plan->id)->first();
    expect($entPrice)->not->toBeNull();
    // Enterprise price: 250 + 5 = 255
    expect((float) $entPrice->wholesale_price)->toBe(255.0);
});

test('it preserves existing custom prices when scope is new_only or costs_only', function () {
    $initialData = [
        [
            'id' => 20,
            'name' => '1GB',
            'network' => ['name' => 'Airtel', 'code' => 'airtel'],
            'type' => 'gifting',
            'size_mb' => 1024,
            'price' => 200,
            'regular_price' => 250,
            'validity' => '30 days',
        ],
    ];

    /** @var DataPlanSyncService $syncService */
    $syncService = app(DataPlanSyncService::class);
    $syncService->sync($initialData);

    $plan = DataPlan::where('plan_code', '20')->first();
    $plan->update(['selling_price' => 320.00, 'default_retail_price' => 350.00]);

    // Resync with new_only scope
    $updatedData = [
        [
            'id' => 20,
            'name' => '1GB',
            'network' => ['name' => 'Airtel', 'code' => 'airtel'],
            'type' => 'gifting',
            'size_mb' => 1024,
            'price' => 220, // cost changed
            'regular_price' => 270,
            'validity' => '30 days',
        ],
    ];

    $syncService->sync($updatedData, ['scope' => 'new_only']);
    $plan->refresh();
    // When new_only, existing plan was not touched
    expect((float) $plan->selling_price)->toBe(320.00);

    // Resync with costs_only scope
    $syncService->sync($updatedData, ['scope' => 'costs_only']);
    $plan->refresh();
    // When costs_only, cost_price was updated to 220, but custom selling_price remains 320.00
    expect((float) $plan->cost_price)->toBe(220.00);
    expect((float) $plan->selling_price)->toBe(320.00);
});
