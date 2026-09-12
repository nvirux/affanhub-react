<?php

declare(strict_types=1);

namespace App\Http\Controllers;

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

        // Recent wallet transactions from ledger
        $recentTransactions = $wallet
            ? $wallet->transactions()
                ->latest()
                ->take(20)
                ->get()
                ->map(fn ($tx) => [
                    'id' => $tx->id,
                    'reference' => $tx->reference ?? 'WT-'.$tx->id,
                    'type' => $tx->type === 'credit' ? 'deposit' : 'debit',
                    'title' => $tx->type === 'credit' ? ($tx->category === 'manual_merchant_credit' ? 'Merchant Credit' : 'Wallet Deposit') : 'Wallet Payment',
                    'description' => $tx->description ?? ($tx->type === 'credit' ? 'Wallet credited' : 'Wallet debited'),
                    'amount' => (float) ($tx->amount ?? 0),
                    'status' => in_array($tx->status, ['success', 'successful'], true) ? 'successful' : ($tx->status ?? 'pending'),
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
