<?php

namespace App\Services\Domain;

use App\Models\Domain;
use App\Models\Store;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CustomDomainOnboardingService
{
    public function __construct(
        protected DnsVerificationService $dns
    ) {}

    public function submitDomain(Store $tenant, string $domainName): Domain
    {
        $domainName = $this->dns->normalizeDomain($domainName);

        // Conflict check: Check if domain is used by ANY other tenant (active or soft deleted)
        // We normalize by removing www. for the comparison to prevent root/www overlap across tenants
        $normalizedForComparison = preg_replace('/^www\./i', '', $domainName);

        $conflict = Domain::where(function ($query) use ($domainName, $normalizedForComparison) {
            $query->where('domain', $domainName)
                ->orWhere('domain', 'www.'.$normalizedForComparison)
                ->orWhere('domain', $normalizedForComparison);
        })
            ->where('tenant_id', '!=', $tenant->id)
            ->first();

        if ($conflict) {
            throw new \Exception('This domain is already connected to another AffanHub store. If you believe this is a mistake, please contact support.');
        }

        // Unset primary from existing custom domains for THIS tenant
        Domain::where('tenant_id', $tenant->id)
            ->where('is_primary', true)
            ->update(['is_primary' => false]);

        // Check for existing domain for THIS tenant
        $existing = Domain::where('tenant_id', $tenant->id)
            ->where('domain', $domainName)
            ->first();

        if ($existing) {
            // If it's already healthy/verified, don't reset it!
            if ($existing->isHealthy()) {
                $existing->update(['is_primary' => true]);

                return $existing;
            }

            $existing->update([
                'status' => Domain::STATUS_PENDING,
                'verification_status' => Domain::VERIFY_PENDING_DNS,
                'is_verified' => false,
                'is_approved' => false,
                'is_routing_enabled' => false,
                'is_primary' => true,
                'ownership_verified_at' => null,
                'connection_verified_at' => null,
                'last_verification_message' => 'Domain restored. Please verify ownership.',
                'verification_error' => null,
                'verification_attempts' => 0,
            ]);

            return $existing;
        }

        // Generate deterministic token
        $token = substr(hash_hmac('sha256', $tenant->id.'|'.$domainName, config('app.key')), 0, 32);

        return Domain::create([
            'tenant_id' => $tenant->id,
            'domain' => $domainName,
            'status' => Domain::STATUS_PENDING,
            'verification_status' => Domain::VERIFY_PENDING_DNS,
            'is_primary' => true,
            'is_verified' => false,
            'is_approved' => false,
            'is_routing_enabled' => false,
            'verification_token' => $token,
        ]);
    }

    public function processOnboarding(Domain $domain, bool $force = false): bool
    {
        // Immutable safety for healthy domains
        if ($domain->isHealthy() && ! $force) {
            $this->checkConnection($domain);

            return true;
        }

        if ($domain->verification_paused_at) {
            return false;
        }

        if (! $domain->ownership_verified_at && ! $domain->is_legacy) {
            if (empty($domain->verification_token)) {
                $domain->update([
                    'verification_error' => 'Step 1 Failed: Verification token is missing.',
                ]);

                return false;
            }

            $domain->increment('verification_attempts');
            $domain->update(['last_verification_attempt_at' => now()]);

            $ownership = $this->dns->verifyOwnership($domain->domain, $domain->verification_token);

            $domain->update([
                'verification_debug_info' => [
                    'step' => 'ownership_txt',
                    'checked_at' => now()->toDateTimeString(),
                    'checked_host' => $ownership['checked_host'] ?? null,
                    'expected_token' => $ownership['expected_token'] ?? $domain->verification_token,
                    'found_txt_values' => $ownership['found_txt_values'] ?? ($ownership['found_values'] ?? []),
                    'found_values' => $ownership['found_txt_values'] ?? ($ownership['found_values'] ?? []),
                    'dig_debug' => $ownership['dig_debug'] ?? null,
                    'attempt' => $domain->verification_attempts,
                ],
            ]);

            if ($ownership['status']) {
                $domain->update([
                    'ownership_verified_at' => now(),
                    'verification_status' => Domain::VERIFY_OWNERSHIP_VERIFIED,
                    'last_verification_message' => 'Ownership verified. Provisioning domain routing now.',
                    'verification_error' => null,
                    'verification_attempts' => 0, // reset on success
                    'verification_paused_at' => null,
                ]);

                Log::info('Domain ownership verified successfully', ['domain' => $domain->domain]);
            } else {
                $isFirstFailure = $domain->verification_attempts === 1;
                $hasExceededAttempts = $domain->verification_attempts >= 10;
                $hasExceededTime = $domain->created_at->diffInHours(now()) >= 48;

                if ($hasExceededAttempts || $hasExceededTime) {
                    $reason = $hasExceededAttempts
                        ? 'Verification attempts limit reached (10).'
                        : 'Verification time limit reached (48 hours).';

                    $domain->update([
                        'verification_paused_at' => now(),
                        'verification_failed_at' => now(),
                        'verification_failure_reason' => 'TXT verification token was not found after multiple attempts.',
                        'verification_error' => 'Step 1 Failed: '.$reason,
                        'last_verification_message' => 'Verification paused. Please check your DNS record and click Retry Verification.',
                    ]);

                    Log::error('Domain verification reached retry limit and is now paused.', [
                        'domain' => $domain->domain,
                        'attempts' => $domain->verification_attempts,
                        'reason' => $reason,
                    ]);
                } else {
                    $domain->update([
                        'last_verification_message' => $ownership['message'],
                        'verification_error' => 'Step 1 Failed: '.$ownership['message'],
                    ]);

                    if ($isFirstFailure) {
                        Log::warning('Domain ownership verification failed (First attempt)', [
                            'domain' => $domain->domain,
                            'error' => $ownership['message'],
                        ]);
                    }
                }

                return false;
            }
        }

        $this->activateDomain($domain);
        $domain->refresh();

        $this->runOriginDiagnostics($domain);

        return true;
    }

    protected function activateDomain(Domain $domain): void
    {
        DB::transaction(function () use ($domain) {
            $updates = [
                'verification_status' => Domain::VERIFY_ACTIVE,
                'status' => Domain::STATUS_VERIFIED,
                'is_verified' => true,
                'verified_at' => $domain->verified_at ?? now(),
                'is_approved' => true,
                'approved_at' => $domain->approved_at ?? now(),
                'last_verification_message' => 'Domain connected successfully. SSL and routing may take a few minutes to fully propagate.',
                'verification_error' => null,
            ];

            $domain->update($updates);
            $domain->update(['is_routing_enabled' => true]);
        });
    }

    public function checkConnection(Domain $domain): void
    {
        // Lightweight diagnostics for healthy domains
        $this->runOriginDiagnostics($domain);

        $domain->updateQuietly([
            'last_verification_message' => 'Domain is active. Connection diagnostics refreshed.',
        ]);
    }

    public function removeDomain(Domain $domain): void
    {
        // Platform subdomains cannot be removed this way
        if (! $domain->isCustom()) {
            throw new \Exception('Platform subdomains cannot be removed.');
        }

        $domain->delete();
    }

    protected function runOriginDiagnostics(Domain $domain): void
    {
        $fingerprint = $this->dns->verifyOriginFingerprint($domain->domain);
        $debugInfo = $domain->verification_debug_info ?? [];
        $debugInfo['origin_fingerprint'] = [
            'checked_url' => $fingerprint['checked_url'] ?? null,
            'checked_urls' => $fingerprint['checked_urls'] ?? [],
            'attempts' => $fingerprint['attempts'] ?? [],
            'message' => $fingerprint['message'] ?? null,
            'status' => $fingerprint['status'] ?? false,
        ];

        $updates = [
            'last_http_status' => $fingerprint['http_status'] ?? null,
            'last_resolved_ip' => $fingerprint['resolved_ip'] ?? null,
            'cloudflare_detected' => $fingerprint['cloudflare_detected'] ?? false,
            'verification_debug_info' => $debugInfo,
            'last_verification_message' => 'Domain connected successfully. SSL and routing may take a few minutes to fully propagate.',
            'verification_error' => null,
        ];

        if ($fingerprint['status']) {
            $updates['connection_verified_at'] = $domain->connection_verified_at ?? now();
            $updates['origin_verified_at'] = $domain->origin_verified_at ?? now();
        }

        $domain->updateQuietly($updates);
    }

    public function regenerateToken(Domain $domain): void
    {
        $domain->update([
            'verification_token' => Str::random(32),
            'status' => Domain::STATUS_PENDING,
            'verification_status' => Domain::VERIFY_PENDING_DNS,
            'is_verified' => false,
            'is_approved' => false,
            'is_routing_enabled' => false,
            'ownership_verified_at' => null,
            'connection_verified_at' => null,
            'last_verification_message' => 'Verification token regenerated. Please update your DNS records.',
            'verification_error' => null,
        ]);
    }

    public function verifyAndProvision(Domain $domain): bool
    {
        return $this->processOnboarding($domain);
    }
}
