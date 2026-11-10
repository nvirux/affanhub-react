<?php

namespace App\Filament\Merchant\Widgets;

use App\Filament\Merchant\Pages\StoreSettings;
use App\Filament\Merchant\Pages\StoreWallet;
use App\Filament\Merchant\Resources\StoreDataPlanResource;
use App\Models\SettlementAccount;
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
        if ((bool) ($tenant->dismiss_onboarding_checklist ?? false)) {
            return false;
        }

        // Auto-hide once the merchant has finished all onboarding steps!
        $hasPrices = $tenant->storeDataPlans()->exists() || $tenant->storeAirtimeDiscounts()->exists();
        $hasBranding = filled($tenant->whatsapp_chat_phone)
            || (filled($tenant->description) && $tenant->description !== 'Digital services and VTU portal')
            || ! empty($tenant->logo_path)
            || ! empty($tenant->data['logo_url']);
        $hasSettlement = SettlementAccount::where('store_id', $tenant->id)->exists();
        $hasCommunity = (bool) ($tenant->joined_whatsapp_community ?? false);
        $hasCustomer = $tenant->users()->exists();

        if ($hasPrices && $hasBranding && $hasSettlement && $hasCommunity && $hasCustomer) {
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
        $tenant = Filament::getTenant();

        if ($tenant) {
            $tenant->storefront_link_copied = true;
            $tenant->save();
        }

        Notification::make()
            ->title('Link Copied')
            ->body('Storefront URL copied to clipboard! Share it with your customers on WhatsApp or social media.')
            ->success()
            ->send();
    }

    public function markCommunityJoined(): void
    {
        $tenant = Filament::getTenant();

        if ($tenant) {
            $tenant->joined_whatsapp_community = true;
            $tenant->save();
        }
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

        // 1. Set Selling Prices (data plans or airtime discounts custom configured)
        $step1Done = $tenant->storeDataPlans()->exists() || $tenant->storeAirtimeDiscounts()->exists();

        // 2. Customize Branding (has WhatsApp support phone, tagline, or custom logo)
        $step2Done = filled($tenant->whatsapp_chat_phone)
            || (filled($tenant->description) && $tenant->description !== 'Digital services and VTU portal')
            || ! empty($tenant->logo_path)
            || ! empty($tenant->data['logo_url']);

        // 3. Add Bank Settlement Account (so merchant can withdraw profits)
        $step3Done = SettlementAccount::where('store_id', $tenant->id)->exists();

        // 4. Join WhatsApp Community
        $step4Done = (bool) ($tenant->joined_whatsapp_community ?? false);

        // 5. Get First Customer (only completed when store has at least 1 registered customer)
        $hasCustomer = $tenant->users()->exists();
        $step5Done = $hasCustomer;

        $steps = [
            [
                'id' => 'set_prices',
                'title' => 'Set your selling prices & profit margins',
                'description' => 'Configure retail prices for SME data & airtime to set your profit.',
                'completed' => $step1Done,
                'url' => StoreDataPlanResource::getUrl('index', ['tenant' => $tenant->public_id]),
                'action_label' => $step1Done ? 'Manage Prices' : 'Set Prices →',
                'icon' => 'heroicon-m-tag',
            ],
            [
                'id' => 'customize_branding',
                'title' => 'Personalize your website & WhatsApp support',
                'description' => 'Add your logo and connect WhatsApp for customer orders.',
                'completed' => $step2Done,
                'url' => StoreSettings::getUrl(['tenant' => $tenant->public_id]),
                'action_label' => $step2Done ? 'Settings' : 'Customize →',
                'icon' => 'heroicon-m-paint-brush',
            ],
            [
                'id' => 'add_settlement',
                'title' => 'Add your bank settlement account',
                'description' => 'Link your Nigerian bank account to withdraw profits anytime.',
                'completed' => $step3Done,
                'url' => StoreWallet::getUrl(['tenant' => $tenant->public_id]),
                'action_label' => $step3Done ? 'Bank Added ✓' : 'Add Bank →',
                'icon' => 'heroicon-m-building-library',
            ],
            [
                'id' => 'join_community',
                'title' => 'Join our Merchant WhatsApp Community',
                'description' => 'Connect with other merchants, receive platform updates, and marketing tips.',
                'completed' => $step4Done,
                'url' => 'https://chat.whatsapp.com/BRaczSTbj3fG5CmG3qttf1',
                'is_external' => true,
                'action_label' => $step4Done ? 'Joined ✓' : 'Join Group ↗',
                'icon' => 'heroicon-m-chat-bubble-left-right',
            ],
            [
                'id' => 'first_customer',
                'title' => 'Get your first customer',
                'description' => $hasCustomer
                    ? 'Congratulations! You have active customers on your website.'
                    : "Share your website link at {$storeUrl} to get your first customer.",
                'completed' => $step5Done,
                'url' => $storeUrl,
                'is_external' => true,
                'action_label' => $hasCustomer ? 'View Website' : 'Share Your Website',
                'icon' => 'heroicon-m-user-plus',
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
