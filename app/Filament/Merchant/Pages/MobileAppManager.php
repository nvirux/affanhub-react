<?php

namespace App\Filament\Merchant\Pages;

use App\Models\PlatformSetting;
use App\Models\StoreMobileApp;
use App\Services\MobileAppBuilderService;
use BackedEnum;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use PayMint\Laravel\Facades\PayMint;
use UnitEnum;

class MobileAppManager extends Page
{
    use WithFileUploads;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-device-phone-mobile';

    protected static string|UnitEnum|null $navigationGroup = 'Settings';

    protected static ?string $title = 'Mobile App (Android)';

    protected static ?string $navigationLabel = 'Mobile App';

    protected static ?int $navigationSort = 3;

    protected string $view = 'filament.merchant.pages.mobile-app';

    public ?StoreMobileApp $mobileApp = null;

    public string $appName = '';

    public string $iconChoice = 'store_logo'; // 'store_logo' or 'custom'

    public $appIcon = null;

    public string $paymentMethod = 'paymint'; // 'paymint' or 'wallet'

    public string $buildType = 'release'; // Always production release build (Signed APK + Google Play Bundle)

    public float $setupPrice = 15000.00;

    public float $playstorePrice = 30000.00;

    public bool $includePlaystore = false;

    public function togglePlaystore(): void
    {
        if (! $this->mobileApp?->isPlayStoreRequested()) {
            $this->includePlaystore = ! $this->includePlaystore;
        }
    }

    public function getTotalPriceProperty(): float
    {
        $hasPaidSetup = $this->mobileApp && (float) $this->mobileApp->price_paid >= $this->setupPrice;
        $hasPaidPlaystore = $this->mobileApp && (float) $this->mobileApp->playstore_paid >= $this->playstorePrice;

        $total = 0.0;
        if (! $hasPaidSetup) {
            $total += $this->setupPrice;
        }
        if ($this->includePlaystore && ! $hasPaidPlaystore) {
            $total += $this->playstorePrice;
        }

        return $total;
    }

    public function isBuilderEnabled(): bool
    {
        return (bool) PlatformSetting::get('mobile_app_builder_enabled', true);
    }

    public function requireCustomDomain(): bool
    {
        return (bool) PlatformSetting::get('mobile_app_require_custom_domain', false);
    }

    public function hasVerifiedCustomDomain(): bool
    {
        $tenant = Filament::getTenant();
        if (! $tenant) {
            return false;
        }

        return $tenant->domains()->get()->contains(function ($domain) {
            return $domain->isCustom() && $domain->isHealthy();
        });
    }

    public function isPlanAllowed(): bool
    {
        $allowedPlans = (string) PlatformSetting::get('mobile_app_allowed_plans', 'all');
        if ($allowedPlans === 'all') {
            return true;
        }

        $tenant = Filament::getTenant();
        $sub = $tenant?->activeSubscription;
        $planSlug = $sub?->plan?->slug;

        if ($allowedPlans === 'pro_and_above') {
            return in_array($planSlug, ['pro', 'enterprise'], true);
        }

        if ($allowedPlans === 'enterprise_only') {
            return $planSlug === 'enterprise';
        }

        return true;
    }

    public function canCreateOrRebuildApp(): bool
    {
        return $this->isBuilderEnabled()
            && (! $this->requireCustomDomain() || $this->hasVerifiedCustomDomain())
            && $this->isPlanAllowed();
    }

    public function getEligibilityBlockReason(): ?string
    {
        if (! $this->isBuilderEnabled()) {
            return 'Mobile app compilation is temporarily paused by platform administrators.';
        }

        if ($this->requireCustomDomain() && ! $this->hasVerifiedCustomDomain()) {
            return 'Your store must connect and verify a custom domain (e.g. yourbrand.com) before building a mobile app.';
        }

        if (! $this->isPlanAllowed()) {
            $allowedPlans = (string) PlatformSetting::get('mobile_app_allowed_plans', 'all');
            $tier = $allowedPlans === 'enterprise_only' ? 'Enterprise' : 'Pro or Enterprise';

            return "Your current plan does not include mobile app compilation. Please upgrade to the {$tier} plan.";
        }

        return null;
    }

    public function getResolvedStoreUrl(): string
    {
        $tenant = Filament::getTenant();
        if (! $tenant) {
            return url('/dashboard');
        }

        // 1. Prefer verified custom domain if available
        $customDomain = $tenant->domains()->get()->first(fn ($d) => $d->isCustom() && $d->isHealthy());
        if ($customDomain) {
            $scheme = str_contains($customDomain->domain, 'localhost') ? 'http' : 'https';

            return "{$scheme}://{$customDomain->domain}/dashboard";
        }

        // 2. Primary domain
        $primaryDomain = $tenant->domains()->where('is_primary', true)->first() ?? $tenant->domains()->first();
        if ($primaryDomain) {
            $scheme = str_contains($primaryDomain->domain, 'localhost') ? 'http' : 'https';

            return "{$scheme}://{$primaryDomain->domain}/dashboard";
        }

        return rtrim($tenant->getStoreUrl(), '/').'/dashboard';
    }

    public function setBuildType(string $type): void
    {
        $this->buildType = $type;
    }

    public function setIconChoice(string $choice): void
    {
        if (! $this->mobileApp?->isBuilding()) {
            $this->iconChoice = $choice;
        }
    }

    public function setPaymentMethod(string $method): void
    {
        $this->paymentMethod = $method;
    }

    public function mount(): void
    {
        $tenant = Filament::getTenant();
        $this->mobileApp = $tenant->mobileApp;

        $this->setupPrice = (float) PlatformSetting::get('mobile_app_setup_fee', 15000.00);
        $this->playstorePrice = (float) PlatformSetting::get('mobile_app_playstore_fee', 30000.00);

        if ($this->mobileApp) {
            $this->appName = $this->mobileApp->app_name;
            $this->iconChoice = 'custom';
            $this->includePlaystore = (bool) $this->mobileApp->include_playstore;
        } else {
            $this->appName = $tenant->name;
            $this->iconChoice = ! empty($tenant->logo_path) ? 'store_logo' : 'custom';
        }
    }

    /**
     * Get computed unique package ID based on current app name.
     */
    public function getPackageIdProperty(): string
    {
        if ($this->mobileApp && ! empty($this->mobileApp->package_id)) {
            return $this->mobileApp->package_id;
        }

        $tenant = Filament::getTenant();

        return StoreMobileApp::generateUniquePackageId($this->appName ?: $tenant->name, $tenant->id);
    }

    /**
     * Resolve and normalize the app launcher icon to a 512x512 square PNG.
     */
    protected function resolveIconPath(MobileAppBuilderService $builder): ?string
    {
        $tenant = Filament::getTenant();

        if ($this->iconChoice === 'store_logo' && ! empty($tenant->logo_path)) {
            return $builder->processAndStoreAppIcon($tenant->logo_path, $tenant->id);
        }

        if ($this->appIcon) {
            if ($this->mobileApp?->app_icon_path) {
                $builder->deleteOldIcon($this->mobileApp->app_icon_path);
            }

            return $builder->processAndStoreAppIcon($this->appIcon, $tenant->id);
        }

        if ($this->mobileApp?->app_icon_path) {
            return $this->mobileApp->app_icon_path;
        }

        if (! empty($tenant->logo_path)) {
            return $builder->processAndStoreAppIcon($tenant->logo_path, $tenant->id);
        }

        return null;
    }

    /**
     * Pay with PayMint Hosted Checkout (Card, Bank Transfer, USSD).
     */
    public function payWithPayMint(MobileAppBuilderService $builder): mixed
    {
        if (! $this->canCreateOrRebuildApp()) {
            Notification::make()
                ->title('App Creation Unavailable')
                ->body($this->getEligibilityBlockReason() ?? 'Mobile app creation is not available.')
                ->danger()
                ->send();

            return null;
        }

        $tenant = Filament::getTenant();

        $rules = [
            'appName' => 'required|string|min:2|max:30',
        ];

        if ($this->iconChoice === 'custom' && ! $this->mobileApp?->app_icon_path) {
            $rules['appIcon'] = 'required|image|max:3072';
        }

        $this->validate($rules);

        $iconPath = $this->resolveIconPath($builder);
        $packageId = $this->package_id;
        $reference = 'APP_'.strtoupper(Str::random(12));
        $redirectUrl = route('merchant.billing.callback', ['tenant' => $tenant->public_id]);

        $owner = $tenant->owner;
        $email = $owner?->email ?? 'merchant@store.com';
        $name = $owner?->name ?? $tenant->name;
        $phone = $owner?->phone ?? '08012345678';

        $totalPrice = $this->totalPrice;

        try {
            $response = PayMint::checkout()->initialize([
                'amount' => (int) $totalPrice,
                'email' => $email,
                'reference' => $reference,
                'redirect_url' => $redirectUrl,
                'name' => $name,
                'phone' => $phone,
            ]);

            if (($response['status'] ?? '') !== 'success' || empty($response['data']['authorization_url'])) {
                $errorMsg = $response['message'] ?? 'Failed to initialize PayMint Checkout.';
                Notification::make()
                    ->title('PayMint Checkout Failed')
                    ->body($errorMsg)
                    ->danger()
                    ->send();

                return null;
            }

            // Cache checkout intent for 2 hours
            Cache::put("mobile_app_checkout_{$reference}", [
                'store_id' => $tenant->id,
                'app_name' => $this->appName,
                'package_id' => $packageId,
                'icon_path' => $iconPath,
                'price' => $totalPrice,
                'include_playstore' => $this->includePlaystore,
                'playstore_fee' => $this->includePlaystore ? $this->playstorePrice : 0.0,
            ], now()->addHours(2));

            Log::info('PayMint Mobile App Checkout Session Initialized:', [
                'reference' => $reference,
                'auth_url' => $response['data']['authorization_url'],
            ]);

            $authUrl = $response['data']['authorization_url'];
            $this->js("window.location.href = '{$authUrl}';");

            return $this->redirect($authUrl, navigate: false);

        } catch (\Throwable $e) {
            Log::error('PayMint Mobile App Checkout Exception: '.$e->getMessage());

            Notification::make()
                ->title('Payment Initialization Error')
                ->body($e->getMessage())
                ->danger()
                ->send();

            return null;
        }
    }

    /**
     * Submit order and pay from Store Main Wallet balance.
     */
    public function orderWithBalance(MobileAppBuilderService $builder): void
    {
        if (! $this->canCreateOrRebuildApp()) {
            Notification::make()
                ->title('App Creation Unavailable')
                ->body($this->getEligibilityBlockReason() ?? 'Mobile app creation is not available.')
                ->danger()
                ->send();

            return;
        }

        $tenant = Filament::getTenant();

        $rules = [
            'appName' => 'required|string|min:2|max:30',
        ];

        if ($this->iconChoice === 'custom' && ! $this->mobileApp?->app_icon_path) {
            $rules['appIcon'] = 'required|image|max:3072';
        }

        $this->validate($rules);

        $wallet = $tenant->mainWallet();
        $totalPrice = $this->totalPrice;
        $hasPaidSetup = $this->mobileApp && (float) $this->mobileApp->price_paid >= $this->setupPrice;
        $hasPaidPlaystore = $this->mobileApp && (float) $this->mobileApp->playstore_paid >= $this->playstorePrice;

        // Debit store wallet if there is an unpaid amount (setup or playstore)
        if ($totalPrice > 0) {
            if (! $wallet->hasSufficientBalance($totalPrice)) {
                Notification::make()
                    ->title('Insufficient Store Balance')
                    ->body('Your store main wallet balance is insufficient. You need ₦'.number_format($totalPrice, 2).' or choose PayMint to pay with Card or Transfer.')
                    ->danger()
                    ->send();

                return;
            }

            $wallet->withdraw(
                amount: $totalPrice,
                description: "Mobile App Android Compilation for {$this->appName}".($this->includePlaystore ? ' + Google Play Publishing' : ''),
                reference: 'app_'.strtolower(Str::random(12)),
                meta: [
                    'type' => 'mobile_app_setup',
                    'app_name' => $this->appName,
                    'package_id' => $this->package_id,
                    'include_playstore' => $this->includePlaystore,
                ]
            );
        }

        $iconPath = $this->resolveIconPath($builder);

        $app = StoreMobileApp::updateOrCreate(
            ['store_id' => $tenant->id],
            [
                'app_name' => $this->appName,
                'package_id' => $this->package_id,
                'app_icon_path' => $iconPath,
                'price_paid' => ($this->mobileApp?->price_paid ?? 0) + ($hasPaidSetup ? 0 : $this->setupPrice),
                'include_playstore' => $this->includePlaystore,
                'playstore_status' => $this->includePlaystore ? ($this->mobileApp?->playstore_status !== 'not_requested' ? $this->mobileApp->playstore_status : 'pending_submission') : 'not_requested',
                'playstore_paid' => ($this->mobileApp?->playstore_paid ?? 0) + ($this->includePlaystore && ! $hasPaidPlaystore ? $this->playstorePrice : 0),
                'status' => 'building',
                'version_code' => $this->mobileApp?->version_code ?? 1,
                'version_name' => $this->mobileApp?->version_name ?? '1.0.0',
            ]
        );

        $this->mobileApp = $app;

        $storeUrl = $this->getResolvedStoreUrl();

        $result = $builder->dispatchBuild($app, $storeUrl, $this->buildType);

        if ($result['success']) {
            Notification::make()
                ->title('App Compilation Started!')
                ->body('Your Android app is compiling in the cloud. It will be ready in approximately 2 minutes.')
                ->success()
                ->send();
        } else {
            Notification::make()
                ->title('Compilation Notice')
                ->body($result['message'])
                ->info()
                ->send();
        }
    }

    /**
     * Request Google Play Store Publishing for an existing built app.
     */
    public function requestPlayStorePublishing(): void
    {
        if (! $this->mobileApp || $this->mobileApp->isPlayStoreRequested()) {
            return;
        }

        $tenant = Filament::getTenant();
        $wallet = $tenant->mainWallet();

        if (! $wallet->hasSufficientBalance($this->playstorePrice)) {
            Notification::make()
                ->title('Insufficient Store Balance')
                ->body('You need ₦'.number_format($this->playstorePrice, 2).' in your store wallet to request Google Play Store publishing.')
                ->danger()
                ->send();

            return;
        }

        $wallet->withdraw(
            amount: $this->playstorePrice,
            description: "Google Play Store Publishing Service for {$this->mobileApp->app_name}",
            reference: 'play_'.strtolower(Str::random(12)),
            meta: [
                'type' => 'playstore_publishing',
                'app_name' => $this->mobileApp->app_name,
                'package_id' => $this->mobileApp->package_id,
            ]
        );

        $this->mobileApp->update([
            'include_playstore' => true,
            'playstore_status' => 'pending_submission',
            'playstore_paid' => $this->playstorePrice,
        ]);

        Notification::make()
            ->title('Google Play Store Publishing Requested!')
            ->body('Our deployment team will package, sign, and submit your app bundle (.aab) to Google Play Store.')
            ->success()
            ->send();
    }

    /**
     * Trigger a new release update (auto-increments version code). Cleans up old builds.
     */
    public function requestUpdate(MobileAppBuilderService $builder): void
    {
        if (! $this->mobileApp) {
            return;
        }

        if (! $this->canCreateOrRebuildApp()) {
            Notification::make()
                ->title('App Updates Unavailable')
                ->body($this->getEligibilityBlockReason() ?? 'Mobile app compilation is not available.')
                ->danger()
                ->send();

            return;
        }

        $this->validate([
            'appName' => 'required|string|min:2|max:30',
            'appIcon' => 'nullable|image|max:3072',
        ]);

        $tenant = Filament::getTenant();

        // Update logo if new one uploaded or re-selected
        $iconPath = $this->resolveIconPath($builder);
        if ($iconPath) {
            $this->mobileApp->app_icon_path = $iconPath;
        }

        // Clean up old APK to avoid storage buildup
        $builder->cleanupOldBuilds($this->mobileApp);

        $this->mobileApp->app_name = $this->appName;
        $this->mobileApp->incrementVersion();
        $this->mobileApp->status = 'building';
        $this->mobileApp->save();

        $storeUrl = $this->getResolvedStoreUrl();

        $result = $builder->dispatchBuild($this->mobileApp, $storeUrl, $this->buildType);

        Notification::make()
            ->title("Update Dispatched (v{$this->mobileApp->version_name})")
            ->body('New version is being compiled in the cloud!')
            ->success()
            ->send();
    }

    /**
     * Check GitHub for completed APK download artifact.
     */
    public function checkBuildStatus(MobileAppBuilderService $builder): void
    {
        if (! $this->mobileApp) {
            return;
        }

        $prevStatus = $this->mobileApp->status;

        $builder->syncBuildStatus($this->mobileApp);
        $this->mobileApp->refresh();

        if ($this->mobileApp->isReady() && $prevStatus !== 'ready') {
            Notification::make()
                ->title('APK Ready for Download!')
                ->body("v{$this->mobileApp->version_name} has been compiled and is ready for download.")
                ->success()
                ->send();
        } elseif ($this->mobileApp->status === 'failed' && $prevStatus !== 'failed') {
            Notification::make()
                ->title('Compilation Issue')
                ->body($this->mobileApp->failure_reason ?? 'Build failed on runner. Check workflow logs.')
                ->danger()
                ->send();
        }
    }
}
