<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class TransactionController extends Controller
{
    /**
     * Display a paginated listing of customer transactions.
     */
    public function index(Request $request): Response
    {
        $user = Auth::user();
        $tenantId = tenant('id') ?? $user?->store_id;

        $query = Transaction::query()
            ->where('user_id', $user->id)
            ->when($tenantId, fn ($q) => $q->where('store_id', $tenantId));

        if ($request->filled('type') && in_array($request->type, ['airtime', 'data', 'cable', 'electricity'], true)) {
            $query->where('service_type', $request->type);
        }

        if ($request->filled('status') && in_array($request->status, ['successful', 'success', 'pending', 'failed'], true)) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = trim((string) $request->search);
            $query->where(function ($q) use ($search) {
                $q->where('reference', 'like', "%{$search}%")
                    ->orWhere('recipient', 'like', "%{$search}%");
            });
        }

        $transactions = $query->latest()
            ->paginate(15)
            ->withQueryString()
            ->through(fn ($tx) => [
                'id' => $tx->id,
                'reference' => $tx->reference,
                'service_type' => $tx->service_type,
                'recipient' => $tx->recipient,
                'amount' => (float) $tx->amount,
                'status' => in_array($tx->status, ['success', 'successful', 'completed'], true) ? 'successful' : ($tx->status === 'pending' ? 'pending' : 'failed'),
                'created_at' => $tx->created_at?->diffForHumans() ?? 'Just now',
                'date' => $tx->created_at?->format('M d, Y h:i A') ?? '',
            ]);

        return Inertia::render('Storefront/Transactions/Index', [
            'transactions' => $transactions,
            'filters' => [
                'type' => $request->type ?? 'all',
                'status' => $request->status ?? 'all',
                'search' => $request->search ?? '',
            ],
        ]);
    }

    /**
     * Display the digital receipt and details for a specific transaction.
     */
    public function show(string $reference): Response
    {
        $user = Auth::user();
        $tenant = tenant();

        $transaction = Transaction::where('reference', $reference)
            ->where('user_id', $user->id)
            ->firstOrFail();

        $mainWallet = $user->wallet('main');

        return Inertia::render('Storefront/Transactions/Show', [
            'transaction' => [
                'id' => $transaction->id,
                'reference' => $transaction->reference,
                'service_type' => $transaction->service_type,
                'recipient' => $transaction->recipient,
                'amount' => (float) $transaction->amount,
                'discount' => (float) ($transaction->discount ?? 0.00),
                'amount_paid' => (float) ($transaction->amount_paid > 0 ? $transaction->amount_paid : $transaction->amount),
                'status' => in_array($transaction->status, ['success', 'successful', 'completed'], true) ? 'successful' : ($transaction->status === 'pending' ? 'pending' : 'failed'),
                'created_at' => $transaction->created_at?->format('M d, Y · h:i A') ?? '',
                'api_response' => $transaction->api_response,
            ],
            'wallet_balance' => (float) ($mainWallet?->balance ?? 0.00),
            'store_support' => [
                'name' => $tenant?->name ?? 'Store Support',
                'whatsapp' => $tenant?->whatsapp_number ?? null,
            ],
        ]);
    }
}
