<?php

namespace App\Services;

use App\Models\StorePushNotification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OneSignalService
{
    protected ?string $appId;

    protected ?string $apiKey;

    public function __construct()
    {
        $this->appId = config('services.onesignal.app_id');
        $this->apiKey = config('services.onesignal.rest_api_key');
    }

    /**
     * Check if OneSignal is configured.
     */
    public function isConfigured(): bool
    {
        return ! empty($this->appId) && ! empty($this->apiKey);
    }

    /**
     * Broadcast a push notification to all customers/users of a specific store.
     *
     * @return array{success: bool, recipients: int, id: ?string, error: ?string}
     */
    public function sendToStore(
        int|string $storeId,
        string $title,
        string $message,
        ?string $actionUrl = null,
        ?int $sentById = null
    ): array {
        if (! $this->isConfigured()) {
            Log::warning('OneSignal push aborted: ONESIGNAL_APP_ID or ONESIGNAL_REST_API_KEY is not configured in services.');

            StorePushNotification::create([
                'store_id' => $storeId,
                'title' => $title,
                'message' => $message,
                'action_url' => $actionUrl,
                'target_audience' => 'all',
                'status' => 'failed',
                'recipients_count' => 0,
                'failure_reason' => 'OneSignal API credentials are missing in system configuration.',
                'sent_by_id' => $sentById,
            ]);

            return [
                'success' => false,
                'recipients' => 0,
                'id' => null,
                'error' => 'OneSignal credentials are not configured.',
            ];
        }

        $payload = [
            'app_id' => $this->appId,
            // Filter targets devices that belong to this store
            'filters' => [
                [
                    'field' => 'tag',
                    'key' => 'store_id',
                    'relation' => '=',
                    'value' => (string) $storeId,
                ],
            ],
            'headings' => ['en' => $title],
            'contents' => ['en' => $message],
        ];

        if (! empty($actionUrl)) {
            $payload['data'] = ['url' => $actionUrl];
            $payload['url'] = $actionUrl;
        }

        try {
            $authHeader = str_starts_with((string) $this->apiKey, 'Basic ') || str_starts_with((string) $this->apiKey, 'Key ')
                ? $this->apiKey
                : 'Key '.$this->apiKey;

            $response = Http::withHeaders([
                'Authorization' => $authHeader,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->timeout(15)->post('https://onesignal.com/api/v1/notifications', $payload);

            $data = $response->json() ?? [];

            if ($response->successful() && empty($data['errors'])) {
                $notificationId = $data['id'] ?? null;
                $recipients = (int) ($data['recipients'] ?? 0);

                StorePushNotification::create([
                    'store_id' => $storeId,
                    'title' => $title,
                    'message' => $message,
                    'action_url' => $actionUrl,
                    'target_audience' => 'all',
                    'status' => 'sent',
                    'recipients_count' => $recipients,
                    'onesignal_id' => $notificationId,
                    'sent_by_id' => $sentById,
                ]);

                Log::info("OneSignal push notification sent for store #{$storeId}: {$notificationId} to {$recipients} recipients.");

                return [
                    'success' => true,
                    'recipients' => $recipients,
                    'id' => $notificationId,
                    'error' => null,
                ];
            }

            $errorMessage = is_array($data['errors'] ?? null)
                ? implode(', ', $data['errors'])
                : ($data['errors'] ?? $response->body());

            StorePushNotification::create([
                'store_id' => $storeId,
                'title' => $title,
                'message' => $message,
                'action_url' => $actionUrl,
                'target_audience' => 'all',
                'status' => 'failed',
                'recipients_count' => 0,
                'failure_reason' => $errorMessage,
                'sent_by_id' => $sentById,
            ]);

            Log::error("OneSignal push notification failed for store #{$storeId}: {$errorMessage}");

            return [
                'success' => false,
                'recipients' => 0,
                'id' => null,
                'error' => $errorMessage,
            ];
        } catch (\Throwable $e) {
            Log::error("Exception sending OneSignal notification for store #{$storeId}: {$e->getMessage()}");

            StorePushNotification::create([
                'store_id' => $storeId,
                'title' => $title,
                'message' => $message,
                'action_url' => $actionUrl,
                'target_audience' => 'all',
                'status' => 'failed',
                'recipients_count' => 0,
                'failure_reason' => $e->getMessage(),
                'sent_by_id' => $sentById,
            ]);

            return [
                'success' => false,
                'recipients' => 0,
                'id' => null,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Send a notification to a specific customer of a store (e.g. order update, wallet funding).
     *
     * @return array{success: bool, recipients: int, id: ?string, error: ?string}
     */
    public function sendToCustomer(
        int|string $userId,
        int|string $storeId,
        string $title,
        string $message,
        ?string $actionUrl = null
    ): array {
        if (! $this->isConfigured()) {
            return [
                'success' => false,
                'recipients' => 0,
                'id' => null,
                'error' => 'OneSignal credentials are not configured.',
            ];
        }

        $payload = [
            'app_id' => $this->appId,
            'filters' => [
                [
                    'field' => 'tag',
                    'key' => 'store_id',
                    'relation' => '=',
                    'value' => (string) $storeId,
                ],
                [
                    'operator' => 'AND',
                ],
                [
                    'field' => 'tag',
                    'key' => 'user_id',
                    'relation' => '=',
                    'value' => (string) $userId,
                ],
            ],
            'headings' => ['en' => $title],
            'contents' => ['en' => $message],
        ];

        if (! empty($actionUrl)) {
            $payload['data'] = ['url' => $actionUrl];
            $payload['url'] = $actionUrl;
        }

        try {
            $authHeader = str_starts_with((string) $this->apiKey, 'Basic ') || str_starts_with((string) $this->apiKey, 'Key ')
                ? $this->apiKey
                : 'Key '.$this->apiKey;

            $response = Http::withHeaders([
                'Authorization' => $authHeader,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->timeout(15)->post('https://onesignal.com/api/v1/notifications', $payload);

            $data = $response->json() ?? [];

            return [
                'success' => $response->successful() && empty($data['errors']),
                'recipients' => (int) ($data['recipients'] ?? 0),
                'id' => $data['id'] ?? null,
                'error' => $data['errors'][0] ?? null,
            ];
        } catch (\Throwable $e) {
            Log::error("OneSignal single user notification exception: {$e->getMessage()}");

            return [
                'success' => false,
                'recipients' => 0,
                'id' => null,
                'error' => $e->getMessage(),
            ];
        }
    }
}
