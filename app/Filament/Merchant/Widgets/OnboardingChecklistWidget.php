<?php

namespace App\Filament\Merchant\Widgets;

use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Filament\Widgets\Widget;

class OnboardingChecklistWidget extends Widget
{
    protected string $view = 'filament.merchant.widgets.onboarding-checklist-widget';

    protected static ?int $sort = 0;

    protected int|string|array $columnSpan = 'full';

    public static function canView(): bool
    {
        $tenant = Filament::getTenant();

        if (! $tenant) {
            return false;
        }

        // Allow merchant to dismiss checklist
        if ($tenant->dismiss_onboarding_checklist) {
            return false;
        }

        return true;
    }

    public function dismiss(): void
    {
        $tenant = Filament::getTenant();

        if (! $tenant) {
            return;
        }

        $tenant->dismiss_onboarding_checklist = true;
        $tenant->save();

        Notification::make()
            ->title('Checklist Dismissed')
            ->body('You can access your store settings and pricing anytime from the sidebar.')
            ->info()
            ->send();
    }

    public function markLinkCopied(): void
    {
        Notification::make()
            ->title('Link Copied')
            ->body('Storefront URL copied to clipboard! Share it with your customers on WhatsApp or social media.')
            ->success()
            ->send();
    }

    public function getStepsData(): array
    {
        $tenant = Filament::getTenant();

        if (! $tenant) {
            return [
                'steps' => [],
                'progress' => 0,
                'completedCount' => 0,
                'totalCount' => 5,
                'storeUrl' => '#',
            ];
        }

        $storeUrl = $tenant->getStoreUrl();

        // 1. Store Created
        $step1Done = true;

        // 2. Set Selling Prices (data plans or airtime discounts custom configured)
        $step2Done = $tenant->storeDataPlans()->exists() || $tenant->storeAirtimeDiscounts()->exists();

        // 3. Fund Vending Wallet (balance > 0 or has successful wallet funding transaction)
        $step3Done = (float) $tenant->mainWallet()->balance > 0
            || $tenant->transactions()->where('type', 'wallet_funding')->where('status', 'successful')->exists();

        // 4. Customize Branding (has WhatsApp support phone, tagline, or custom colors)
        $step4Done = filled($tenant->whatsapp_chat_phone)
            || filled($tenant->description)
            || ! empty($tenant->data['logo_url']);

        // 5. Connect Domain or Share Link
        $step5Done = $tenant->hasFeature('custom_domain')
            && $tenant->domains()->where('is_primary', false)->exists();

        $steps = [
            [
                'id' => 'store_created',
                'title' => 'Create your digital storefront',
                'description' => "Store '{$tenant->name}' created and hosted.",
                'completed' => $step1Done,
                'url' => null,
                'action_label' => 'Done',
                'icon' => 'heroicon-m-check-circle',
            ],
            [
                'id' => 'set_prices',
                'title' => 'Set your selling prices & profit margins',
                'description' => 'Configure retail prices for SME data & airtime.',
                'completed' => $step2Done,
                'url' => route('filament.merchant.resources.store-data-plans.index', ['tenant' => $tenant->public_id]),
                'action_label' => $step2Done ? 'Manage Prices' : 'Set Prices →',
                'icon' => 'heroicon-m-tag',
            ],
            [
                'id' => 'fund_wallet',
                'title' => 'Fund your vending wallet',
                'description' => 'Add vending balance with PayMint checkout.',
                'completed' => $step3Done,
                'url' => route('filament.merchant.pages.store-wallet', ['tenant' => $tenant->public_id]),
                'action_label' => $step3Done ? 'Wallet Active' : 'Fund Wallet →',
                'icon' => 'heroicon-m-wallet',
            ],
            [
                'id' => 'customize_branding',
                'title' => 'Personalize your storefront & WhatsApp support',
                'description' => 'Set your store logo & WhatsApp customer chat button.',
                'completed' => $step4Done,
                'url' => route('filament.merchant.pages.store-settings', ['tenant' => $tenant->public_id]),
                'action_label' => $step4Done ? 'Settings' : 'Customize →',
                'icon' => 'heroicon-m-paint-brush',
            ],
            [
                'id' => 'share_store',
                'title' => 'Share your storefront link with customers',
                'description' => "Live at {$storeUrl}",
                'completed' => $step5Done,
                'url' => $storeUrl,
                'is_external' => true,
                'action_label' => 'View Storefront 🌐',
                'icon' => 'heroicon-m-share',
            ],
        ];

        $completedCount = collect($steps)->filter(fn ($s) => $s['completed'])->count();
        $totalCount = count($steps);
        $progress = (int) round(($completedCount / $totalCount) * 100);

        return [
            'steps' => $steps,
            'progress' => $progress,
            'completedCount' => $completedCount,
            'totalCount' => $totalCount,
            'storeUrl' => $storeUrl,
        ];
    }
}
