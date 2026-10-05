<?php

namespace App\Services\Vtu;

use App\Models\DataPlan;
use App\Models\DataType;
use App\Models\Network;
use App\Models\Plan;
use App\Models\PlanDataPrice;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class DataPlanSyncService
{
    public function __construct(
        protected VtuLabService $vtuLabService
    ) {}

    /**
     * Sync data plans directly from the upstream provider API.
     *
     * @param  array|null  $overrideData  Optional array of plan items to sync (e.g. for testing)
     * @param  array  $options  Configuration options (scope, apply_pricing, tier margins, rounding, delete_stale)
     */
    public function sync(?array $overrideData = null, array $options = []): array
    {
        if ($overrideData !== null) {
            $data = $overrideData;
        } else {
            $response = $this->vtuLabService->fetchPlans();

            $isSuccess = ($response['success'] ?? false) === true || ($response['code'] ?? '') === 'SUCCESS';

            if (! $isSuccess || ! isset($response['data']) || ! is_array($response['data'])) {
                $errorMsg = $response['message'] ?? 'Failed to retrieve data plans from provider API.';
                Log::warning('DataPlanSync failed: '.$errorMsg, ['response' => $response]);

                return [
                    'success' => false,
                    'count' => 0,
                    'networks' => 0,
                    'message' => $errorMsg,
                ];
            }

            $data = $response['data'];
        }

        if (empty($data)) {
            return [
                'success' => false,
                'count' => 0,
                'networks' => 0,
                'message' => 'No data plans returned by provider.',
            ];
        }

        $scope = $options['scope'] ?? 'all'; // 'all', 'new_only', 'costs_only'
        $applyPricing = (bool) ($options['apply_pricing'] ?? false);
        $marginType = $options['margin_type'] ?? 'fixed'; // 'fixed' or 'percentage'
        $starterMargin = (float) ($options['starter_margin'] ?? 15.00);
        $proMargin = (float) ($options['pro_margin'] ?? 10.00);
        $enterpriseMargin = (float) ($options['enterprise_margin'] ?? 5.00);
        $retailMargin = (float) ($options['retail_margin'] ?? 40.00);
        $roundTo = $options['round_to'] ?? '5';
        $deleteStale = (bool) ($options['delete_stale'] ?? true);

        // Preload subscription plans for tier wholesale pricing
        $plansBySlug = Plan::whereIn('slug', ['starter', 'pro', 'enterprise'])->get()->keyBy('slug');

        $syncedCount = 0;
        $deletedCount = 0;
        $networkIds = [];
        $syncedPlanIds = [];

        DB::transaction(function () use (
            $data,
            $scope,
            $applyPricing,
            $marginType,
            $starterMargin,
            $proMargin,
            $enterpriseMargin,
            $retailMargin,
            $roundTo,
            $deleteStale,
            $plansBySlug,
            &$syncedCount,
            &$deletedCount,
            &$networkIds,
            &$syncedPlanIds
        ) {
            foreach ($data as $item) {
                if (empty($item['id']) || empty($item['network']['name'])) {
                    continue;
                }

                // 1. Resolve or Create Network
                $netName = trim($item['network']['name']);
                $netCode = strtolower(trim($item['network']['code'] ?? $netName));

                $network = Network::firstOrCreate(
                    ['slug' => $netCode],
                    [
                        'name' => $netName,
                        'is_active' => true,
                    ]
                );
                $networkIds[$network->id] = true;

                // 2. Resolve or Create DataType
                $rawType = trim((string) ($item['type'] ?? 'General'));
                $typeSlug = Str::slug($rawType);

                $dataType = DataType::firstOrCreate(
                    ['slug' => $typeSlug],
                    [
                        'name' => ucwords($rawType),
                        'is_active' => true,
                    ]
                );

                // 3. Resolve Size MB
                $sizeMb = (int) ($item['size_mb'] ?? 0);
                if ($sizeMb <= 0 && ! empty($item['name'])) {
                    if (preg_match('/(\d+(?:\.\d+)?)\s*GB/i', $item['name'], $m)) {
                        $sizeMb = (int) (floatval($m[1]) * 1024);
                    } elseif (preg_match('/(\d+)\s*MB/i', $item['name'], $m)) {
                        $sizeMb = (int) $m[1];
                    }
                }

                // 4. Resolve Provider Base Cost
                $costPrice = (float) ($item['price'] ?? 0);
                $providerRegularPrice = (float) ($item['regular_price'] ?? $costPrice);

                // Check if this plan already exists
                $existingPlan = DataPlan::where('network_id', $network->id)
                    ->where('plan_code', (string) $item['id'])
                    ->first();

                // If scope is 'new_only' and plan already exists, leave it completely untouched
                if ($scope === 'new_only' && $existingPlan) {
                    $syncedPlanIds[] = $existingPlan->id;

                    continue;
                }

                // Compute prices
                $wholesalePrice = $providerRegularPrice;
                $retailPrice = $providerRegularPrice;

                if ($scope === 'costs_only' && $existingPlan) {
                    // Only update provider cost price and active status; keep existing merchant & retail prices
                    $wholesalePrice = (float) $existingPlan->selling_price;
                    $retailPrice = (float) $existingPlan->default_retail_price;
                } elseif ($applyPricing && $costPrice > 0) {
                    // Compute Starter wholesale price (which is DataPlan->selling_price)
                    if ($marginType === 'percentage') {
                        $wholesalePrice = $costPrice * (1 + ($starterMargin / 100));
                        $retailPrice = $costPrice * (1 + ($retailMargin / 100));
                    } else {
                        $wholesalePrice = $costPrice + $starterMargin;
                        $retailPrice = $costPrice + $retailMargin;
                    }

                    if ($roundTo === '5') {
                        $wholesalePrice = round($wholesalePrice / 5) * 5;
                        $retailPrice = round($retailPrice / 5) * 5;
                    } elseif ($roundTo === '10') {
                        $wholesalePrice = round($wholesalePrice / 10) * 10;
                        $retailPrice = round($retailPrice / 10) * 10;
                    }
                } elseif ($existingPlan && ! $applyPricing) {
                    // If not re-calculating pricing, keep existing custom selling prices
                    $wholesalePrice = (float) $existingPlan->selling_price;
                    $retailPrice = (float) $existingPlan->default_retail_price;
                }

                // 5. Update or Create DataPlan
                $dataPlan = DataPlan::updateOrCreate(
                    [
                        'network_id' => $network->id,
                        'plan_code' => (string) $item['id'],
                    ],
                    [
                        'data_type_id' => $dataType->id,
                        'name' => trim((string) $item['name']),
                        'size_mb' => $sizeMb,
                        'validity' => trim((string) ($item['validity'] ?? '30 days')),
                        'cost_price' => $costPrice,
                        'selling_price' => round($wholesalePrice, 2),
                        'default_retail_price' => round($retailPrice, 2),
                        'is_best_offer' => (bool) ($item['is_best_offer'] ?? false),
                        'is_active' => (bool) ($item['is_available'] ?? true),
                    ]
                );

                // 6. If automated pricing is active, also sync tier wholesale prices (Starter, Pro, Enterprise)
                if ($applyPricing && $costPrice > 0) {
                    $tiers = [
                        'starter' => $starterMargin,
                        'pro' => $proMargin,
                        'enterprise' => $enterpriseMargin,
                    ];

                    foreach ($tiers as $slug => $margin) {
                        $subPlan = $plansBySlug->get($slug);
                        if (! $subPlan) {
                            continue;
                        }

                        if ($marginType === 'percentage') {
                            $tierPrice = $costPrice * (1 + ($margin / 100));
                        } else {
                            $tierPrice = $costPrice + $margin;
                        }

                        if ($roundTo === '5') {
                            $tierPrice = round($tierPrice / 5) * 5;
                        } elseif ($roundTo === '10') {
                            $tierPrice = round($tierPrice / 10) * 10;
                        }

                        PlanDataPrice::updateOrCreate(
                            [
                                'plan_id' => $subPlan->id,
                                'data_plan_id' => $dataPlan->id,
                            ],
                            [
                                'wholesale_price' => round($tierPrice, 2),
                            ]
                        );
                    }
                }

                $syncedPlanIds[] = $dataPlan->id;
                $syncedCount++;
            }

            // 7. Delete stale plans if requested
            if ($deleteStale && ! empty($networkIds) && ! empty($syncedPlanIds)) {
                $deletedCount = DataPlan::query()
                    ->whereIn('network_id', array_keys($networkIds))
                    ->whereNotIn('id', $syncedPlanIds)
                    ->delete();
            }
        });

        $message = "Successfully synced {$syncedCount} data plans across ".count($networkIds).' networks.';
        if ($deletedCount > 0) {
            $message .= " Removed {$deletedCount} stale plans no longer offered by VTULab.";
        }

        return [
            'success' => true,
            'count' => $syncedCount,
            'deleted' => $deletedCount,
            'networks' => count($networkIds),
            'message' => $message,
        ];
    }
}
