<?php

namespace App\Filament\Merchant\Pages;

use App\Models\Domain;
use App\Models\Plan;
use App\Models\Store;
use Filament\Forms\Components\Placeholder;
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
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\HtmlString;
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
                        ->description('Set your business name and storefront link')
                        ->schema([
                            Placeholder::make('identity_guide')
                                ->hiddenLabel()
                                ->content(new HtmlString('
                                    <div style="background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 12px 16px; margin-bottom: 6px;">
                                        <div style="display: flex; gap: 10px; align-items: flex-start;">
                                            <span style="font-size: 1.25rem; line-height: 1;">💡</span>
                                            <div style="font-size: 0.815rem; color: #334155; line-height: 1.45;">
                                                <strong style="display: block; margin-bottom: 2px; color: #0f172a;">How your store web address works:</strong>
                                                Enter your business brand name below, then choose a <strong>short, easy-to-remember web address</strong> (e.g. <em>mydata</em>). Your customers will visit your website at this address to purchase data and airtime. You can also connect a custom domain (like <em>.com</em> or <em>.ng</em>) anytime from your store settings!
                                            </div>
                                        </div>
                                    </div>
                                ')),

                            TextInput::make('name')
                                ->label('Store Name')
                                ->placeholder('e.g. MyData Hub')
                                ->required()
                                ->maxLength(255)
                                ->helperText('The public brand name of your business shown on receipts, invoices, and your storefront header.'),

                            TextInput::make('subdomain')
                                ->label('Store Subdomain / Web Address')
                                ->placeholder('mydata')
                                ->required()
                                ->minLength(3)
                                ->maxLength(30)
                                ->rules(['regex:/^[a-zA-Z]([a-zA-Z0-9\-]*[a-zA-Z0-9])?$/'])
                                ->live(debounce: 400)
                                ->prefix('https://')
                                ->suffix('.'.$baseDomain)
                                ->notIn(config('tenancy.reserved_tenant_ids', []))
                                ->validationMessages([
                                    'not_in' => 'This store URL is reserved by AffanHub and cannot be used.',
                                    'regex' => 'The store web address must start with a letter and contain only letters, numbers, and hyphens (cannot be purely numbers or end with a hyphen).',
                                    'min' => 'The store web address must be at least 3 characters.',
                                    'max' => 'The store web address cannot exceed 30 characters.',
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
                                    if (blank($state)) {
                                        return new HtmlString('Choose a short, catchy username for your link (e.g. <strong>mydata</strong>). Avoid long sentences so it is easy for customers to type.');
                                    }

                                    if (! preg_match('/^[a-zA-Z]([a-zA-Z0-9\-]*[a-zA-Z0-9])?$/', $state)) {
                                        return new HtmlString("<span style='color: #dc2626; font-weight: 600;'>✗ Subdomain must start with a letter and contain only letters, numbers, and hyphens (cannot be purely numbers).</span>");
                                    }

                                    if (strlen($state) < 3) {
                                        return new HtmlString("<span style='color: #dc2626; font-weight: 600;'>✗ Subdomain must be at least 3 characters.</span>");
                                    }

                                    $fullDomain = strtolower(trim($state)).'.'.$baseDomain;
                                    $isReserved = in_array(strtolower(trim($state)), config('tenancy.reserved_tenant_ids', []));
                                    $isTaken = Domain::where('domain', $fullDomain)->exists();

                                    if ($isReserved || $isTaken) {
                                        return new HtmlString("<span style='color: #dc2626; font-weight: 600;'>✗ <strong>{$fullDomain}</strong> is not available.</span>");
                                    }

                                    return new HtmlString("<span style='color: #059669; font-weight: 600;'>✓ <strong>{$fullDomain}</strong> is available! Customers will visit this address.</span>");
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
        $ip = request()->ip() ?? '127.0.0.1';
        $throttleKey = 'merchant_store_create_ip:'.$ip;
        $isLocal = app()->environment('local', 'testing') || in_array($ip, ['127.0.0.1', '::1']);

        if (! $isLocal && RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            $hours = max(1, (int) ceil($seconds / 3600));

            Notification::make()
                ->title('Store Creation Limit Reached')
                ->body("Too many stores have been created from your network. Please try again in {$hours} hour(s) or contact support.")
                ->danger()
                ->send();

            throw ValidationException::withMessages([
                'data.name' => "Too many stores have been created from your network. Please try again in {$hours} hour(s) or contact support.",
            ]);
        }

        $owner = auth()->user();

        if ($owner && ! $owner->canCreateMoreStores()) {
            $max = $owner->max_stores ?? 3;

            Notification::make()
                ->title('Account Store Limit Reached')
                ->body("You have reached your account limit of {$max} stores. Please contact support to increase your limit.")
                ->danger()
                ->send();

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

        if (! $isLocal) {
            RateLimiter::hit($throttleKey, 86400); // 24 hours decay
        }

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
        return route('filament.merchant.pages.dashboard', [
            'tenant' => $this->tenant->public_id,
        ]);
    }
}
