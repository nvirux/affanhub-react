<?php

namespace App\Http\Controllers\Merchant;

use App\Filament\Merchant\Pages\Domains;
use App\Http\Controllers\Controller;
use App\Models\Domain;
use App\Models\Store;
use App\Services\Audit\ActivityLogger;
use App\Services\Cloudflare\CloudflareOAuthService;
use App\Services\Domain\CustomDomainOnboardingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CloudflareDomainConnectController extends Controller
{
    /**
     * Start Cloudflare OAuth authorization flow for a custom domain.
     */
    public function connect(Request $request, Domain $domain, CloudflareOAuthService $cloudflare): RedirectResponse
    {
        $owner = Auth::guard('owner')->user();
        if (! $owner) {
            return redirect()->route('filament.merchant.auth.login')
                ->with('error', 'Please log in to continue.');
        }

        // Verify that this domain belongs to one of owner's stores
        $store = Store::where('id', $domain->tenant_id)
            ->where(function ($query) use ($owner) {
                $query->where('owner_id', $owner->id)
                    ->orWhereHas('members', fn ($sq) => $sq->where('owners.id', $owner->id));
            })
            ->first();

        if (! $store) {
            abort(403, 'Unauthorized domain access.');
        }

        if (! $cloudflare->isConfigured()) {
            return redirect()->to(Domains::getUrl(['tenant' => $store->public_id], panel: 'merchant'))
                ->with('error', 'Cloudflare integration is not yet configured. Please contact support or use manual DNS setup.');
        }

        // Generate secure random state and PKCE verifier/challenge
        $state = Str::random(40);
        $codeVerifier = CloudflareOAuthService::generateCodeVerifier();
        $codeChallenge = CloudflareOAuthService::generateCodeChallenge($codeVerifier);

        $request->session()->put("cf_oauth_{$state}", [
            'domain_id' => $domain->id,
            'tenant_id' => $store->id,
            'tenant_public_id' => $store->public_id,
            'owner_id' => $owner->id,
            'code_verifier' => $codeVerifier,
            'created_at' => now()->timestamp,
        ]);

        $authUrl = $cloudflare->getAuthorizationUrl($state, $codeChallenge);

        return redirect()->away($authUrl);
    }

    /**
     * Handle callback from Cloudflare OAuth.
     */
    public function callback(
        Request $request,
        CloudflareOAuthService $cloudflare,
        CustomDomainOnboardingService $onboardingService
    ): RedirectResponse {
        $state = (string) $request->query('state');
        $code = (string) $request->query('code');
        $error = (string) $request->query('error');
        $errorDescription = (string) $request->query('error_description');

        $stateData = $request->session()->pull("cf_oauth_{$state}");

        if (! $stateData || ! isset($stateData['domain_id'])) {
            Log::warning('Cloudflare OAuth invalid or expired state', ['state' => $state]);

            return redirect()->to(route('home'))
                ->with('error', 'Cloudflare authorization session expired. Please try connecting again.');
        }

        $store = Store::find($stateData['tenant_id']);
        $domainsUrl = $store ? Domains::getUrl(['tenant' => $store->public_id], panel: 'merchant') : route('home');

        if (filled($error)) {
            Log::info('Cloudflare OAuth authorization denied by user', [
                'error' => $error,
                'description' => $errorDescription,
            ]);

            return redirect()->to($domainsUrl)
                ->with('error', 'Cloudflare connection cancelled: '.($errorDescription ?: $error));
        }

        if (empty($code)) {
            return redirect()->to($domainsUrl)
                ->with('error', 'Cloudflare authorization code was missing.');
        }

        $domain = Domain::where('id', $stateData['domain_id'])
            ->where('tenant_id', $stateData['tenant_id'])
            ->first();

        if (! $domain) {
            return redirect()->to($domainsUrl)
                ->with('error', 'Domain record not found.');
        }

        // Exchange code for token using PKCE code_verifier
        $codeVerifier = $stateData['code_verifier'] ?? null;
        $tokenData = $cloudflare->exchangeCodeForToken($code, $codeVerifier);
        if (! $tokenData || empty($tokenData['access_token'])) {
            return redirect()->to($domainsUrl)
                ->with('error', 'Failed to retrieve access token from Cloudflare. Please try again.');
        }

        $accessToken = $tokenData['access_token'];

        // Automatically configure DNS records in merchant's Cloudflare zone
        $dnsResult = $cloudflare->configureDnsRecords($accessToken, $domain);

        if (! $dnsResult['success']) {
            return redirect()->to($domainsUrl)
                ->with('error', $dnsResult['message']);
        }

        // Mark cloudflare as detected
        $domain->update([
            'cloudflare_detected' => true,
        ]);

        // Attempt verification and provisioning
        try {
            $isVerified = $onboardingService->verifyAndProvision($domain);

            ActivityLogger::log('domain_cloudflare_connected', "Connected domain {$domain->domain} via 1-Click Cloudflare", [
                'domain' => $domain->domain,
                'zone_id' => $dnsResult['zone_id'] ?? null,
                'is_verified' => $isVerified,
            ]);

            if ($isVerified) {
                return redirect()->to($domainsUrl)
                    ->with('success', "🎉 Success! {$domain->domain} is now connected with Cloudflare and verified!");
            }

            return redirect()->to($domainsUrl)
                ->with('success', "Cloudflare DNS records created successfully for {$domain->domain}! Verification is in progress and will complete shortly.");
        } catch (\Throwable $e) {
            Log::error('Domain verification exception after Cloudflare DNS setup: '.$e->getMessage());

            return redirect()->to($domainsUrl)
                ->with('success', "DNS records created in Cloudflare! Click 'Verify Ownership' to complete activation.");
        }
    }
}
