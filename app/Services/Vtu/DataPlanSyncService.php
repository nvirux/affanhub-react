<?php

namespace App\Services\Vtu;

use App\Models\DataPlan;
use App\Models\DataType;
use App\Models\Network;
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
     */
    public function sync(?array $overrideData = null): array
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

        $syncedCount = 0;
        $networkIds = [];

        DB::transaction(function () use ($data, &$syncedCount, &$networkIds) {
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

                // 4. Resolve Prices
                $costPrice = (float) ($item['price'] ?? 0);
                $retailPrice = (float) ($item['regular_price'] ?? $costPrice);

                // 5. Update or Create DataPlan
                DataPlan::updateOrCreate(
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
                        'selling_price' => $retailPrice,
                        'default_retail_price' => $retailPrice,
                        'is_best_offer' => (bool) ($item['is_best_offer'] ?? false),
                        'is_active' => (bool) ($item['is_available'] ?? true),
                    ]
                );

                $syncedCount++;
            }
        });

        return [
            'success' => true,
            'count' => $syncedCount,
            'networks' => count($networkIds),
            'message' => "Successfully synced {$syncedCount} data plans across ".count($networkIds).' networks.',
        ];
    }
}
