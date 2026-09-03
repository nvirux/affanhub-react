<?php

namespace App\Http\Controllers;

use App\Models\DataPlan;
use App\Models\DataType;
use App\Models\Network;
use App\Models\PlanDataPrice;
use App\Models\Store;
use App\Models\StoreDataPlan;
use App\Models\Subscription;
use App\Models\Transaction;
use App\Services\WalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
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
     * Handle Data Bundle Purchase for Storefront Customer.
     */
    public function purchase(Request $request, WalletService $walletService)
    {
        $user = Auth::user();

        if (!$user) {
            return Redirect::back()->with('error', 'Unauthenticated user.');
        }

        $validated = $request->validate([
            'data_plan_id' => 'required|exists:data_plans,id',
            'phone' => 'required|string|min:10|max:14',
        ]);

        $dataPlan = DataPlan::with(['network', 'dataType'])->findOrFail($validated['data_plan_id']);
        $tenantId = tenant('id');
        $store = Store::find($tenantId);

        // Normalize phone
        $phone = preg_replace('/\D/', '', $validated['phone']);
        if (str_starts_with($phone, '234') && strlen($phone) === 13) {
            $phone = '0' . substr($phone, 3);
        }

        if (strlen($phone) !== 11) {
            return Redirect::back()->with('error', 'Please enter a valid 11-digit phone number.');
        }

        // 1. Customer Retail Price
        $storePlan = StoreDataPlan::where('store_id', $tenantId)
            ->where('data_plan_id', $dataPlan->id)
            ->first();

        $customerPrice = $storePlan ? (float) $storePlan->selling_price : (float) $dataPlan->default_retail_price;

        // 2. Merchant Wholesale Cost (Resolved via Tier Subscription)
        $wholesaleCost = (float) ($dataPlan->selling_price ?? $dataPlan->default_retail_price);

        if ($store) {
            $subscription = Subscription::where('store_id', $store->id)
                ->whereIn('status', ['active', 'trialing'])
                ->latest()
                ->first();

            if ($subscription && $subscription->plan_id) {
                $planPrice = PlanDataPrice::where('plan_id', $subscription->plan_id)
                    ->where('data_plan_id', $dataPlan->id)
                    ->first();

                if ($planPrice && $planPrice->wholesale_price !== null) {
                    $wholesaleCost = (float) $planPrice->wholesale_price;
                }
            }
        }

        // Net Merchant Profit
        $merchantProfit = max(0, $customerPrice - $wholesaleCost);

        // Check customer wallet balance
        $customerWallet = $user->wallet('main');
        if ((float) $customerWallet->balance < $customerPrice) {
            return Redirect::back()->with('error', sprintf('Insufficient wallet balance. Price is ₦%s but your balance is ₦%s.', number_format($customerPrice, 2), number_format($customerWallet->balance, 2)));
        }

        // Check store main wallet balance
        $storeMainWallet = $store ? $store->mainWallet() : null;
        if ($storeMainWallet && (float) $storeMainWallet->balance < $wholesaleCost) {
            return Redirect::back()->with('error', 'Service temporarily unavailable. Store wholesale balance insufficient.');
        }

        try {
            DB::transaction(function () use ($user, $customerWallet, $store, $storeMainWallet, $dataPlan, $customerPrice, $wholesaleCost, $merchantProfit, $phone, $tenantId, $walletService) {
                $reference = 'DATA_' . strtoupper(Str::random(12));
                $txMeta = [
                    'phone' => $phone,
                    'network' => $dataPlan->network->name ?? 'Mobile Network',
                    'data_type' => $dataPlan->dataType->name ?? 'Data',
                    'plan_name' => $dataPlan->name,
                    'validity' => $dataPlan->validity,
                    'retail_price' => $customerPrice,
                    'wholesale_cost' => $wholesaleCost,
                    'profit' => $merchantProfit,
                ];

                // 1. Debit Customer Main Wallet
                $walletService->debit(
                    $customerWallet,
                    $customerPrice,
                    'purchase',
                    sprintf('%s %s Data Purchase for %s', $dataPlan->network->name ?? '', $dataPlan->name, $phone),
                    $txMeta,
                    'CUST_' . $reference
                );

                // 2. Debit Store Main Wallet (Wholesale Cost)
                if ($storeMainWallet && $wholesaleCost > 0) {
                    $walletService->debit(
                        $storeMainWallet,
                        $wholesaleCost,
                        'wholesale_cost',
                        sprintf('Wholesale Data Cost for %s (%s)', $dataPlan->name, $phone),
                        $txMeta,
                        'ST_COST_' . $reference
                    );
                }

                // 3. Credit Store Profit Wallet (Net Profit)
                if ($store && $merchantProfit > 0) {
                    $storeProfitWallet = $store->profitWallet();
                    $walletService->credit(
                        $storeProfitWallet,
                        $merchantProfit,
                        'profit_earned',
                        sprintf('Net Profit from %s Data sale to %s', $dataPlan->name, $phone),
                        $txMeta,
                        'ST_PROF_' . $reference
                    );
                }

                // 4. Log Customer Transaction
                Transaction::create([
                    'store_id' => $tenantId,
                    'user_id' => $user->id,
                    'type' => 'data',
                    'amount' => $customerPrice,
                    'reference' => $reference,
                    'status' => 'successful',
                    'meta' => $txMeta,
                ]);
            });

            return Redirect::back()->with('success', sprintf('Successfully sent %s %s Data to %s!', $dataPlan->network->name ?? '', $dataPlan->name, $phone));
        } catch (\Throwable $e) {
            return Redirect::back()->with('error', 'Failed to process data purchase: ' . $e->getMessage());
        }
    }
}
