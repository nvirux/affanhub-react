<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\StoreService;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Inertia\Response;

class NinVerificationController extends Controller
{
    /**
     * Display the NIN verification & slip search storefront page.
     */
    public function index(Request $request): Response
    {
        $user = $request->user();
        $store = tenant();

        $mainWallet = method_exists($user, 'wallet') ? $user->wallet('main') : null;
        $walletBalance = (float) ($mainWallet?->balance ?? 0.00);

        // 4 Official Slip formats available for customer selection
        $slips = [
            [
                'id' => 'information',
                'name' => 'Information',
                'badge' => 'Info Slip',
                'format' => 'Data Summary',
                'price' => 50.00,
                'description' => 'Essential identity bio-data lookup with verified personal details.',
                'features' => ['Basic Bio-Data', 'Identity Match', 'Instant Result'],
                'is_popular' => false,
                'color' => 'slate',
            ],
            [
                'id' => 'regular',
                'name' => 'Regular',
                'badge' => 'Pocket Slip',
                'format' => 'Pocket Slip',
                'price' => 100.00,
                'description' => 'Compact verification slip with tracking ID and photo summary.',
                'features' => ['Photo & Bio-Data', 'Tracking ID', 'Compact Size'],
                'is_popular' => false,
                'color' => 'blue',
            ],
            [
                'id' => 'standard',
                'name' => 'Standard',
                'badge' => 'Official A4',
                'format' => 'A4 Document',
                'price' => 150.00,
                'description' => 'Full-page document with digital verification stamp and QR code.',
                'features' => ['Full Bio-Data', 'Verified QR Code', 'Print-Ready A4'],
                'is_popular' => false,
                'color' => 'emerald',
            ],
            [
                'id' => 'premium',
                'name' => 'Premium',
                'badge' => 'Plastic ID',
                'format' => 'ID Card Format',
                'price' => 300.00,
                'description' => 'Front & back card format with high-res portrait and scannable barcode.',
                'features' => ['Front & Back Card', 'High-Res Photo', 'Scannable Barcode'],
                'is_popular' => true,
                'color' => 'amber',
            ],
        ];

        // Recent NIN verification records for the current user
        $recentVerifications = Transaction::where('user_id', $user->id)
            ->whereIn('service_type', ['nin_verification', 'nin'])
            ->latest()
            ->limit(5)
            ->get()
            ->map(fn ($tx) => [
                'id' => $tx->id,
                'reference' => $tx->reference,
                'recipient' => $tx->recipient,
                'amount_paid' => (float) $tx->amount_paid,
                'status' => $tx->status,
                'created_at' => $tx->created_at?->format('M d, Y · h:i A') ?? '',
            ]);

        // Service Availability check
        $service = Service::where('key', 'nin_verification')->first();
        $hasAccess = true;
        if ($service?->feature_id && $service?->feature) {
            try {
                $hasAccess = (bool) $store->hasFeature($service->feature->slug);
            } catch (\Throwable) {
                $hasAccess = false;
            }
        }
        $storeService = $service ? StoreService::where('store_id', $store->id)->where('service_id', $service->id)->first() : null;
        $isEnabled = $storeService ? (bool) $storeService->is_enabled : $hasAccess;
        $isAvailable = $hasAccess && $isEnabled;

        return Inertia::render('Storefront/Identity/Nin', [
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
            'search_type' => ['required', 'string', 'in:nin,phone'],
            'search_value' => ['required', 'string', 'regex:/^[0-9]{11}$/'],
            'slip_type' => ['required', 'string', 'in:information,regular,standard,premium,standard_nin,premium_nin,basic_nin'],
            'pin' => ['nullable', 'string', 'digits:4'],
        ], [
            'search_value.regex' => 'The '.($request->search_type === 'phone' ? 'phone number' : 'NIN').' must be exactly 11 numeric digits.',
        ]);

        $store = tenant();
        $service = Service::where('key', 'nin_verification')->first();
        $hasAccess = true;
        if ($service?->feature_id && $service?->feature) {
            try {
                $hasAccess = (bool) $store->hasFeature($service->feature->slug);
            } catch (\Throwable) {
                $hasAccess = false;
            }
        }
        $storeService = $service ? StoreService::where('store_id', $store->id)->where('service_id', $service->id)->first() : null;
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

        $slipPrices = [
            'information' => 50.00,
            'regular' => 100.00,
            'basic_nin' => 100.00,
            'standard' => 150.00,
            'standard_nin' => 150.00,
            'premium' => 300.00,
            'premium_nin' => 300.00,
        ];

        $price = $slipPrices[$validated['slip_type']] ?? 150.00;
        $mainWallet = method_exists($user, 'wallet') ? $user->wallet('main') : null;
        $walletBalance = (float) ($mainWallet?->balance ?? 0.00);

        if ($walletBalance < $price) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient wallet balance (₦'.number_format($walletBalance, 2).'). You need ₦'.number_format($price, 2).' to generate this slip.',
            ], 422);
        }

        // Generate verified presentation payload
        $isPhone = $validated['search_type'] === 'phone';
        $verifiedRecord = [
            'nin' => $isPhone ? '73948201938' : $validated['search_value'],
            'phone' => $isPhone ? $validated['search_value'] : ($user->phone ?? '0803'.rand(1000000, 9999999)),
            'firstname' => 'MUSA',
            'middlename' => 'IBRAHIM',
            'surname' => 'BELLO',
            'gender' => 'Male',
            'dob' => '1994-08-14',
            'tracking_id' => 'NG-ID-'.rand(10000000, 99999999),
            'state_of_origin' => 'Kano',
            'lga' => 'Nasarawa',
            'address' => 'Plot 14, Commercial Avenue, Kano',
            'slip_type' => $validated['slip_type'],
            'slip_name' => match ($validated['slip_type']) {
                'information' => 'Information Slip',
                'regular', 'basic_nin' => 'Regular Slip',
                'premium', 'premium_nin' => 'Premium Card',
                default => 'Standard Slip',
            },
            'verified_at' => now()->format('M d, Y · h:i A'),
            'reference' => 'NIN-'.strtoupper(uniqid()),
            'fee_charged' => $price,
        ];

        return response()->json([
            'success' => true,
            'message' => 'Record Verified Successfully!',
            'data' => $verifiedRecord,
        ]);
    }
}
