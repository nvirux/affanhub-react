<?php

namespace App\Services\Cloudflare;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CloudflareSaaSService
{
    protected string $zoneId;

    protected string $apiToken;

    public function __construct()
    {
        $this->zoneId = (string) config('services.cloudflare.zone_id', env('CLOUDFLARE_ZONE_ID', ''));
        $this->apiToken = (string) config('services.cloudflare.api_token', env('CLOUDFLARE_API_TOKEN', ''));
    }

    /**
     * Check if Cloudflare for SaaS API credentials are configured.
     */
    public function isConfigured(): bool
    {
        return filled($this->zoneId) && filled($this->apiToken);
    }

    /**
     * Register a custom hostname in Cloudflare for SaaS with HTTP validation.
     *
     * @return array{success: bool, id?: string, status?: string, message?: string}
     */
    public function createCustomHostname(string $hostname): array
    {
        if (! $this->isConfigured()) {
            return [
                'success' => false,
                'message' => 'Cloudflare Zone ID or API Token is not configured.',
            ];
        }

        $hostname = strtolower(trim($hostname));

        try {
            $response = Http::withToken($this->apiToken)
                ->post("https://api.cloudflare.com/client/v4/zones/{$this->zoneId}/custom_hostnames", [
                    'hostname' => $hostname,
                    'ssl' => [
                        'method' => 'http',
                        'type' => 'dv',
                        'settings' => [
                            'min_tls_version' => '1.2',
                        ],
                    ],
                ]);

            $json = $response->json();

            if ($response->successful() && ($json['success'] ?? false)) {
                $result = $json['result'] ?? [];

                return [
                    'success' => true,
                    'id' => $result['id'] ?? null,
                    'status' => $result['status'] ?? 'pending',
                    'message' => 'Custom hostname created successfully.',
                ];
            }

            // If hostname already exists, treat as success and fetch it
            $errors = $json['errors'] ?? [];
            foreach ($errors as $error) {
                if (($error['code'] ?? null) === 1437 || str_contains($error['message'] ?? '', 'already exists')) {
                    return [
                        'success' => true,
                        'message' => 'Custom hostname already registered in Cloudflare for SaaS.',
                    ];
                }
            }

            Log::warning("Cloudflare createCustomHostname failed for {$hostname}", [
                'status' => $response->status(),
                'response' => $json,
            ]);

            return [
                'success' => false,
                'message' => $errors[0]['message'] ?? 'Failed to create Cloudflare Custom Hostname.',
            ];
        } catch (\Throwable $e) {
            Log::error("Cloudflare createCustomHostname exception for {$hostname}: ".$e->getMessage(), [
                'exception' => $e,
            ]);

            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Retrieve status of a custom hostname.
     */
    public function getCustomHostname(string $hostname): ?array
    {
        if (! $this->isConfigured()) {
            return null;
        }

        $hostname = strtolower(trim($hostname));

        try {
            $response = Http::withToken($this->apiToken)
                ->get("https://api.cloudflare.com/client/v4/zones/{$this->zoneId}/custom_hostnames", [
                    'hostname' => $hostname,
                ]);

            if ($response->successful()) {
                $result = $response->json()['result'] ?? [];

                return ! empty($result) ? $result[0] : null;
            }

            return null;
        } catch (\Throwable $e) {
            Log::error("Cloudflare getCustomHostname exception for {$hostname}: ".$e->getMessage());

            return null;
        }
    }

    /**
     * Delete a custom hostname from Cloudflare.
     */
    public function deleteCustomHostname(string $hostnameId): bool
    {
        if (! $this->isConfigured() || empty($hostnameId)) {
            return false;
        }

        try {
            $response = Http::withToken($this->apiToken)
                ->delete("https://api.cloudflare.com/client/v4/zones/{$this->zoneId}/custom_hostnames/{$hostnameId}");

            return $response->successful() && ($response->json()['success'] ?? false);
        } catch (\Throwable $e) {
            Log::error("Cloudflare deleteCustomHostname exception for ID {$hostnameId}: ".$e->getMessage());

            return false;
        }
    }
}
