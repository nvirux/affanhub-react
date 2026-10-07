<?php

namespace App\Services\Cloudflare;

use App\Models\Domain;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CloudflareOAuthService
{
    protected string $clientId;

    protected string $clientSecret;

    protected string $redirectUri;

    protected string $fallbackCname;

    protected string $scopes;

    public function __construct()
    {
        $this->clientId = (string) config('services.cloudflare.client_id', '');
        $this->clientSecret = (string) config('services.cloudflare.client_secret', '');
        $this->redirectUri = (string) config('services.cloudflare.redirect_uri', '');
        $this->fallbackCname = (string) config('services.cloudflare.fallback_cname', 'cname.affanhub.com');
        $this->scopes = (string) config('services.cloudflare.scopes', 'dns.read dns.write zone.read zone.write');
    }

    /**
     * Check if Cloudflare OAuth credentials are configured.
     */
    public function isConfigured(): bool
    {
        return filled($this->clientId) && filled($this->clientSecret) && filled($this->redirectUri);
    }

    /**
     * Generate PKCE code verifier.
     */
    public static function generateCodeVerifier(): string
    {
        return Str::random(64);
    }

    /**
     * Generate PKCE code challenge from verifier using SHA-256 and base64url encoding.
     */
    public static function generateCodeChallenge(string $codeVerifier): string
    {
        return rtrim(strtr(base64_encode(hash('sha256', $codeVerifier, true)), '+/', '-_'), '=');
    }

    /**
     * Build Cloudflare OAuth Authorization URL with PKCE and scopes.
     */
    public function getAuthorizationUrl(string $state, string $codeChallenge, ?string $scope = null): string
    {
        $params = [
            'client_id' => $this->clientId,
            'response_type' => 'code',
            'redirect_uri' => $this->redirectUri,
            'state' => $state,
            'code_challenge' => $codeChallenge,
            'code_challenge_method' => 'S256',
        ];

        $targetScope = $scope ?? $this->scopes;
        if (filled($targetScope)) {
            $params['scope'] = $targetScope;
        }

        return 'https://dash.cloudflare.com/oauth2/auth?'.http_build_query($params);
    }

    /**
     * Exchange authorization code for access token with PKCE verification.
     *
     * @return array{access_token: string, expires_in?: int, refresh_token?: string}|null
     */
    public function exchangeCodeForToken(string $code, ?string $codeVerifier = null): ?array
    {
        try {
            $data = [
                'grant_type' => 'authorization_code',
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'code' => $code,
                'redirect_uri' => $this->redirectUri,
            ];

            if (filled($codeVerifier)) {
                $data['code_verifier'] = $codeVerifier;
            }

            $response = Http::asForm()
                ->post('https://dash.cloudflare.com/oauth2/token', $data);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Cloudflare OAuth token exchange failed', [
                'status' => $response->status(),
                'body' => $response->json() ?? $response->body(),
            ]);

            return null;
        } catch (\Throwable $e) {
            Log::error('Cloudflare OAuth token exchange exception: '.$e->getMessage(), [
                'exception' => $e,
            ]);

            return null;
        }
    }

    /**
     * Retrieve all active zones in merchant's Cloudflare account.
     *
     * @return array<int, array{id: string, name: string}>
     */
    public function listZones(string $accessToken): array
    {
        try {
            $response = Http::withToken($accessToken)
                ->get('https://api.cloudflare.com/client/v4/zones', [
                    'status' => 'active',
                    'per_page' => 50,
                ]);

            if ($response->successful() && isset($response->json()['result'])) {
                return $response->json()['result'];
            }

            Log::warning('Cloudflare listZones returned non-successful response', [
                'status' => $response->status(),
                'body' => $response->json() ?? $response->body(),
            ]);

            return [];
        } catch (\Throwable $e) {
            Log::error('Cloudflare listZones exception: '.$e->getMessage());

            return [];
        }
    }

    /**
     * Find the best matching zone for a given domain name (handles apex and subdomains).
     */
    public function findZoneForDomain(string $accessToken, string $domainName): ?array
    {
        $zones = $this->listZones($accessToken);
        $domainName = strtolower(trim($domainName));

        // Exact match first
        foreach ($zones as $zone) {
            if (strtolower($zone['name']) === $domainName) {
                return $zone;
            }
        }

        // Subdomain match: e.g. domain is shop.example.com and zone is example.com
        foreach ($zones as $zone) {
            $zoneName = strtolower($zone['name']);
            if (str_ends_with($domainName, '.'.$zoneName)) {
                return $zone;
            }
        }

        return null;
    }

    /**
     * Automatically create or update the required CNAME & TXT DNS records for the domain.
     *
     * @return array{success: bool, message: string, zone_id?: string}
     */
    public function configureDnsRecords(string $accessToken, Domain $domain): array
    {
        $zone = $this->findZoneForDomain($accessToken, $domain->domain);

        if (! $zone) {
            return [
                'success' => false,
                'message' => "Could not find a Cloudflare zone for '{$domain->domain}' in your Cloudflare account. Please verify that this domain is active in your Cloudflare dashboard.",
            ];
        }

        $zoneId = $zone['id'];
        $cleanDomain = strtolower(trim($domain->domain));

        try {
            // 1. Create or update CNAME record for domain routing
            $this->upsertRecord($accessToken, $zoneId, [
                'type' => 'CNAME',
                'name' => $cleanDomain,
                'content' => $this->fallbackCname,
                'ttl' => 1, // Auto
                'proxied' => true,
            ]);

            // 2. Also ensure www. record if domain is apex (or vice versa)
            if (! str_starts_with($cleanDomain, 'www.')) {
                $wwwDomain = 'www.'.$cleanDomain;
                $this->upsertRecord($accessToken, $zoneId, [
                    'type' => 'CNAME',
                    'name' => $wwwDomain,
                    'content' => $this->fallbackCname,
                    'ttl' => 1,
                    'proxied' => true,
                ]);
            }

            // 3. Create or update TXT record for ownership verification
            if (filled($domain->verification_token)) {
                $verifyHost = "_affanhub-verify.{$cleanDomain}";
                $this->upsertRecord($accessToken, $zoneId, [
                    'type' => 'TXT',
                    'name' => $verifyHost,
                    'content' => $domain->verification_token,
                    'ttl' => 1,
                ]);
            }

            return [
                'success' => true,
                'message' => 'DNS records created successfully in your Cloudflare account.',
                'zone_id' => $zoneId,
            ];
        } catch (\Throwable $e) {
            Log::error("Failed to configure Cloudflare DNS records for {$domain->domain}: ".$e->getMessage(), [
                'exception' => $e,
            ]);

            return [
                'success' => false,
                'message' => 'Error communicating with Cloudflare DNS API: '.$e->getMessage(),
            ];
        }
    }

    /**
     * Upsert a DNS record in Cloudflare (updates if exists, creates if not).
     */
    protected function upsertRecord(string $accessToken, string $zoneId, array $recordData): void
    {
        $existing = Http::withToken($accessToken)
            ->get("https://api.cloudflare.com/client/v4/zones/{$zoneId}/dns_records", [
                'type' => $recordData['type'],
                'name' => $recordData['name'],
            ]);

        $existingRecords = $existing->successful() ? ($existing->json()['result'] ?? []) : [];

        if (! empty($existingRecords)) {
            // Update the first matching record
            $recordId = $existingRecords[0]['id'];
            Http::withToken($accessToken)
                ->put("https://api.cloudflare.com/client/v4/zones/{$zoneId}/dns_records/{$recordId}", $recordData);
        } else {
            // Create new record
            Http::withToken($accessToken)
                ->post("https://api.cloudflare.com/client/v4/zones/{$zoneId}/dns_records", $recordData);
        }
    }
}
