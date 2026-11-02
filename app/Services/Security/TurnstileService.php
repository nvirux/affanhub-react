<?php

namespace App\Services\Security;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TurnstileService
{
    public const SITEVERIFY_URL = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';

    /**
     * Verify a Turnstile response token against Cloudflare's siteverify API.
     */
    public function verify(?string $token, ?string $clientIp = null, ?string $expectedAction = null): bool
    {
        // Bypass in unit/feature testing
        if (app()->environment('testing')) {
            return true;
        }

        $secret = config('services.turnstile.secret_key');

        if (empty($secret)) {
            Log::warning('Turnstile verification skipped: TURNSTILE_SECRET_KEY is not configured.');

            return true;
        }

        if (empty($token) || strlen($token) > 2048) {
            return false;
        }

        try {
            $response = Http::asForm()
                ->timeout(10)
                ->post(self::SITEVERIFY_URL, array_filter([
                    'secret' => $secret,
                    'response' => $token,
                    'remoteip' => $clientIp ?: request()->ip(),
                ]));

            if (! $response->successful()) {
                Log::warning('Turnstile siteverify HTTP failure', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return false;
            }

            $data = $response->json();

            if (! ($data['success'] ?? false)) {
                Log::info('Turnstile verification rejected', [
                    'error_codes' => $data['error-codes'] ?? [],
                ]);

                return false;
            }

            if ($expectedAction !== null && ($data['action'] ?? null) !== $expectedAction) {
                Log::warning('Turnstile action mismatch', [
                    'expected' => $expectedAction,
                    'received' => $data['action'] ?? null,
                ]);

                return false;
            }

            return true;
        } catch (\Throwable $e) {
            Log::error('Turnstile verification exception: '.$e->getMessage());

            return false;
        }
    }
}
