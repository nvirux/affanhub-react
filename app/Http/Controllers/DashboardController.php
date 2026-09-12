<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\StoreService;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    /**
     * Display the customer storefront dashboard with enabled services & recent transactions.
     */
    public function index(Request $request): Response
    {
        $tenant = tenant();
        $allServices = Service::where('is_active', true)->get();

        $enabledServices = [];
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

            if ($hasAccess && $isEnabled) {
                $enabledServices[] = [
                    'id' => $service->id,
                    'name' => $service->name,
                    'key' => $service->key,
                    'category' => $service->category,
                    'icon' => $service->icon,
                    'description' => $service->description,
                    'sort_order' => $sortOrder,
                ];
            }
        }

        usort($enabledServices, fn ($a, $b) => $a['sort_order'] <=> $b['sort_order']);

        $recentTransactions = Transaction::query()
            ->where('user_id', Auth::id())
            ->latest()
            ->take(8)
            ->get()
            ->map(fn ($tx) => [
                'id' => $tx->id,
                'reference' => $tx->reference,
                'service_type' => $tx->service_type,
                'recipient' => $tx->recipient,
                'amount' => (float) $tx->amount,
                'discount' => (float) ($tx->discount ?? 0.00),
                'amount_paid' => (float) ($tx->amount_paid > 0 ? $tx->amount_paid : $tx->amount),
                'status' => in_array($tx->status, ['success', 'successful', 'completed'], true) ? 'successful' : ($tx->status === 'pending' ? 'pending' : 'failed'),
                'created_at' => $tx->created_at?->diffForHumans() ?? 'Just now',
                'date' => $tx->created_at?->format('M d, Y') ?? '',
            ]);

        return Inertia::render('Storefront/Dashboard', [
            'store_services' => $enabledServices,
            'recent_transactions' => $recentTransactions,
        ]);
    }
}
