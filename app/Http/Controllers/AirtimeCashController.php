<?php

namespace App\Http\Controllers;

use App\Models\AirtimeCashRequest;
use App\Models\Network;
use App\Models\Service;
use App\Models\Store;
use App\Models\StoreAirtimeCashSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class AirtimeCashController extends Controller
{
    /**
     * Display the Airtime to Cash page.
     */
    public function index(): Response
    {
        $user = Auth::user();
        $store = (function_exists('tenant') && tenant())
            ? tenant()
            : ((method_exists($user, 'store') ? $user?->store : null)
                ?? ($user ? Store::where('owner_id', $user->id)->first() : null)
                ?? Store::first());

        $service = Service::where('key', 'airtime_cash')->first();
        $isAccessible = $service ? $service->isAccessibleByStore($store) : true;

        $networks = Network::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $storeId = $store?->id;
        $storeSettings = $storeId
            ? StoreAirtimeCashSetting::where('store_id', $storeId)->get()->keyBy('network_id')
            : collect();

        $formattedNetworks = $networks->map(function ($network) use ($storeSettings) {
            $setting = $storeSettings->get($network->id);

            $rate = $setting && $setting->exchange_rate !== null ? (float) $setting->exchange_rate : 80.00;
            $minAmount = $setting && $setting->min_amount !== null ? (float) $setting->min_amount : 500.00;
            $maxAmount = $setting && $setting->max_amount !== null ? (float) $setting->max_amount : 50000.00;
            $receivingPhone = $setting?->receiving_phone;
            $isEnabled = $setting ? (bool) $setting->is_enabled : true;

            // Get USSD transfer guide
            $instructions = $setting
                ? $setting->getDefaultUssdInstructions()
                : (new StoreAirtimeCashSetting(['network_id' => $network->id, 'receiving_phone' => $receivingPhone]))->getDefaultUssdInstructions();

            return [
                'id' => $network->id,
                'name' => $network->name,
                'slug' => strtolower($network->slug),
                'rate' => $rate,
                'min_amount' => $minAmount,
                'max_amount' => $maxAmount,
                'receiving_phone' => $receivingPhone,
                'ussd_instructions' => $instructions,
                'is_enabled' => $isEnabled && ! empty($receivingPhone),
            ];
        })->values();

        // Recent requests for the authenticated customer
        $recentRequests = $user
            ? AirtimeCashRequest::where('user_id', $user->id)
                ->where('store_id', $storeId)
                ->with('network')
                ->latest()
                ->take(10)
                ->get()
                ->map(fn ($req) => [
                    'id' => $req->id,
                    'reference' => $req->reference,
                    'network' => $req->network?->name,
                    'network_slug' => strtolower($req->network?->slug ?? ''),
                    'airtime_amount' => (float) $req->airtime_amount,
                    'cash_amount' => (float) $req->cash_amount,
                    'exchange_rate' => (float) $req->exchange_rate,
                    'payout_method' => $req->payout_method,
                    'status' => $req->status,
                    'rejection_reason' => $req->rejection_reason,
                    'proof_image_url' => $req->proof_image_url,
                    'created_at' => $req->created_at->format('M d, Y h:i A'),
                ])
            : [];

        $mainWallet = $user?->wallet('main');

        return Inertia::render('Storefront/Vtu/AirtimeToCash', [
            'networks' => $formattedNetworks,
            'recent_requests' => $recentRequests,
            'wallet_balance' => (float) ($mainWallet?->balance ?? 0.00),
            'user' => [
                'name' => $user?->name,
                'phone' => $user?->phone,
            ],
            'is_accessible' => $isAccessible,
        ]);
    }

    /**
     * Submit an Airtime to Cash conversion request.
     */
    public function submit(Request $request): mixed
    {
        $user = Auth::user();

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'Please sign in to submit an airtime to cash request.',
            ], 401);
        }

        $validated = $request->validate([
            'network_id' => 'required|exists:networks,id',
            'airtime_amount' => 'required|numeric|min:50',
            'sender_phone' => 'required|string|min:10|max:15',
            'payout_method' => 'required|in:wallet,bank',
            'bank_name' => 'required_if:payout_method,bank|nullable|string|max:100',
            'account_number' => 'required_if:payout_method,bank|nullable|string|min:10|max:10',
            'account_name' => 'required_if:payout_method,bank|nullable|string|max:100',
            'proof_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $store = (function_exists('tenant') && tenant())
            ? tenant()
            : ((method_exists($user, 'store') ? $user?->store : null)
                ?? ($user ? Store::where('owner_id', $user->id)->first() : null)
                ?? Store::first());

        $service = Service::where('key', 'airtime_cash')->first();
        if ($service && ! $service->isAccessibleByStore($store)) {
            return back()->withErrors([
                'network_id' => 'Airtime to Cash is currently unavailable. Please check back later or contact customer support.',
            ]);
        }

        $setting = StoreAirtimeCashSetting::where('store_id', $store?->id)
            ->where('network_id', $validated['network_id'])
            ->first();

        if (! $setting || ! $setting->is_enabled || empty($setting->receiving_phone)) {
            return back()->withErrors([
                'network_id' => 'Airtime to cash is currently unavailable for this network.',
            ]);
        }

        $amount = (float) $validated['airtime_amount'];
        $min = (float) ($setting->min_amount ?? 500);
        $max = (float) ($setting->max_amount ?? 50000);

        if ($amount < $min || $amount > $max) {
            return back()->withErrors([
                'airtime_amount' => 'Airtime amount must be between ₦'.number_format($min).' and ₦'.number_format($max).'.',
            ]);
        }

        $rate = (float) $setting->exchange_rate;
        $cashAmount = round(($amount * $rate) / 100, 2);

        $proofPath = null;
        if ($request->hasFile('proof_image')) {
            $proofPath = $request->file('proof_image')->store('proofs/airtime-cash', 'central_public');
        }

        $conversionRequest = AirtimeCashRequest::create([
            'reference' => AirtimeCashRequest::generateReference(),
            'store_id' => $store->id,
            'user_id' => $user->id,
            'network_id' => $validated['network_id'],
            'airtime_amount' => $amount,
            'exchange_rate' => $rate,
            'cash_amount' => $cashAmount,
            'sender_phone' => $validated['sender_phone'],
            'receiving_phone' => $setting->receiving_phone,
            'payout_method' => $validated['payout_method'],
            'bank_name' => $validated['payout_method'] === 'bank' ? $validated['bank_name'] : null,
            'account_number' => $validated['payout_method'] === 'bank' ? $validated['account_number'] : null,
            'account_name' => $validated['payout_method'] === 'bank' ? $validated['account_name'] : null,
            'proof_image' => $proofPath,
            'status' => 'pending',
        ]);

        return redirect()->route('vtu.airtime-to-cash')->with('success', 'Your airtime conversion request has been submitted successfully! We are reviewing the airtime transfer and will credit your account shortly.');
    }
}
