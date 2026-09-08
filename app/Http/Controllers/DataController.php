<?php

namespace App\Http\Controllers;

use App\Models\DataPlan;
use App\Models\DataType;
use App\Models\Network;
use App\Models\StoreDataPlan;
use App\Services\Vtu\DataService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class DataController extends Controller
{
    /**
     * Display the Data Bundle purchase page.
     */
    public function index()
    {
        return Inertia::render('Storefront/Vtu/Data');
    }

    /**
     * Return available networks, data types, and data plans with store pricing.
     */
    public function getPlans()
    {
        $tenantId = tenant('id');
        $networks = Network::where('is_active', true)->orderBy('sort_order')->get();
        $dataTypes = DataType::where('is_active', true)->get();
        $plans = DataPlan::with(['network', 'dataType'])->where('is_active', true)->get();

        $customStorePlans = StoreDataPlan::where('store_id', $tenantId)
            ->get()
            ->keyBy('data_plan_id');

        $formattedPlans = $plans->map(function ($plan) use ($customStorePlans) {
            $custom = $customStorePlans->get($plan->id);
            $retailPrice = $custom ? (float) $custom->selling_price : (float) $plan->default_retail_price;
            $isEnabled = $custom ? (bool) $custom->is_enabled : (bool) $plan->is_active;
            $isBestOffer = ($custom && $custom->is_best_offer !== null) ? (bool) $custom->is_best_offer : (bool) $plan->is_best_offer;

            return [
                'id' => $plan->id,
                'network_id' => $plan->network_id,
                'network_slug' => $plan->network->slug ?? '',
                'network_name' => $plan->network->name ?? '',
                'data_type_id' => $plan->data_type_id,
                'data_type_slug' => $plan->dataType->slug ?? '',
                'data_type_name' => $plan->dataType->name ?? '',
                'name' => $plan->name,
                'size_mb' => $plan->size_mb,
                'validity' => $plan->validity,
                'validity_group' => strtolower($plan->validityGroup()),
                'price' => $retailPrice,
                'is_enabled' => $isEnabled,
                'is_best_offer' => $isBestOffer,
            ];
        })->filter(fn ($p) => $p['is_enabled'])->values();

        return response()->json([
            'success' => true,
            'networks' => $networks,
            'data_types' => $dataTypes,
            'plans' => $formattedPlans,
        ]);
    }

    /**
     * Handle Data Bundle Purchase for Storefront Customer via DataService & VTULab.
     */
    public function purchase(Request $request, DataService $dataService)
    {
        $user = Auth::user();

        if (! $user) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Unauthenticated user.'], 401);
            }

            return Redirect::back()->with('error', 'Unauthenticated user.');
        }

        $validated = $request->validate([
            'data_plan_id' => 'required|exists:data_plans,id',
            'phone' => 'required|string|min:10|max:14',
        ]);

        $dataPlan = DataPlan::findOrFail($validated['data_plan_id']);
        $tenantId = tenant('id') ?? $user->store_id ?? 1;

        // Get or create store data plan record
        $storeDataPlan = StoreDataPlan::firstOrCreate([
            'store_id' => $tenantId,
            'data_plan_id' => $dataPlan->id,
        ], [
            'selling_price' => $dataPlan->default_retail_price,
            'is_enabled' => true,
        ]);

        try {
            $result = $dataService->buyDataPlan($user, $storeDataPlan, $validated['phone']);

            if ($request->wantsJson()) {
                return response()->json($result);
            }

            if ($result['success']) {
                return Redirect::back()->with('success', $result['message']);
            }

            return Redirect::back()->with('error', $result['message']);
        } catch (\Throwable $e) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
            }

            return Redirect::back()->with('error', $e->getMessage());
        }
    }
}
