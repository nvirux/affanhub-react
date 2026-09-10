<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Store;
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

        // Retrieve checkout metadata from cache
        $checkoutData = Cache::get("billing_checkout_{$reference}");

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

                return redirect()->to($billingUrl);
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
