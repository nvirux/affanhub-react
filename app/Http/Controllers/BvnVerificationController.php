<?php

namespace App\Http\Controllers;

use App\Models\IdentityVerification;
use App\Models\Service;
use App\Models\Slip;
use App\Models\StoreService;
use App\Models\Transaction;
use App\Services\Identity\IdCoreService;
use App\Services\WalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class BvnVerificationController extends Controller
{
    /**
     * Display the BVN verification & slip search storefront page.
     */
    public function index(Request $request): Response
    {
        $user = $request->user();
        $store = tenant();

        $mainWallet = method_exists($user, 'wallet') ? $user->wallet('main') : null;
        $walletBalance = (float) ($mainWallet?->balance ?? 0.00);

        // Service Availability check
        $service = Service::where('key', 'bvn_verification')->first();
        $hasAccess = true;
        if ($service?->feature_id && $service?->feature) {
            try {
                $hasAccess = (bool) $store->hasFeature($service->feature->slug);
            } catch (\Throwable) {
                $hasAccess = false;
            }
        }
        $storeService = $service && $store ? StoreService::where('store_id', $store->id)->where('service_id', $service->id)->first() : null;
        $isEnabled = $storeService ? (bool) $storeService->is_enabled : $hasAccess;
        $isAvailable = $hasAccess && $isEnabled;

        // Dynamic slips loaded from database for BVN service
        $slips = [];
        if ($service) {
            $slips = $service->slips()
                ->where('is_active', true)
                ->get()
                ->filter(fn ($s) => $store ? $s->isEnabledForStore($store->id) : true)
                ->map(fn ($s) => [
                    'id' => $s->slug,
                    'slug' => $s->slug,
                    'name' => $s->name,
                    'badge' => $s->badge ?? $s->name,
                    'price' => $store ? $s->getStorePrice($store->id) : (float) $s->selling_price,
                    'description' => $s->description ?? '',
                    'features' => $s->features ?? [],
                    'is_popular' => (bool) $s->is_popular,
                    'color' => $s->color ?? 'blue',
                ])
                ->values()
                ->toArray();
        }

        // Recent BVN verification records for the current user
        $recentVerifications = IdentityVerification::where('user_id', $user->id)
            ->where('service_id', $service?->id)
            ->latest()
            ->limit(5)
            ->get()
            ->map(fn ($v) => [
                'id' => $v->id,
                'reference' => $v->reference,
                'recipient' => $v->recipient_name ?? $v->search_value,
                'search_value' => $v->search_value,
                'amount_paid' => (float) $v->fee_charged,
                'status' => $v->status,
                'slip_download_url' => $v->slip_download_url,
                'created_at' => $v->created_at?->format('M d, Y · h:i A') ?? '',
            ]);

        // Fallback to transactions if no IdentityVerification records exist yet
        if ($recentVerifications->isEmpty()) {
            $recentVerifications = Transaction::where('user_id', $user->id)
                ->whereIn('service_type', ['bvn_verification', 'bvn'])
                ->latest()
                ->limit(5)
                ->get()
                ->map(fn ($tx) => [
                    'id' => $tx->id,
                    'reference' => $tx->reference,
                    'recipient' => $tx->recipient,
                    'search_value' => $tx->recipient,
                    'amount_paid' => (float) $tx->amount_paid,
                    'status' => $tx->status,
                    'slip_download_url' => null,
                    'created_at' => $tx->created_at?->format('M d, Y · h:i A') ?? '',
                ]);
        }

        return Inertia::render('Storefront/Identity/Bvn', [
            'is_available' => $isAvailable,
            'slips' => $slips,
            'wallet_balance' => $walletBalance,
            'recent_verifications' => $recentVerifications,
            'store_support' => [
                'name' => $store?->name ?? 'Support',
                'whatsapp' => $store?->whatsapp_chat_phone ?? null,
            ],
        ]);
    }

    /**
     * Process verification request (or preview verification).
     */
    public function verify(Request $request)
    {
        $validated = $request->validate([
            'search_type' => ['required', 'string', 'in:bvn,phone'],
            'search_value' => ['required', 'string', 'regex:/^[0-9]{11}$/'],
            'slip_type' => ['required', 'string', 'in:basic,advance,plastic,bvn-basic,bvn-advance,bvn-plastic'],
            'pin' => ['nullable', 'string', 'digits:4'],
        ], [
            'search_value.regex' => 'The '.($request->search_type === 'phone' ? 'phone number' : 'BVN').' must be exactly 11 numeric digits.',
        ]);

        $store = tenant();
        $service = Service::where('key', 'bvn_verification')->first();
        $hasAccess = true;
        if ($service?->feature_id && $service?->feature) {
            try {
                $hasAccess = (bool) $store->hasFeature($service->feature->slug);
            } catch (\Throwable) {
                $hasAccess = false;
            }
        }
        $storeService = $service && $store ? StoreService::where('store_id', $store->id)->where('service_id', $service->id)->first() : null;
        $isEnabled = $storeService ? (bool) $storeService->is_enabled : $hasAccess;

        if (! $hasAccess || ! $isEnabled) {
            return response()->json([
                'success' => false,
                'message' => 'This service is currently unavailable. Please contact support.',
            ], 403);
        }

        $user = $request->user();

        $pin = $request->input('transaction_pin') ?? $request->input('pin');

        // Check PIN if provided and user has transaction pin set
        if (! empty($pin) && $user->hasTransactionPin()) {
            if (! Hash::check($pin, $user->transaction_pin_hash)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid transaction PIN. Please try again.',
                ], 422);
            }
        }

        // Resolve slip from database dynamically
        $normalizedSlug = match ($validated['slip_type']) {
            'bvn-basic' => 'basic',
            'bvn-advance' => 'advance',
            'bvn-plastic' => 'plastic',
            default => $validated['slip_type'],
        };

        $slip = $service ? Slip::where('service_id', $service->id)
            ->where('slug', $normalizedSlug)
            ->first() : null;

        $retailPrice = $slip
            ? ($store ? $slip->getStorePrice($store->id) : (float) $slip->selling_price)
            : 150.00;
        $merchantCost = $slip ? (float) $slip->selling_price : 100.00;
        $costPrice = $slip ? (float) $slip->cost_price : 60.00;
        $profit = max(0, $retailPrice - $merchantCost);
        $slipName = $slip?->name ?? 'Basic Slip';

        $mainWallet = method_exists($user, 'wallet') ? $user->wallet('main') : null;
        $walletBalance = (float) ($mainWallet?->balance ?? 0.00);

        if (! $mainWallet || $walletBalance < $retailPrice) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient wallet balance (₦'.number_format($walletBalance, 2).'). You need ₦'.number_format($retailPrice, 2).' to generate this slip.',
            ], 422);
        }

        $reference = 'BVN-'.strtoupper(uniqid());
        $walletService = app(WalletService::class);
        $storeMainWallet = $store ? $store->mainWallet() : null;

        // Atomic debit: Customer Wallet (full retail) and Store Main Wallet (wholesale cost)
        DB::beginTransaction();
        try {
            // 1. Lock & Debit Customer Wallet
            $walletService->debit(
                $mainWallet,
                $retailPrice,
                'bvn_verification',
                "BVN Verification ({$slipName}) for {$validated['search_value']}",
                [
                    'search_type' => $validated['search_type'] ?? 'bvn',
                    'search_value' => $validated['search_value'],
                    'slip_slug' => $slip?->slug,
                    'slip_name' => $slipName,
                    'retail_price' => $retailPrice,
                ],
                $reference
            );

            // 2. Lock & Debit Store Main Wallet for Wholesale Cost
            if ($storeMainWallet) {
                $walletService->debit(
                    $storeMainWallet,
                    $merchantCost,
                    'wholesale_cost',
                    "Wholesale BVN Verification: {$slipName} for customer #{$user->id}",
                    [
                        'customer_id' => $user->id,
                        'search_type' => $validated['search_type'] ?? 'bvn',
                        'search_value' => $validated['search_value'],
                        'slip_slug' => $slip?->slug,
                        'retail_price' => $retailPrice,
                        'wholesale_cost' => $merchantCost,
                    ],
                    'WS_'.$reference,
                    true
                );
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('BVN Verification Wallet Debit Error: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to process wallet payment: '.$e->getMessage(),
            ], 422);
        }

        // Call upstream provider IDCore
        $idCoreService = app(IdCoreService::class);
        $idcoreResult = $idCoreService->verifyBvn(
            $validated['search_value'],
            $slip?->slug,
            'advance'
        );

        if (! $idcoreResult['success']) {
            $failReason = $idcoreResult['message'] ?? 'BVN lookup failed.';

            // Instant Auto-Refund Engine
            try {
                $walletService->refund(
                    $mainWallet,
                    $retailPrice,
                    "Auto-Refund: BVN lookup failed ({$failReason})",
                    ['failed_reference' => $reference, 'reason' => $failReason],
                    'REF_'.$reference
                );

                if ($storeMainWallet) {
                    $walletService->credit(
                        $storeMainWallet,
                        $merchantCost,
                        'wholesale_refund',
                        'Auto-Refund Store Wholesale: BVN lookup failed',
                        ['failed_reference' => $reference],
                        'WS_REF_'.$reference
                    );
                }
            } catch (\Throwable $refundErr) {
                Log::error("Failed to auto-refund for {$reference}: ".$refundErr->getMessage());
            }

            return response()->json([
                'success' => false,
                'message' => $idcoreResult['message'] ?? 'BVN lookup failed. Your wallet was not charged.',
            ], 422);
        }

        // Profit Allocation: Sweep & credit profit to Store Profit Wallet
        if ($store && $storeMainWallet && $profit > 0) {
            try {
                // 1. Debit profit sweep from Store Main Wallet
                $walletService->debit(
                    $storeMainWallet,
                    $profit,
                    'profit_sweep',
                    "Profit Allocation: BVN Verification ({$slipName})",
                    [
                        'customer_id' => $user->id,
                        'retail_price' => $retailPrice,
                        'wholesale_cost' => $merchantCost,
                        'profit_margin' => $profit,
                    ],
                    'SWP_'.$reference,
                    true
                );

                // 2. Credit Store Profit Wallet (Withdrawable)
                $storeProfitWallet = $store->profitWallet();
                $walletService->credit(
                    $storeProfitWallet,
                    $profit,
                    'earned_profit',
                    "Earned Profit: BVN Verification ({$slipName}) for customer #{$user->id}",
                    [
                        'customer_id' => $user->id,
                        'retail_price' => $retailPrice,
                        'wholesale_cost' => $merchantCost,
                        'profit_margin' => $profit,
                    ],
                    'PRF_'.$reference
                );
            } catch (\Throwable $profitErr) {
                Log::error("Failed to sweep profit for {$reference}: ".$profitErr->getMessage());
            }
        }
        $providerRef = $idcoreResult['reference'] ?? null;
        $idData = $idcoreResult['data'] ?? [];
        $slipMeta = $idcoreResult['slip'] ?? null;

        // Create transaction record
        $transaction = Transaction::create([
            'store_id' => $store?->id,
            'user_id' => $user->id,
            'service_id' => $service?->id,
            'reference' => $reference,
            'service_type' => 'bvn_verification',
            'amount' => $retailPrice,
            'amount_paid' => $retailPrice,
            'cost_price' => $costPrice,
            'vendor_cost' => $merchantCost,
            'profit' => $profit,
            'platform_profit' => max(0, $merchantCost - $costPrice),
            'recipient' => $validated['search_value'],
            'status' => 'successful',
            'api_response' => [
                'provider' => 'idcore',
                'provider_reference' => $providerRef,
                'service' => 'bvn_verification',
                'slip_type' => $validated['slip_type'],
                'slip_name' => $slip?->name ?? 'Basic Slip',
            ],
        ]);

        $fullName = $idData['full_name'] ?? trim(($idData['first_name'] ?? '').' '.($idData['middle_name'] ?? '').' '.($idData['last_name'] ?? ''));
        $downloadUrl = route('identity.verifications.download-slip', ['reference' => $reference]);

        $verifiedRecord = [
            'bvn' => $idData['bvn'] ?? ($validated['search_type'] === 'bvn' ? $validated['search_value'] : 'N/A'),
            'phone' => ($validated['search_type'] === 'phone') ? $validated['search_value'] : ($idData['phone'] ?? ($user->phone ?? '')),
            'firstname' => $idData['first_name'] ?? '',
            'middlename' => $idData['middle_name'] ?? '',
            'surname' => $idData['last_name'] ?? '',
            'full_name' => $fullName,
            'gender' => $idData['gender'] ?? 'N/A',
            'dob' => $idData['date_of_birth'] ?? '',
            'enrollment_bank' => $idData['enrollment_bank'] ?? 'N/A',
            'branch' => $idData['enrollment_branch'] ?? $idData['branch'] ?? 'N/A',
            'tracking_id' => $idData['tracking_id'] ?? null,
            'state_of_origin' => $idData['state'] ?? $idData['state_of_origin'] ?? '',
            'lga' => $idData['lga'] ?? '',
            'address' => $idData['address'] ?? '',
            'photo' => $idData['photo'] ?? null,
            'slip_type' => $validated['slip_type'],
            'slip_name' => $slip?->name ?? ($slipMeta['name'] ?? 'Basic BVN Slip'),
            'slip_download_url' => $downloadUrl,
            'verified_at' => now()->format('M d, Y · h:i A'),
            'reference' => $reference,
            'provider_reference' => $providerRef,
            'fee_charged' => $retailPrice,
        ];

        // Store identity verification record
        IdentityVerification::create([
            'store_id' => $store?->id,
            'user_id' => $user->id,
            'service_id' => $service?->id,
            'slip_id' => $slip?->id,
            'transaction_id' => $transaction->id,
            'search_type' => $validated['search_type'],
            'search_value' => $validated['search_value'],
            'reference' => $reference,
            'provider_reference' => $providerRef,
            'status' => 'successful',
            'recipient_name' => $fullName ?: $validated['search_value'],
            'tracking_id' => $verifiedRecord['tracking_id'],
            'photo_url' => $verifiedRecord['photo'],
            'slip_download_url' => $downloadUrl,
            'data_payload' => $verifiedRecord,
            'fee_charged' => $retailPrice,
            'cost_price' => $costPrice,
            'merchant_cost' => $merchantCost,
            'profit' => $profit,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'BVN Record Verified Successfully!',
            'redirect_url' => route('identity.verifications.show', ['reference' => $reference]),
            'data' => $verifiedRecord,
        ]);
    }

    /**
     * Download or stream official PDF verification slip from IDCore.
     */
    public function downloadSlip(Request $request, string $reference, IdCoreService $idCoreService)
    {
        $verification = IdentityVerification::where('reference', $reference)->firstOrFail();
        $user = $request->user();
        $store = tenant();

        $isStoreOwner = $store && $store->owner_id === $user->id;
        if ($verification->user_id !== $user->id && ! $isStoreOwner) {
            abort(403, 'Unauthorized access to this slip.');
        }

        if (empty($verification->provider_reference)) {
            abort(404, 'No slip download available for this verification record.');
        }

        $result = $idCoreService->downloadSlip($verification->provider_reference);

        if (! $result['success']) {
            return back()->with('error', $result['message']);
        }

        return response($result['pdf_content'])
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="BVN_Slip_'.$reference.'.pdf"');
    }
}
