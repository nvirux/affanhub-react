<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class WalletController extends Controller
{
    /**
     * Display the customer wallet management & funding page.
     */
    public function index(Request $request): Response
    {
        $user = Auth::user();
        $wallet = $user ? $user->wallet('main') : null;
        $virtualAccount = $user ? $user->virtualAccounts()->where('status', 'active')->first() : null;

        // Recent wallet transactions
        $recentTransactions = $user
            ? Transaction::where('user_id', $user->id)
                ->latest()
                ->take(15)
                ->get()
                ->map(fn ($tx) => [
                    'id' => $tx->id,
                    'reference' => $tx->reference ?? 'TXN-'.$tx->id,
                    'type' => $tx->type ?? 'deposit',
                    'title' => $tx->title ?? ($tx->type === 'deposit' ? 'Wallet Funding' : 'Service Payment'),
                    'description' => $tx->description ?? 'Wallet Transaction',
                    'amount' => (float) ($tx->amount ?? 0),
                    'status' => $tx->status ?? 'successful',
                    'created_at' => $tx->created_at?->diffForHumans() ?? 'Just now',
                    'date' => $tx->created_at?->format('M d, Y h:i A') ?? '',
                ])
            : [];

        return Inertia::render('Storefront/Wallet', [
            'wallet' => [
                'balance' => (float) ($wallet?->balance ?? 0.00),
                'currency' => $wallet?->currency ?? 'NGN',
                'status' => $wallet?->status ?? 'active',
            ],
            'virtual_account' => $virtualAccount ? [
                'id' => $virtualAccount->id,
                'bank_name' => $virtualAccount->bank_name,
                'account_number' => $virtualAccount->account_number,
                'account_name' => $virtualAccount->account_name,
                'status' => $virtualAccount->status,
                'provider' => $virtualAccount->provider ?? 'Automated',
            ] : null,
            'recent_transactions' => $recentTransactions,
        ]);
    }
}
