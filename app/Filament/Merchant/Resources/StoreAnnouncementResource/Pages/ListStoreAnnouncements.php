<?php

namespace App\Filament\Merchant\Resources\StoreAnnouncementResource\Pages;

use App\Filament\Merchant\Resources\StoreAnnouncementResource;
use App\Models\StoreMobileApp;
use App\Models\StorePushNotification;
use App\Services\OneSignalService;
use Filament\Actions\CreateAction;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListStoreAnnouncements extends ListRecords
{
    protected static string $resource = StoreAnnouncementResource::class;

    protected string $view = 'filament.merchant.resources.announcements.list-announcements';

    public ?string $activeTab = 'announcements'; // 'announcements' or 'push'

    public string $pushTitle = '';

    public string $pushMessage = '';

    public string $destinationPreset = 'dashboard';

    public string $customUrl = '';

    protected $queryString = [
        'activeTab' => ['except' => 'announcements'],
    ];

    public function mount(): void
    {
        parent::mount();

        $requestedTab = request()->query('tab') ?? request()->query('activeTab');
        if (in_array($requestedTab, ['announcements', 'push'], true)) {
            $this->activeTab = $requestedTab;
        }
    }

    public function setActiveTab(string $tab): void
    {
        if (in_array($tab, ['announcements', 'push'], true)) {
            $this->activeTab = $tab;
        }
    }

    protected function getHeaderActions(): array
    {
        if ($this->activeTab === 'push') {
            return [];
        }

        return [
            CreateAction::make()
                ->label('New Announcement')
                ->icon('heroicon-o-plus')
                ->modalHeading('Create Customer Announcement')
                ->modalWidth('2xl'),
        ];
    }

    public function getMobileAppProperty(): ?StoreMobileApp
    {
        $tenant = Filament::getTenant();

        return $tenant?->mobileApp;
    }

    public function getDestinationPresetsProperty(): array
    {
        return [
            'dashboard' => [
                'label' => 'Storefront Dashboard',
                'path' => '/dashboard',
                'description' => 'Opens user dashboard / home screen',
                'icon' => '🏠',
            ],
            'data' => [
                'label' => 'Buy Data',
                'path' => '/vtu/data',
                'description' => 'Opens data bundles purchase screen',
                'icon' => '📶',
            ],
            'airtime' => [
                'label' => 'Buy Airtime',
                'path' => '/vtu/airtime',
                'description' => 'Opens airtime top-up screen',
                'icon' => '📱',
            ],
            'wallet' => [
                'label' => 'Fund Wallet',
                'path' => '/wallet',
                'description' => 'Opens wallet & virtual accounts screen',
                'icon' => '💳',
            ],
            'earn' => [
                'label' => 'Refer & Earn',
                'path' => '/earn',
                'description' => 'Opens referral rewards page',
                'icon' => '🎁',
            ],
            'custom' => [
                'label' => 'Custom Link',
                'path' => '',
                'description' => 'Provide your own relative path or full web link',
                'icon' => '🔗',
            ],
        ];
    }

    public function getEffectiveActionUrlProperty(): ?string
    {
        if ($this->destinationPreset === 'custom') {
            return ! empty(trim($this->customUrl)) ? trim($this->customUrl) : null;
        }

        $presets = $this->destinationPresets;

        return $presets[$this->destinationPreset]['path'] ?? '/dashboard';
    }

    public function sendBroadcast(OneSignalService $oneSignal): void
    {
        $tenant = Filament::getTenant();
        if (! $tenant) {
            return;
        }

        $this->validate([
            'pushTitle' => 'required|string|max:100',
            'pushMessage' => 'required|string|max:255',
            'destinationPreset' => 'required|string',
            'customUrl' => 'nullable|string|max:500',
        ]);

        $targetUrl = $this->effectiveActionUrl;

        $result = $oneSignal->sendToStore(
            storeId: $tenant->id,
            title: $this->pushTitle,
            message: $this->pushMessage,
            actionUrl: $targetUrl,
            sentById: auth()->id()
        );

        if ($result['success']) {
            Notification::make()
                ->title('Push Broadcast Dispatched!')
                ->body("Notification sent successfully to OneSignal for {$tenant->name}'s app users.")
                ->success()
                ->send();

            $this->reset(['pushTitle', 'pushMessage', 'customUrl']);
            $this->destinationPreset = 'dashboard';
        } else {
            Notification::make()
                ->title('Failed to Dispatch Notification')
                ->body($result['error'] ?? 'Could not dispatch notification. Please verify OneSignal setup.')
                ->danger()
                ->send();
        }
    }

    public function getRecentPushNotificationsProperty()
    {
        $tenant = Filament::getTenant();
        if (! $tenant) {
            return collect();
        }

        return StorePushNotification::where('store_id', $tenant->id)
            ->latest()
            ->take(15)
            ->get();
    }
}
