<?php

namespace App\Http\Controllers;

use App\Models\AirtimeDiscount;
use App\Models\Network;
use App\Models\Store;
use App\Models\StoreAirtimeDiscount;
use App\Services\Vtu\AirtimeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class AirtimeController extends Controller
{
    /**
     * Display the Airtime purchase page with dynamic network configurations.
     */
    public function index(): Response
    {
        $user = Auth::user();
        $store = (method_exists($user, 'store') ? $user?->store : null)
            ?? Store::where('owner_id', $user?->id)->first()
            ?? Store::first();

        $networks = Network::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $storeId = $store?->id;
        $storeDiscounts = $storeId
            ? StoreAirtimeDiscount::where('store_id', $storeId)->get()->keyBy('network_id')
            : collect();

        $globalDiscounts = AirtimeDiscount::all()->keyBy('network_id');

        $formattedNetworks = $networks->map(function ($network) use ($storeDiscounts, $globalDiscounts) {
            $global = $globalDiscounts->get($network->id);
            $storeDisc = $storeDiscounts->get($network->id);

            $isGloballyActive = $global ? (bool) $global->is_active : true;
            $isEnabled = $storeDisc ? (bool) $storeDisc->is_enabled : $isGloballyActive;

            $retailDiscount = ($storeDisc && $storeDisc->selling_discount !== null)
                ? (float) $storeDisc->selling_discount
                : ($global ? (float) $global->default_retail_discount : 1.50);

            $minAmount = $storeDisc?->min_amount !== null
                ? (float) $storeDisc->min_amount
                : ($global ? (float) $global->min_amount : 50.00);

            $maxAmount = $storeDisc?->max_amount !== null
                ? (float) $storeDisc->max_amount
                : ($global ? (float) $global->max_amount : 50000.00);

            return [
                'id' => $network->id,
                'name' => $network->name,
                'slug' => strtolower($network->slug),
                'discount' => $retailDiscount,
                'min_amount' => $minAmount,
                'max_amount' => $maxAmount,
                'is_enabled' => $isEnabled,
            ];
        })->filter(fn ($n) => $n['is_enabled'])->values();

        $mainWallet = $user?->wallet('main');

        return Inertia::render('Storefront/Vtu/Airtime', [
            'networks' => $formattedNetworks,
            'wallet_balance' => (float) ($mainWallet?->balance ?? 0.00),
        ]);
    }

    /**
     * Handle Airtime Recharge for Storefront Customer.
     */
    public function purchase(Request $request, AirtimeService $airtimeService): mixed
    {
        $user = Auth::user();

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated. Please log in to complete your airtime recharge.',
            ], 401);
        }

        $validated = $request->validate([
            'network_id' => 'required|exists:networks,id',
            'amount' => 'required|numeric|min:50',
            'phone' => 'required|string|min:10|max:14',
            'transaction_pin' => 'required|string',
        ]);

        if (! $user->hasTransactionPin() || ! Hash::check($validated['transaction_pin'], $user->transaction_pin_hash)) {
            throw ValidationException::withMessages([
                'transaction_pin' => 'Incorrect 4-digit transaction PIN. Please try again.',
            ]);
        }

        $network = Network::findOrFail($validated['network_id']);

        try {
            $result = $airtimeService->buyAirtime(
                $user,
                $network,
                (float) $validated['amount'],
                $validated['phone']
            );

            if ($request->wantsJson() && ! $request->header('X-Inertia')) {
                return response()->json($result, $result['success'] ? 200 : 422);
            }

            if (! empty($result['reference'])) {
                return redirect()->route('transactions.show', $result['reference']);
            }

            return Redirect::back()->with('error', $result['message'] ?? 'Airtime recharge failed.');
        } catch (\InvalidArgumentException $e) {
            if ($request->wantsJson() && ! $request->header('X-Inertia')) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ], 422);
            }

            return Redirect::back()->with('error', $e->getMessage());
        } catch (\Throwable $e) {
            if ($request->wantsJson() && ! $request->header('X-Inertia')) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ], 400);
            }

            return Redirect::back()->with('error', $e->getMessage());
        }
    }
}
