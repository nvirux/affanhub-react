<?php

namespace App\Services\Identity;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class IdCoreService
{
    protected string $baseUrl;

    protected ?string $apiKey;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('services.idcore.base_url', 'https://api.idcore.africa/v1'), '/');
        $this->apiKey = config('services.idcore.api_key');
    }

    /**
     * Map internal slip slug to IDCore's official slip slug.
     */
    public static function resolveSlipSlug(string $service, ?string $internalSlug): ?string
    {
        if (empty($internalSlug)) {
            return null;
        }

        if ($service === 'nin' || $service === 'nin_verification') {
            return match ($internalSlug) {
                'information', 'nin-information' => 'nin-information',
                'regular', 'nin-regular' => 'nin-regular',
                'standard', 'standard_nin', 'nin-standard' => 'nin-standard',
                'premium', 'premium_nin', 'nin-premium' => 'nin-premium',
                default => $internalSlug,
            };
        }

        if ($service === 'bvn' || $service === 'bvn_verification') {
            return match ($internalSlug) {
                'basic', 'bvn-basic' => 'bvn-basic',
                'advance', 'bvn-advance' => 'bvn-advance',
                'plastic', 'bvn-plastic' => 'bvn-plastic',
                default => $internalSlug,
            };
        }

        return $internalSlug;
    }

    /**
     * Verify NIN via IDCore API (by 11-digit NIN or registered 11-digit phone number).
     */
    public function verifyNin(string $searchType, string $searchValue, ?string $slipSlug = null, string $tier = 'advance'): array
    {
        $endpoint = $this->baseUrl.'/verifications/nin';

        $payload = [
            $searchType === 'phone' ? 'phone' : 'nin' => $searchValue,
            'type' => $tier,
        ];

        $resolvedSlug = self::resolveSlipSlug('nin', $slipSlug);
        if (! empty($resolvedSlug)) {
            $payload['slip_slug'] = $resolvedSlug;
        }

        Log::info('IDCore NIN Verification Request:', [
            'endpoint' => $endpoint,
            'search_type' => $searchType,
            'payload' => array_merge($payload, [$searchType === 'phone' ? 'phone' : 'nin' => '***'.substr($searchValue, -4)]),
        ]);

        return $this->sendPostRequest($endpoint, $payload, 'NIN');
    }

    /**
     * Verify BVN via IDCore API.
     */
    public function verifyBvn(string $bvn, ?string $slipSlug = null, string $tier = 'advance'): array
    {
        $endpoint = $this->baseUrl.'/verifications/bvn';

        $payload = [
            'bvn' => $bvn,
            'type' => $tier,
        ];

        $resolvedSlug = self::resolveSlipSlug('bvn', $slipSlug);
        if (! empty($resolvedSlug)) {
            $payload['slip_slug'] = $resolvedSlug;
        }

        Log::info('IDCore BVN Verification Request:', [
            'endpoint' => $endpoint,
            'bvn' => '***'.substr($bvn, -4),
            'slip_slug' => $resolvedSlug,
        ]);

        return $this->sendPostRequest($endpoint, $payload, 'BVN');
    }

    /**
     * Download binary PDF verification slip from IDCore.
     */
    public function downloadSlip(string $providerReference): array
    {
        $endpoint = $this->baseUrl."/verifications/{$providerReference}/slip";

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer '.$this->apiKey,
                'Accept' => 'application/pdf, application/json',
            ])->timeout(45)->get($endpoint);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'pdf_content' => $response->body(),
                    'content_type' => $response->header('Content-Type') ?: 'application/pdf',
                ];
            }

            $errorData = $response->json() ?? [];
            $message = $errorData['message'] ?? 'Unable to retrieve verification slip from IDCore.';

            return [
                'success' => false,
                'message' => $message,
                'status_code' => $response->status(),
            ];
        } catch (Throwable $e) {
            Log::error('IDCore Slip Download Exception:', [
                'endpoint' => $endpoint,
                'message' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Connection to IDCore slip service timed out.',
                'status_code' => 500,
            ];
        }
    }

    /**
     * Query existing verification record from IDCore by reference.
     */
    public function getVerification(string $providerReference): array
    {
        $endpoint = $this->baseUrl."/verifications/{$providerReference}";

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer '.$this->apiKey,
                'Accept' => 'application/json',
            ])->timeout(30)->get($endpoint);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json(),
                ];
            }

            return [
                'success' => false,
                'message' => $response->json('message') ?? 'Verification not found.',
                'status_code' => $response->status(),
            ];
        } catch (Throwable $e) {
            Log::error('IDCore Query Exception:', [
                'endpoint' => $endpoint,
                'message' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Failed to reach IDCore service.',
                'status_code' => 500,
            ];
        }
    }

    /**
     * Send authenticated JSON POST request to IDCore with Bearer token.
     */
    protected function sendPostRequest(string $endpoint, array $payload, string $serviceType): array
    {
        if (empty($this->apiKey)) {
            if (app()->environment('testing', 'local')) {
                return $this->getSimulatedResponse($serviceType, $payload);
            }

            Log::warning("IDCore API key is not configured for {$serviceType} verification.");

            return [
                'success' => false,
                'message' => 'Identity verification provider is not configured. Please contact administration.',
                'status_code' => 500,
            ];
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer '.$this->apiKey,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])->timeout(45)->post($endpoint, $payload);

            $statusCode = $response->status();
            $resData = $response->json() ?? [];

            Log::info("IDCore {$serviceType} Verification Response:", [
                'status_code' => $statusCode,
                'status' => $resData['status'] ?? null,
                'reference' => $resData['reference'] ?? null,
            ]);

            if ($response->successful() && ($resData['status'] ?? '') === 'success') {
                return [
                    'success' => true,
                    'reference' => $resData['reference'] ?? null,
                    'service' => $resData['service'] ?? null,
                    'tier' => $resData['tier'] ?? null,
                    'amount_charged' => (float) ($resData['amount_charged'] ?? 0.00),
                    'slip' => $resData['slip'] ?? null,
                    'data' => $resData['data'] ?? [],
                    'raw' => $resData,
                ];
            }

            // Extract human-friendly error message
            $errorMessage = $resData['message'] ?? 'Identity verification lookup failed with the upstream provider.';
            if (isset($resData['errors']) && is_array($resData['errors'])) {
                $flattened = collect($resData['errors'])->flatten()->first();
                if ($flattened) {
                    $errorMessage = $flattened;
                }
            }

            return [
                'success' => false,
                'message' => $errorMessage,
                'status_code' => $statusCode,
                'raw' => $resData,
            ];
        } catch (Throwable $e) {
            Log::error("IDCore {$serviceType} Exception:", [
                'endpoint' => $endpoint,
                'message' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Unable to connect to the identity verification provider. Please try again shortly.',
                'status_code' => 500,
            ];
        }
    }

    /**
     * Provide realistic simulated payload for local/testing environments when no API key is set.
     */
    protected function getSimulatedResponse(string $serviceType, array $payload): array
    {
        $ref = 'VRF-'.strtoupper($serviceType).'-API-'.strtoupper(substr(bin2hex(random_bytes(5)), 0, 10));
        $slipSlug = $payload['slip_slug'] ?? null;

        if ($serviceType === 'NIN') {
            $isPhone = isset($payload['phone']);
            $searchValue = $isPhone ? $payload['phone'] : ($payload['nin'] ?? '12345678901');

            return [
                'success' => true,
                'reference' => $ref,
                'service' => 'nin',
                'tier' => $payload['type'] ?? 'advance',
                'amount_charged' => 150.00,
                'slip' => $slipSlug ? [
                    'slug' => $slipSlug,
                    'name' => ucwords(str_replace('-', ' ', $slipSlug)),
                    'download_url' => $this->baseUrl."/verifications/{$ref}/slip",
                    'url' => $this->baseUrl."/verifications/{$ref}/slip",
                ] : null,
                'data' => [
                    'nin' => $isPhone ? '73948201938' : $searchValue,
                    'first_name' => 'MUSA',
                    'last_name' => 'BELLO',
                    'middle_name' => 'IBRAHIM',
                    'full_name' => 'MUSA IBRAHIM BELLO',
                    'date_of_birth' => '14 August 1994',
                    'gender' => 'Male',
                    'phone' => $isPhone ? $searchValue : '08031234567',
                    'address' => 'Plot 14, Commercial Avenue, Kano',
                    'state' => 'Kano',
                    'lga' => 'Nasarawa',
                    'tracking_id' => 'TRK'.rand(100000000, 999999999),
                    'photo' => null,
                    'status' => 'ACTIVE',
                ],
                'raw' => [],
            ];
        }

        // BVN
        $bvn = $payload['bvn'] ?? '22280005737';

        return [
            'success' => true,
            'reference' => $ref,
            'service' => 'bvn',
            'tier' => $payload['type'] ?? 'advance',
            'amount_charged' => 150.00,
            'slip' => $slipSlug ? [
                'slug' => $slipSlug,
                'name' => ucwords(str_replace('-', ' ', $slipSlug)),
                'download_url' => $this->baseUrl."/verifications/{$ref}/slip",
                'url' => $this->baseUrl."/verifications/{$ref}/slip",
            ] : null,
            'data' => [
                'bvn' => $bvn,
                'first_name' => 'FATIMA',
                'last_name' => 'GARBA',
                'middle_name' => 'ALIYU',
                'full_name' => 'FATIMA ALIYU GARBA',
                'date_of_birth' => '22 May 1996',
                'gender' => 'Female',
                'phone' => '08098765432',
                'enrollment_bank' => 'Guaranty Trust Bank',
                'enrollment_branch' => 'Maitama, Abuja',
                'address' => '24 Crescent Road, Maitama, Abuja',
                'state' => 'Abuja FCT',
                'lga' => 'Municipal',
                'photo' => null,
                'status' => 'VERIFIED',
            ],
            'raw' => [],
        ];
    }
}
