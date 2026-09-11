<?php

namespace App\Filament\Merchant\Pages;

use App\Models\Domain;
use App\Models\Plan;
use App\Models\Store;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Tenancy\RegisterTenant;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class RegisterStore extends RegisterTenant
{
    public function getMaxWidth(): Width|string|null
    {
        return Width::FourExtraLarge;
    }

    public static function getLabel(): string
    {
        return 'Create Your Store';
    }

    public function getHeading(): string|Htmlable
    {
        return 'Create Your Digital Storefront';
    }

    public function getSubheading(): string|Htmlable|null
    {
        return 'Set up your store name and branding in just two simple steps.';
    }

    public function form(Schema $schema): Schema
    {
        $baseDomain = parse_url(config('app.url'), PHP_URL_HOST) ?? 'localhost';

        return $schema
            ->components([
                Wizard::make([
                    // Step 1: Store Details & Subdomain
                    Step::make('Store Identity')
                        ->icon(Heroicon::OutlinedBuildingStorefront)
                        ->description('Name and web address')
                        ->schema([
                            TextInput::make('name')
                                ->label('Store Name')
                                ->placeholder('e.g. Annur Data Services')
                                ->required()
                                ->maxLength(255)
                                ->live(onBlur: true)
                                ->afterStateUpdated(function (string $operation, ?string $state, $set, $get) {
                                    if (blank($get('subdomain')) && filled($state)) {
                                        $set('subdomain', Str::slug($state));
                                    }
                                }),

                            TextInput::make('subdomain')
                                ->label('Store Subdomain / Web Address')
                                ->placeholder('annur')
                                ->required()
                                ->alphaDash()
                                ->live(debounce: 400)
                                ->prefix('https://')
                                ->suffix('.'.$baseDomain)
                                ->notIn(config('tenancy.reserved_tenant_ids', []))
                                ->validationMessages([
                                    'not_in' => 'This store URL is reserved by AffanHub and cannot be used.',
                                ])
                                ->rule(function () use ($baseDomain) {
                                    return function (string $attribute, $value, \Closure $fail) use ($baseDomain) {
                                        $fullDomain = strtolower(trim($value)).'.'.$baseDomain;
                                        if (Domain::where('domain', $fullDomain)->exists()) {
                                            $fail('This store subdomain is already taken.');
                                        }
                                    };
                                })
                                ->helperText(function (?string $state) use ($baseDomain): ?HtmlString {
                                    if (blank($state) || ! preg_match('/^[a-zA-Z0-9_-]+$/', $state)) {
                                        return new HtmlString('Enter a unique web address for your customers to visit.');
                                    }

                                    $fullDomain = strtolower(trim($state)).'.'.$baseDomain;
                                    $isReserved = in_array(strtolower(trim($state)), config('tenancy.reserved_tenant_ids', []));
                                    $isTaken = Domain::where('domain', $fullDomain)->exists();

                                    if ($isReserved || $isTaken) {
                                        return new HtmlString("<span style='color: #dc2626; font-weight: 600;'>✗ <strong>{$fullDomain}</strong> is not available.</span>");
                                    }

                                    return new HtmlString("<span style='color: #059669; font-weight: 600;'>✓ <strong>{$fullDomain}</strong> is available!</span>");
                                }),
                        ]),

                    // Step 2: Storefront Setup & Contact
                    Step::make('Storefront Setup')
                        ->icon(Heroicon::OutlinedPaintBrush)
                        ->description('Branding & customer support')
                        ->schema([
                            TextInput::make('whatsapp_chat_phone')
                                ->label('WhatsApp Support Phone Number')
                                ->tel()
                                ->placeholder('08012345678')
                                ->helperText('Your customers can tap a button on your storefront to message you directly on WhatsApp.'),

                            Textarea::make('description')
                                ->label('Store Description / Tagline')
                                ->placeholder('e.g. Fast & affordable MTN, Airtel, and Glo data bundles at wholesale prices.')
                                ->rows(2)
                                ->maxLength(255)
                                ->helperText('Short description shown under your store name on customer receipts and homepage.'),

                            Select::make('primary_color')
                                ->label('Brand Accent Color')
                                ->options([
                                    '#f59e0b' => 'Amber Gold (AffanHub Default)',
                                    '#2563eb' => 'Royal Blue',
                                    '#059669' => 'Emerald Green',
                                    '#7c3aed' => 'Violet Purple',
                                    '#dc2626' => 'Crimson Red',
                                    '#0f172a' => 'Midnight Slate',
                                ])
                                ->default('#f59e0b')
                                ->helperText('Sets the primary theme accent color for your public storefront buttons and headers.'),
                        ]),
                ])
                    ->submitAction(
                        $this->getRegisterFormAction()
                            ->label('Create & Launch Store 🚀')
                    ),
            ]);
    }

    protected function getFormActions(): array
    {
        return [];
    }

    public function mount(): void
    {
        parent::mount();

        $owner = auth()->user();
        if ($owner && ! $owner->canCreateMoreStores()) {
            $max = $owner->max_stores ?? 3;
            Notification::make()
                ->title('Store Limit Reached')
                ->body("You have reached your account limit of {$max} stores. Please contact support to increase your limit.")
                ->danger()
                ->send();

            $firstStore = $owner->stores()->first();
            if ($firstStore) {
                $this->redirect(route('filament.merchant.pages.dashboard', ['tenant' => $firstStore->public_id]));
            }
        }
    }

    protected function handleRegistration(array $data): Model
    {
        $owner = auth()->user();

        if ($owner && ! $owner->canCreateMoreStores()) {
            $max = $owner->max_stores ?? 3;
            throw ValidationException::withMessages([
                'data.name' => "You have reached your account limit of {$max} stores. Please contact support to increase your limit.",
            ]);
        }

        $store = Store::create([
            'name' => $data['name'],
            'owner_id' => auth()->id(),
            'description' => $data['description'] ?? null,
            'whatsapp_chat_phone' => $data['whatsapp_chat_phone'] ?? null,
            'primary_color' => $data['primary_color'] ?? '#f59e0b',
        ]);

        if ($owner) {
            $owner->stores()->attach($store->id, ['role' => 'owner']);
        }

        // Automatically create the domain for this new store!
        $baseDomain = parse_url(config('app.url'), PHP_URL_HOST) ?? 'localhost';

        $store->domains()->create([
            'domain' => strtolower(trim($data['subdomain'])).'.'.$baseDomain,
            'is_primary' => true,
        ]);

        // Every newly created store starts with the Starter (Free Forever) plan
        $starterPlan = Plan::where('slug', 'starter')->first();

        if ($starterPlan) {
            $store->subscriptions()->create([
                'plan_id' => $starterPlan->id,
                'price' => 0.00,
                'billing_interval' => 'month',
                'status' => 'active',
                'starts_at' => now(),
                'ends_at' => null, // Lifetime free
            ]);
        }

        return $store;
    }

    protected function getRedirectUrl(): ?string
    {
        // Redirect to the dedicated plan selection page
        return route('filament.merchant.pages.onboarding.plan', [
            'tenant' => $this->tenant->public_id,
        ]);
    }
}
