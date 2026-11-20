<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\StoreService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ServicesController extends Controller
{
    /**
     * Display all available storefront services.
     */
    public function index(Request $request): Response
    {
        $tenant = tenant();
        $allServices = Service::where('is_active', true)->get();

        $services = [];
        foreach ($allServices as $service) {
            $hasAccess = $service->isAccessibleByStore($tenant);

            $setting = StoreService::where('store_id', $tenant->id)
                ->where('service_id', $service->id)
                ->first();

            $isEnabled = $setting ? (bool) $setting->is_enabled : true;
            $sortOrder = $setting ? (int) $setting->sort_order : $service->sort_order;

            $icon = $setting ? $setting->getResolvedIcon() : (
                str_contains($service->key, 'cac') ? 'cac' : (
                    str_contains($service->key, 'nin') ? 'nimc' : (
                        str_contains($service->key, 'bvn') ? 'nibss' : ($service->icon ?? 'Smartphone')
                    )
                )
            );

            $services[] = [
                'id' => $service->id,
                'name' => $service->name,
                'key' => $service->key,
                'category' => $service->category,
                'icon' => $icon,
                'description' => $service->description,
                'sort_order' => $sortOrder,
                'is_available' => ($hasAccess && $isEnabled),
            ];
        }

        usort($services, fn ($a, $b) => $a['sort_order'] <=> $b['sort_order']);

        return Inertia::render('Storefront/Services', [
            'services' => $services,
        ]);
    }

    /**
     * Render the ServiceUnavailable page for any service.
     */
    public function showUnavailable(Request $request, string $serviceKey, ?string $customTitle = null, ?string $customCategory = null): Response
    {
        $tenant = tenant();
        $service = Service::where('key', $serviceKey)->first();

        $name = $customTitle ?? ($service?->name ?? ucwords(str_replace(['_', '-'], ' ', $serviceKey)));
        $category = $customCategory ?? match ($service?->category) {
            'identity' => 'Identity',
            default => 'VTU',
        };

        return Inertia::render('Storefront/ServiceUnavailable', [
            'serviceName' => $name,
            'serviceKey' => $serviceKey,
            'category' => $category,
            'message' => "{$name} services are currently not active. Please contact support for assistance.",
            'store' => [
                'name' => $tenant?->name,
                'contact_email' => $tenant?->contact_email,
                'contact_phone' => $tenant?->contact_phone,
                'whatsapp_chat_phone' => $tenant?->whatsapp_chat_phone,
                'social_whatsapp' => $tenant?->social_whatsapp,
            ],
        ]);
    }

    public function bulkSms(Request $request): Response
    {
        return $this->showUnavailable($request, 'bulk_sms', 'Bulk SMS', 'VTU');
    }

    public function cable(Request $request): Response
    {
        return $this->showUnavailable($request, 'cable', 'Cable TV', 'VTU');
    }

    public function electricity(Request $request): Response
    {
        return $this->showUnavailable($request, 'electricity', 'Electricity Bills', 'VTU');
    }

    public function education(Request $request): Response
    {
        return $this->showUnavailable($request, 'education', 'Exam PINs & Education', 'VTU');
    }

    public function ninValidation(Request $request): Response
    {
        return $this->showUnavailable($request, 'nin_validation', 'NIN Validation', 'Identity');
    }

    public function ipeClearance(Request $request): Response
    {
        return $this->showUnavailable($request, 'ipe_clearance', 'IPE Clearance', 'Identity');
    }
}
