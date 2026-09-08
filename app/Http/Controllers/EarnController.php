<?php

namespace App\Http\Controllers;

use App\Models\Referral;
use App\Models\Store;
use App\Models\StoreReferralSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class EarnController extends Controller
{
    /**
     * Display the Customer Earning & Referral Hub.
     */
    public function index(Request $request): Response
    {
        $user = Auth::user();
        $store = (function_exists('tenant') && tenant())
            ? tenant()
            : ($user?->store_id ? Store::find($user->store_id) : (Store::where('owner_id', $user?->id)->first() ?? Store::first()));

        $hasFeature = $store ? (bool) $store->hasFeature('referral_system') : false;

        $referralCode = $user ? $user->ensureReferralCode() : '';

        $settings = $store
            ? StoreReferralSetting::firstOrCreate(
                ['store_id' => $store->id],
                [
                    'is_enabled' => true,
                    'reward_amount' => 50.00,
                    'condition_type' => 'first_deposit',
                    'min_deposit_amount' => 500.00,
                ]
            )
            : null;

        $rewardAmount = (float) ($settings->reward_amount ?? 50.00);
        $conditionType = $settings->condition_type ?? 'first_deposit';
        $minDepositAmount = (float) ($settings->min_deposit_amount ?? 500.00);
        $isProgramEnabled = $settings ? (bool) $settings->is_enabled : true;

        $totalEarned = 0.00;
        $totalReferrals = 0;
        $completedReferrals = 0;
        $pendingReferrals = 0;
        $referralList = [];

        if ($user) {
            $totalEarned = (float) Referral::where('referrer_id', $user->id)
                ->where('status', 'completed')
                ->sum('reward_amount');

            $totalReferrals = Referral::where('referrer_id', $user->id)->count();

            $completedReferrals = Referral::where('referrer_id', $user->id)
                ->where('status', 'completed')
                ->count();

            $pendingReferrals = Referral::where('referrer_id', $user->id)
                ->where('status', 'pending')
                ->count();

            $referralList = Referral::with('referred:id,name,email,created_at')
                ->where('referrer_id', $user->id)
                ->latest()
                ->take(30)
                ->get()
                ->map(fn ($r) => [
                    'id' => $r->id,
                    'name' => $r->referred->name ?? 'User',
                    'status' => $r->status,
                    'reward_amount' => (float) ($r->reward_amount ?? $rewardAmount),
                    'date' => $r->created_at?->diffForHumans() ?? '',
                    'completed_at' => $r->completed_at?->toFormattedDateString(),
                ]);
        }

        $referralLink = url('/register?ref='.$referralCode);

        return Inertia::render('Storefront/Earn/Index', [
            'has_feature' => $hasFeature,
            'is_enabled' => $isProgramEnabled && $hasFeature,
            'referral_code' => $referralCode,
            'referral_link' => $referralLink,
            'reward_amount' => $rewardAmount,
            'condition_type' => $conditionType,
            'min_deposit_amount' => $minDepositAmount,
            'stats' => [
                'total_earned' => $totalEarned,
                'total_referrals' => $totalReferrals,
                'completed_referrals' => $completedReferrals,
                'pending_referrals' => $pendingReferrals,
            ],
            'referrals' => $referralList,
            'wallet_balance' => (float) ($user?->wallet('main')?->balance ?? 0.00),
        ]);
    }
}
