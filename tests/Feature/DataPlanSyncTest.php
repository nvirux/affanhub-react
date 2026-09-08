<?php

use App\Models\DataPlan;
use App\Models\DataType;
use App\Models\Network;
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
