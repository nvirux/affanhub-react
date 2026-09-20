<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Store;
use App\Models\StoreMobileApp;
use App\Services\MobileAppBuilderService;
use Filament\Notifications\Notification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use PayMint\Laravel\Facades\PayMint;

class BillingCheckoutCallbackController extends Controller
{
    /**
     * Handle return from PayMint Hosted Checkout.
     */
    public function handle(Request $request, Store $tenant): RedirectResponse
    {
        $reference = (string) $request->query('reference');

        $billingUrl = route('filament.merchant.pages.billing', ['tenant' => $tenant->public_id]);

        if (empty($reference)) {
            Notification::make()
                ->title('Invalid Payment Reference')
                ->body('No transaction reference was provided by the payment gateway.')
                ->danger()
                ->send();

            return redirect()->to($billingUrl);
        }

        // Check if this checkout was for Mobile App compilation
        $mobileAppData = Cache::get("mobile_app_checkout_{$reference}");
        $isMobileApp = ! empty($mobileAppData) || str_starts_with($reference, 'APP_');
        $targetUrl = $isMobileApp
            ? route('filament.merchant.pages.mobile-app-manager', ['tenant' => $tenant->public_id])
            : $billingUrl;

        try {
            // Verify payment with PayMint SDK
            $verification = PayMint::checkout()->verify($reference);

            Log::info('PayMint Checkout Verification Response:', [
                'reference' => $reference,
                'response' => $verification,
            ]);

            $paymintStatus = $verification['data']['status'] ?? ($verification['status'] ?? null);

            if ($paymintStatus !== 'successful') {
                Notification::make()
                    ->title('Payment Verification Unsuccessful')
                    ->body('Your payment could not be verified as successful. If you were debited, please contact support.')
                    ->danger()
                    ->send();

                return redirect()->to($targetUrl);
            }

            if ($isMobileApp) {
                $appName = $mobileAppData['app_name'] ?? $tenant->name;
                $packageId = $mobileAppData['package_id'] ?? StoreMobileApp::generateUniquePackageId($appName, $tenant->id);
                $iconPath = $mobileAppData['icon_path'] ?? null;
                $pricePaid = (float) ($mobileAppData['price'] ?? ($verification['data']['amount'] ?? 15000.00));

                $includePlaystore = ! empty($mobileAppData['include_playstore']);
                $playstoreFee = (float) ($mobileAppData['playstore_fee'] ?? 0.00);

                $app = StoreMobileApp::updateOrCreate(
                    ['store_id' => $tenant->id],
                    [
                        'app_name' => $appName,
                        'package_id' => $packageId,
                        'app_icon_path' => $iconPath,
                        'price_paid' => $pricePaid,
                        'include_playstore' => $includePlaystore,
                        'playstore_status' => $includePlaystore ? 'pending_submission' : 'not_requested',
                        'playstore_paid' => $includePlaystore ? $playstoreFee : 0.00,
                        'status' => 'building',
                        'version_code' => 1,
                        'version_name' => '1.0.0',
                    ]
                );

                Cache::forget("mobile_app_checkout_{$reference}");

                $primaryDomain = $tenant->domains()->first();
                $storeUrl = $primaryDomain ? "https://{$primaryDomain->domain}" : url('/');

                /** @var MobileAppBuilderService $builder */
                $builder = app(MobileAppBuilderService::class);
                $builder->dispatchBuild($app, $storeUrl);

                Notification::make()
                    ->title('Payment Successful & Build Started!')
                    ->body('₦'.number_format($pricePaid, 2)." received via PayMint. Your {$appName} Android app is now compiling in the cloud!")
                    ->success()
                    ->send();

                return redirect()->to($targetUrl);
            }

            // Determine plan to activate
            $planId = $checkoutData['plan_id'] ?? null;
            $interval = $checkoutData['interval'] ?? 'month';
            $price = (float) ($checkoutData['price'] ?? ($verification['data']['amount'] ?? 0.00));

            $plan = $planId ? Plan::find($planId) : null;

            if (! $plan) {
                // Fallback: try finding plan by slug 'pro'
                $plan = Plan::where('slug', 'pro')->firstOrFail();
            }

            // Cancel previous active subscriptions
            $tenant->subscriptions()->where('status', 'active')->update(['status' => 'cancelled']);

            // Create new active subscription
            $tenant->subscriptions()->create([
                'plan_id' => $plan->id,
                'price' => $price,
                'billing_interval' => $interval,
                'status' => 'active',
                'starts_at' => now(),
                'ends_at' => $interval === 'year' ? now()->addYear() : now()->addMonth(),
            ]);

            // Clean up cache
            Cache::forget("billing_checkout_{$reference}");

            Notification::make()
                ->title("Upgraded to {$plan->name}!")
                ->body('Payment of ₦'.number_format($price, 2)." verified via PayMint. Store {$tenant->name} is now upgraded to {$plan->name}!")
                ->success()
                ->send();

            return redirect()->to($billingUrl);

        } catch (\Throwable $e) {
            Log::error('PayMint Verification Exception: '.$e->getMessage(), [
                'reference' => $reference,
                'tenant' => $tenant->id,
            ]);

            Notification::make()
                ->title('Verification Error')
                ->body('An error occurred while verifying your payment: '.$e->getMessage())
                ->danger()
                ->send();

            return redirect()->to($billingUrl);
        }
    }
}
