<?php

namespace App\Filament\Merchant\Pages;

use App\Models\Plan;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Enums\Width;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use PayMint\Laravel\Facades\PayMint;

class OnboardingPlan extends Page
{
    protected static ?string $slug = 'onboarding/plan';

    protected static bool $shouldRegisterNavigation = false;

    protected static string $layout = 'filament-panels::components.layout.base';

    protected string $view = 'filament.merchant.pages.onboarding-plan';

    public string $interval = 'month';

    public function getMaxWidth(): Width|string|null
    {
        return Width::Full;
    }

    public function getTitle(): string|Htmlable
    {
        return 'Choose Your Plan';
    }

    protected function getViewData(): array
    {
        $tenant = Filament::getTenant();

        return [
            'tenant' => $tenant,
            'proPlan' => Plan::where('slug', 'pro')->first(),
            'enterprisePlan' => Plan::where('slug', 'enterprise')->first(),
            'starterPlan' => Plan::where('slug', 'starter')->first(),
            'primaryDomain' => $tenant->getPrimaryDomain() ?? ($tenant->public_id.'.affanhub.com'),
            'dashboardUrl' => route('filament.merchant.pages.dashboard', ['tenant' => $tenant->public_id]),
        ];
    }

    public function selectInterval(string $interval): void
    {
        $this->interval = $interval;
    }

    public function continueStarter(): mixed
    {
        $tenant = Filament::getTenant();

        // Ensure current store has active Starter plan
        if (! $tenant->hasActiveSubscription()) {
            $starterPlan = Plan::where('slug', 'starter')->first();
            if ($starterPlan) {
                $tenant->subscriptions()->create([
                    'plan_id' => $starterPlan->id,
                    'price' => 0.00,
                    'billing_interval' => 'month',
                    'status' => 'active',
                    'starts_at' => now(),
                    'ends_at' => null,
                ]);
            }
        }

        return redirect()->route('filament.merchant.pages.dashboard', ['tenant' => $tenant->public_id]);
    }

    public function contactEnterprise(): mixed
    {
        $tenant = Filament::getTenant();
        $supportPhone = config('services.support.whatsapp') ?? env('SUPPORT_WHATSAPP_PHONE', '2348000000000');
        $message = rawurlencode("Hello AffanHub Sales, I would like to subscribe to the Enterprise Plan for my store '{$tenant->name}' (ID: {$tenant->public_id}). Let's discuss high-volume rates and API access.");

        Notification::make()
            ->title('Enterprise Sales Inquiry')
            ->body('Connecting to AffanHub Enterprise Sales via WhatsApp...')
            ->info()
            ->send();

        $whatsappUrl = "https://wa.me/{$supportPhone}?text={$message}";

        $this->js("window.open('{$whatsappUrl}', '_blank');");

        return null;
    }

    public function payWithCheckout(int $planId): mixed
    {
        $tenant = Filament::getTenant();
        $plan = Plan::findOrFail($planId);
        $price = (float) ($this->interval === 'year' ? $plan->price_yearly : $plan->price_monthly);

        $owner = $tenant->owner;
        $email = $owner?->email ?? 'merchant@store.com';
        $name = $owner?->name ?? $tenant->name;
        $phone = $owner?->phone ?? '08012345678';

        $reference = 'SUB_'.strtoupper(Str::random(12));
        $redirectUrl = route('merchant.billing.callback', ['tenant' => $tenant->public_id]);

        try {
            $response = PayMint::checkout()->initialize([
                'amount' => (int) $price,
                'email' => $email,
                'reference' => $reference,
                'redirect_url' => $redirectUrl,
                'name' => $name,
                'phone' => $phone,
            ]);

            if (($response['status'] ?? '') !== 'success' || empty($response['data']['authorization_url'])) {
                $errorMsg = $response['message'] ?? 'Failed to initialize PayMint Checkout.';
                Notification::make()
                    ->title('Checkout Initialization Failed')
                    ->body($errorMsg)
                    ->danger()
                    ->send();

                return null;
            }

            Cache::put("billing_checkout_{$reference}", [
                'store_id' => $tenant->id,
                'plan_id' => $plan->id,
                'interval' => $this->interval,
                'price' => $price,
            ], now()->addHours(2));

            Log::info('Onboarding PayMint Checkout Session Initialized:', [
                'reference' => $reference,
                'auth_url' => $response['data']['authorization_url'],
            ]);

            $authUrl = $response['data']['authorization_url'];

            $this->js("window.location.href = '{$authUrl}';");

            return $this->redirect($authUrl, navigate: false);

        } catch (\Throwable $e) {
            Log::error('Onboarding PayWithCheckout Exception: '.$e->getMessage());

            Notification::make()
                ->title('Checkout Error')
                ->body($e->getMessage())
                ->danger()
                ->send();

            return null;
        }
    }
}
