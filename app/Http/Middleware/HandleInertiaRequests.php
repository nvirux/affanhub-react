<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'name' => config('app.name'),
            'store' => tenant() ? [
                'id' => tenant('id'),
                'name' => tenant('name'),
                'logo_url' => tenant('logo_path') ? global_asset('storage/' . tenant('logo_path')) : null,
                'whatsapp_chat_enabled' => (bool) (tenant('whatsapp_chat_enabled') ?? false),
                'whatsapp_chat_phone' => (function() {
                    $phone = tenant('whatsapp_chat_phone');
                    if (!$phone) return null;
                    $phone = preg_replace('/\D/', '', $phone);
                    if (str_starts_with($phone, '0')) {
                        $phone = '234' . substr($phone, 1);
                    }
                    return $phone;
                })(),
                'whatsapp_chat_message' => tenant('whatsapp_chat_message'),
                'tawk_chat_enabled' => (bool) (tenant('tawk_chat_enabled') ?? false),
                'tawk_property_id' => tenant('tawk_property_id'),
                'tawk_widget_id' => tenant('tawk_widget_id'),
                'dashboard_subtitle' => tenant('dashboard_subtitle'),
            ] : null,
            'auth' => [
                'user' => fn () => $request->user() ? [
                    'id' => $request->user()->id,
                    'name' => $request->user()->name,
                    'email' => $request->user()->email,
                    'phone' => $request->user()->phone,
                    'nin' => $request->user()->nin,
                    'bvn' => $request->user()->bvn,
                    'wallet_balance' => $request->user()->wallet('main')->balance,
                    'virtual_account' => $request->user()->virtualAccounts()->where('status', 'active')->first(),
                ] : null,
            ],
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error' => fn () => $request->session()->get('error'),
            ],
            'sidebarOpen' => ! $request->hasCookie('sidebar_state') || $request->cookie('sidebar_state') === 'true',
        ];
    }
}
