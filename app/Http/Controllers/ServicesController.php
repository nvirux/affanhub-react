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
            $hasAccess = true;
            if ($service->feature_id && $service->feature) {
                $hasAccess = $tenant->hasFeature($service->feature->slug);
            }

            $setting = StoreService::where('store_id', $tenant->id)
                ->where('service_id', $service->id)
                ->first();

            $isEnabled = $setting ? (bool) $setting->is_enabled : true;
            $sortOrder = $setting ? (int) $setting->sort_order : $service->sort_order;

            $services[] = [
                'id' => $service->id,
                'name' => $service->name,
                'key' => $service->key,
                'category' => $service->category,
                'icon' => $service->icon,
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
}
