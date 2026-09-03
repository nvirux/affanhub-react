<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use App\Models\VirtualAccount;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PayMintWebhookController extends Controller
{
    /**
     * Handle incoming PayMint webhook deposit notifications.
     */
    public function handle(Request $request)
    {
        $payload = $request->getContent();
        $signature = $request->header('X-Paymint-Signature') ?? $request->header('x-paymint-signature');

        Log::info('PayMint Webhook Received:', [
            'headers' => $request->headers->all(),
            'payload' => $request->all(),
        ]);

        // Verify signature via PayMint SDK
        try {
            if (class_exists(\PayMint\Laravel\Facades\PayMint::class)) {
                $isValid = \PayMint\Laravel\Facades\PayMint::webhooks()->verifySignature($payload, $signature);
                if (!$isValid) {
                    Log::warning('PayMint Webhook Signature Invalid!');
                    return response()->json(['error' => 'Invalid signature detected.'], 401);
                }
            }
        } catch (\Throwable $e) {
            Log::error('PayMint Signature Verification Exception: ' . $e->getMessage());
        }

        $event = $request->input('event');
        $data = $request->input('data', $request->all());

        if ($event === 'payment.success' || ($request->input('status') === 'success' && isset($data['amount']))) {
            $accountNumber = $data['account_number'] ?? $data['virtual_account']['account_number'] ?? null;
            $accountRef = $data['account_id'] ?? $data['virtual_account_id'] ?? null;
            $amount = (float) ($data['amount'] ?? $data['amount_paid'] ?? 0);
            $txReference = $data['reference'] ?? $data['transaction_reference'] ?? ('pm_' . time() . '_' . rand(1000, 9999));

            if ($amount <= 0) {
                return response()->json(['status' => 'ignored', 'message' => 'Invalid deposit amount.'], 400);
            }

            // Find matching VirtualAccount in database
            $virtualAccount = VirtualAccount::query()
                ->when($accountNumber, fn($q) => $q->where('account_number', $accountNumber))
                ->when(!$accountNumber && $accountRef, fn($q) => $q->orWhere('reference', $accountRef))
                ->first();

            if (!$virtualAccount) {
                Log::warning('PayMint Webhook: No VirtualAccount found matching payload.', ['data' => $data]);
                return response()->json(['status' => 'not_found', 'message' => 'Virtual account not found.'], 404);
            }

            // Idempotency check: verify if transaction was already processed
            $alreadyProcessed = WalletTransaction::where('reference', $txReference)->exists();
            if ($alreadyProcessed) {
                Log::info('PayMint Webhook: Deposit reference already processed.', ['reference' => $txReference]);
                return response()->json(['status' => 'duplicate', 'message' => 'Already processed.']);
            }

            // Retrieve holder & wallet
            $holder = $virtualAccount->holder;

            if (!$holder) {
                Log::error('PayMint Webhook: VirtualAccount has no associated holder.', ['va_id' => $virtualAccount->id]);
                return response()->json(['status' => 'error', 'message' => 'Account holder missing.'], 500);
            }

            // Ensure holder has a wallet
            $wallet = $holder->wallet;

            if (!$wallet) {
                $wallet = $holder->wallet()->create(['balance' => 0.00]);
            }

            // Credit customer or merchant wallet atomically with row locking
            $description = "Bank Deposit via {$virtualAccount->bank_name} ({$virtualAccount->account_number})";
            $walletTx = $wallet->deposit($amount, $description, $txReference, [
                'provider' => 'paymint',
                'virtual_account_id' => $virtualAccount->id,
                'raw_event' => $data,
            ]);

            Log::info("PayMint Webhook: Wallet credited successfully! Holder ID: {$holder->id}, Amount: ₦{$amount}, New Balance: ₦{$wallet->balance}");

            return response()->json(['status' => 'success', 'transaction_id' => $walletTx->id]);
        }

        return response()->json(['status' => 'ignored', 'event' => $event]);
    }
}
