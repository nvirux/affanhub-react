<?php

namespace App\Services\Domain;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DnsVerificationService
{
    /**
     * Verify domain ownership via TXT record.
     */
    public function verifyOwnership(string $domain, string $token): array
    {
        return $this->verifyTxtToken($domain, $token);
    }

    /**
     * Verify domain ownership via TXT record.
     * Uses multiple strategies for maximum reliability:
     * 1. dns_get_record(DNS_TXT)
     * 2. dns_get_record(DNS_ALL) fallback
     * 3. shell dig fallback
     */
    public function verifyTxtToken(string $domain, string $token): array
    {
        if (empty($token)) {
            return [
                'status' => false,
                'message' => 'Verification token is empty.',
            ];
        }

        $domain = $this->normalizeDomain($domain);

        // Local environment bypass for testing dummy domains
        if (config('app.env') === 'local' && (str_contains($domain, 'example.com') || str_contains($domain, 'test') || str_contains($domain, 'localhost'))) {
            return [
                'status' => true,
                'message' => 'Ownership verified via Local Bypass.',
                'checked_host' => "_affanhub-verify.{$domain}",
                'expected_token' => $token,
                'found_txt_values' => [$token],
                'found_values' => [$token],
                'dig_debug' => 'Local bypass active',
            ];
        }
        $verifyHost = "_affanhub-verify.{$domain}";
        $expectedToken = trim($token);
        $foundTxtValues = [];
        $debug = [
            'checked_host' => $verifyHost,
            'expected_token' => $expectedToken,
            'found_txt_values' => [],
            'dns_txt_records' => [],
            'dns_all_records' => [],
            'dig_output' => null,
        ];

        $dnsTxtRecords = $this->getDnsRecords($verifyHost, DNS_TXT);
        $debug['dns_txt_records'] = $dnsTxtRecords;
        $foundTxtValues = array_merge($foundTxtValues, $this->extractTxtValues($dnsTxtRecords, false));

        if (! in_array($expectedToken, $foundTxtValues, true)) {
            $dnsAllRecords = $this->getDnsRecords($verifyHost, DNS_ALL);
            $debug['dns_all_records'] = $dnsAllRecords;
            $foundTxtValues = array_merge($foundTxtValues, $this->extractTxtValues($dnsAllRecords, true));
        }

        if (! in_array($expectedToken, $foundTxtValues, true)) {
            $digOutput = $this->runDigTxtQuery($verifyHost);
            $debug['dig_output'] = $digOutput;

            if (filled($digOutput)) {
                foreach (preg_split('/\r\n|\r|\n/', trim($digOutput)) as $line) {
                    $normalized = $this->normalizeTxtValue($line);

                    if ($normalized !== '') {
                        $foundTxtValues[] = $normalized;
                    }
                }
            }
        }

        $foundTxtValues = array_values(array_unique(array_filter($foundTxtValues, fn ($value) => $value !== '')));
        $debug['found_txt_values'] = $foundTxtValues;

        if (in_array($expectedToken, $foundTxtValues, true)) {
            return [
                'status' => true,
                'message' => 'Ownership verified via TXT record.',
                'checked_host' => $verifyHost,
                'expected_token' => $expectedToken,
                'found_txt_values' => $foundTxtValues,
                'found_values' => $foundTxtValues,
                'dig_debug' => $debug['dig_output'],
            ];
        }

        Log::info('Domain TXT ownership check (informational).', $debug);

        return [
            'status' => false,
            'message' => count($foundTxtValues) > 0
                ? 'TXT record found but token does not match.'
                : 'No TXT record found for '.$verifyHost,
            'checked_host' => $verifyHost,
            'expected_token' => $expectedToken,
            'found_txt_values' => $foundTxtValues,
            'found_values' => $foundTxtValues,
            'dig_debug' => $debug['dig_output'],
        ];
    }

    /**
     * Normalize TXT value by removing quotes, joining split chunks, and trimming.
     */
    private function normalizeTxtValue($value): string
    {
        if (is_array($value)) {
            $value = implode('', array_map(function ($part) {
                return trim((string) $part, "\"'");
            }, $value));
        }

        $value = (string) $value;
        $value = preg_replace('/"\s+"/', '', $value) ?? $value;
        $value = str_replace(['"', "'"], '', $value);

        return trim($value);
    }

    /**
     * Verify origin connection via HTTP fingerprint header.
     * Checks for X-AffanHub-Origin: true
     */
    public function verifyOriginFingerprint(string $domain): array
    {
        $domain = $this->normalizeDomain($domain);
        $attempts = [
            "https://{$domain}/",
            "http://{$domain}/",
        ];
        $attemptDiagnostics = [];
        $lastReachableAttempt = null;
        $lastResolvedIp = gethostbyname($domain);

        foreach ($attempts as $url) {
            try {
                $response = Http::timeout(12)
                    ->connectTimeout(5)
                    ->withOptions([
                        'allow_redirects' => true,
                    ])
                    ->withHeaders(['User-Agent' => 'AffanHub-Origin-Scanner/2.0'])
                    ->get($url);

                $status = $response->status();
                $hasHeader = $response->header('X-AffanHub-Origin') === 'true';
                $isCloudflare = str_contains(strtolower((string) $response->header('Server')), 'cloudflare');

                $attemptDiagnostics[] = [
                    'url' => $url,
                    'http_status' => $status,
                    'has_origin_header' => $hasHeader,
                    'cloudflare_detected' => $isCloudflare,
                ];

                if ($hasHeader) {
                    return [
                        'status' => true,
                        'http_status' => $status,
                        'cloudflare_detected' => $isCloudflare,
                        'message' => 'Origin fingerprint verified successfully.',
                        'resolved_ip' => $lastResolvedIp,
                        'checked_url' => $url,
                        'checked_urls' => array_column($attemptDiagnostics, 'url'),
                        'attempts' => $attemptDiagnostics,
                    ];
                }

                $lastReachableAttempt = [
                    'http_status' => $status,
                    'cloudflare_detected' => $isCloudflare,
                    'url' => $url,
                ];
            } catch (ConnectionException $e) {
                $attemptDiagnostics[] = [
                    'url' => $url,
                    'error' => $e->getMessage(),
                ];
            } catch (\Throwable $e) {
                $attemptDiagnostics[] = [
                    'url' => $url,
                    'error' => $e->getMessage(),
                ];
            }
        }

        if ($lastReachableAttempt !== null) {
            return [
                'status' => false,
                'http_status' => $lastReachableAttempt['http_status'],
                'cloudflare_detected' => $lastReachableAttempt['cloudflare_detected'],
                'message' => 'Domain reached, but X-AffanHub-Origin header is missing.',
                'resolved_ip' => $lastResolvedIp,
                'checked_url' => $lastReachableAttempt['url'],
                'checked_urls' => array_column($attemptDiagnostics, 'url'),
                'attempts' => $attemptDiagnostics,
            ];
        }

        $lastError = collect($attemptDiagnostics)->pluck('error')->filter()->last() ?? 'Unknown connectivity failure.';

        // Information only, non-blocking
        Log::info('Domain origin fingerprint check (informational).', [
            'domain' => $domain,
            'resolved_ip' => $lastResolvedIp,
            'attempts' => $attemptDiagnostics,
        ]);

        return [
            'status' => false,
            'http_status' => null,
            'cloudflare_detected' => false,
            'message' => 'Connectivity failed: '.$lastError,
            'resolved_ip' => $lastResolvedIp,
            'checked_url' => null,
            'checked_urls' => array_column($attemptDiagnostics, 'url'),
            'attempts' => $attemptDiagnostics,
        ];
    }

    /**
     * Optional CNAME check (kept as helper/secondary diagnostic)
     */
    public function checkCname(string $domain, string $target): array
    {
        $domain = $this->normalizeDomain($domain);
        $records = @dns_get_record($domain, DNS_CNAME);

        if ($records) {
            foreach ($records as $record) {
                if (strtolower($record['target']) === strtolower($target)) {
                    return ['status' => true, 'message' => 'CNAME matches target.'];
                }
            }
        }

        return ['status' => false, 'message' => 'CNAME not found or mismatched.'];
    }

    public function normalizeDomain(string $domain): string
    {
        $domain = strtolower($domain);
        $domain = preg_replace('/^https?:\/\//i', '', $domain);
        $domain = explode('/', $domain)[0];

        return trim($domain, '. ');
    }

    protected function getDnsRecords(string $host, int $type): array
    {
        try {
            return @dns_get_record($host, $type) ?: [];
        } catch (\Throwable) {
            return [];
        }
    }

    protected function runDigTxtQuery(string $host): ?string
    {
        if (! function_exists('shell_exec')) {
            return null;
        }

        try {
            $output = @shell_exec('dig +short TXT '.escapeshellarg($host).' 2>&1');

            if (! is_string($output)) {
                return null;
            }

            $output = trim($output);

            if ($output === '' ||
                str_contains($output, 'command not found') ||
                str_contains($output, 'not recognized') ||
                str_contains($output, 'connection timed out')) {
                return null;
            }

            return $output;
        } catch (\Throwable) {
            return null;
        }
    }

    protected function extractTxtValues(array $records, bool $txtOnly): array
    {
        $values = [];

        foreach ($records as $record) {
            if ($txtOnly && (($record['type'] ?? null) !== 'TXT')) {
                continue;
            }

            $raw = $record['txt'] ?? $record['entries'] ?? null;
            $normalized = $this->normalizeTxtValue($raw);

            if ($normalized !== '') {
                $values[] = $normalized;
            }
        }

        return $values;
    }
}
