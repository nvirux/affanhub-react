<?php

namespace App\Services\Vtu;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class VtuLabService
{
    protected string $baseUrl;

    protected ?string $apiKey;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('services.vtulab.base_url', 'https://api.vtulab.com/v1'), '/');
        $this->apiKey = config('services.vtulab.api_key');
    }

    /**
     * Purchase a data plan via VTULab API matching exact payload specification:
     * POST https://api.vtulab.com/v1/data
     * Body: { "plan_id": 5, "phone": "07043838371", "reference": "DATA_..." }
     *
     * @param  int|string  $planId  (VTULab plan_id e.g. 5)
     * @param  string  $phone  (11-digit local phone number)
     * @param  string  $reference  (Unique reference)
     */
    public function purchaseData($planId, string $phone, string $reference): array
    {
        $endpoint = $this->baseUrl.'/data';

        $payload = [
            'plan_id' => (int) $planId,
            'phone' => (string) $phone,
            'reference' => (string) $reference,
        ];

        Log::info('VTULab Data Purchase Payload:', ['endpoint' => $endpoint, 'payload' => $payload]);

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer '.$this->apiKey,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])->timeout(30)->post($endpoint, $payload);

            $resData = $response->json() ?? [];

            Log::info('VTULab Data Response:', [
                'status_code' => $response->status(),
                'response' => $resData,
            ]);

            $isApiSuccess = ($resData['success'] ?? false) === true || ($resData['code'] ?? '') === 'SUCCESS';
            $innerStatus = strtolower($resData['data']['status'] ?? '');
            $isSuccessful = $isApiSuccess && in_array($innerStatus, ['successful', 'success', 'completed']);
            $isPending = $isApiSuccess && in_array($innerStatus, ['pending', 'processing']);

            if ($isSuccessful || $isPending) {
                return [
                    'success' => true,
                    'pending' => $isPending,
                    'status' => $isPending ? 'pending' : 'successful',
                    'message' => $resData['message'] ?? 'Data purchase successful.',
                    'reference' => $resData['data']['reference'] ?? $reference,
                    'raw' => $resData,
                ];
            }

            return [
                'success' => false,
                'pending' => false,
                'status' => 'failed',
                'message' => $resData['message'] ?? ($resData['errors'][0] ?? 'VTULab data purchase failed.'),
                'reference' => $reference,
                'raw' => $resData,
            ];
        } catch (\Throwable $e) {
            Log::error('VTULab API HTTP Exception: '.$e->getMessage(), ['reference' => $reference]);

            return [
                'success' => false,
                'pending' => false,
                'status' => 'failed',
                'message' => 'Connection timeout or gateway error: '.$e->getMessage(),
                'reference' => $reference,
                'raw' => [],
            ];
        }
    }

    /**
     * Purchase airtime via VTULab API:
     * POST https://api.vtulab.com/v1/airtime
     * Body: { "network_id": 1, "phone": "07043838371", "amount": 500, "reference": "AIRTIME_..." }
     *
     * @param  int|string  $networkId  (VTULab network identifier or slug)
     * @param  float|int  $amount  (Airtime face value amount)
     * @param  string  $phone  (11-digit local phone number)
     * @param  string  $reference  (Unique reference)
     */
    public function purchaseAirtime($networkId, $amount, string $phone, string $reference): array
    {
        $endpoint = $this->baseUrl.'/airtime';

        $payload = [
            'network' => strtolower((string) $networkId),
            'phone' => (string) $phone,
            'amount' => (float) $amount,
            'reference' => (string) $reference,
        ];

        Log::info('VTULab Airtime Purchase Payload:', ['endpoint' => $endpoint, 'payload' => $payload]);

        if (empty($this->apiKey) && config('services.vtulab.is_sandbox', true)) {
            Log::info('VTULab Sandbox Mock: Successful Airtime Simulation for '.$reference);

            return [
                'success' => true,
                'pending' => false,
                'status' => 'successful',
                'message' => 'Airtime recharge successful (Sandbox).',
                'reference' => $reference,
                'raw' => ['code' => 'SUCCESS', 'message' => 'Mock successful', 'data' => ['status' => 'successful', 'reference' => $reference]],
            ];
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer '.$this->apiKey,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])->timeout(30)->post($endpoint, $payload);

            $resData = $response->json() ?? [];

            Log::info('VTULab Airtime Response:', [
                'status_code' => $response->status(),
                'response' => $resData,
            ]);

            $isApiSuccess = ($resData['success'] ?? false) === true || ($resData['code'] ?? '') === 'SUCCESS';
            $innerStatus = strtolower($resData['data']['status'] ?? '');
            $isSuccessful = $isApiSuccess && in_array($innerStatus, ['successful', 'success', 'completed']);
            $isPending = $isApiSuccess && in_array($innerStatus, ['pending', 'processing']);

            if ($isSuccessful || $isPending) {
                return [
                    'success' => true,
                    'pending' => $isPending,
                    'status' => $isPending ? 'pending' : 'successful',
                    'message' => $resData['message'] ?? 'Airtime purchase successful.',
                    'reference' => $resData['data']['reference'] ?? $reference,
                    'raw' => $resData,
                ];
            }

            return [
                'success' => false,
                'pending' => false,
                'status' => 'failed',
                'message' => $resData['message'] ?? ($resData['errors'][0] ?? 'VTULab airtime purchase failed.'),
                'reference' => $reference,
                'raw' => $resData,
            ];
        } catch (\Throwable $e) {
            Log::error('VTULab Airtime API HTTP Exception: '.$e->getMessage(), ['reference' => $reference]);

            return [
                'success' => false,
                'pending' => false,
                'status' => 'failed',
                'message' => 'Connection timeout or gateway error: '.$e->getMessage(),
                'reference' => $reference,
                'raw' => [],
            ];
        }
    }

    /**
     * Query VTULab account balance.
     */
    public function checkBalance(): array
    {
        $endpoint = $this->baseUrl.'/user';

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer '.$this->apiKey,
                'Accept' => 'application/json',
            ])->timeout(15)->get($endpoint);

            return [
                'success' => $response->successful(),
                'data' => $response->json(),
            ];
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Fetch all available data plans from VTULab API.
     * GET {{base_url}}/data/plans
     */
    public function fetchPlans(): array
    {
        $endpoint = $this->baseUrl.'/data/plans';

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer '.$this->apiKey,
                'Accept' => 'application/json',
            ])->timeout(30)->get($endpoint);

            $resData = $response->json() ?? [];

            Log::info('VTULab Fetch Plans Response:', [
                'status_code' => $response->status(),
                'total' => count($resData['data'] ?? []),
            ]);

            return $resData;
        } catch (\Throwable $e) {
            Log::error('VTULab Fetch Plans Exception: '.$e->getMessage());

            return [
                'success' => false,
                'message' => 'Connection error while fetching plans: '.$e->getMessage(),
                'data' => [],
            ];
        }
    }
}
