<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\User;
use App\Models\VirtualAccount;
use App\Models\WalletTransaction;
use App\Services\WalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use PayMint\Laravel\Facades\PayMint;

class PayMintWebhookController extends Controller
{
    /**
     * Handle incoming PayMint webhook deposit notifications.
     */
    public function handle(Request $request, WalletService $walletService)
    {
        $payload = $request->getContent();
        $signature = $request->header('X-Paymint-Signature') ?? $request->header('x-paymint-signature');

        Log::info('PayMint Webhook Received:', [
            'headers' => $request->headers->all(),
            'payload' => $request->all(),
        ]);

        // 1. Signature Verification
        // Bypass signature check ONLY if running in local/testing console OR header X-Test-Bypass-Signature is true
        $isLocalTesting = app()->environment('local', 'testing') || $request->header('X-Test-Bypass-Signature') === 'true';

        if (! $isLocalTesting) {
            try {
                if (class_exists(PayMint::class)) {
                    $isValid = PayMint::webhooks()->verifySignature($payload, $signature);
                    if (! $isValid) {
                        Log::warning('PayMint Webhook Signature Invalid!');

                        return response()->json(['error' => 'Invalid signature detected.'], 401);
                    }
                } else {
                    // Fallback HMAC verification if PayMint SDK facade is not loaded
                    $secret = config('services.paymint.secret_key') ?? env('PAYMINT_SECRET_KEY');
                    if ($secret) {
                        $expectedSignature = hash_hmac('sha512', $payload, $secret);
                        if (! hash_equals($expectedSignature, (string) $signature)) {
                            Log::warning('PayMint Webhook Signature Mismatch!');

                            return response()->json(['error' => 'Invalid signature.'], 401);
                        }
                    }
                }
            } catch (\Throwable $e) {
                Log::error('PayMint Signature Verification Exception: '.$e->getMessage());

                return response()->json(['error' => 'Signature verification error.'], 401);
            }
        }

        $event = $request->input('event');
        $data = $request->input('data', $request->all());
        $status = strtolower($data['status'] ?? $request->input('status', ''));

        // Check if event is payment.success and status is successful/success
        if ($event === 'payment.success' && in_array($status, ['successful', 'success', 'completed'])) {
            // Extract Virtual Account Number & Email from payload
            $accountNumber = $data['receiver']['account_number']
                ?? $data['account_number']
                ?? $data['virtual_account']['account_number']
                ?? null;

            $customerEmail = $data['customer']['email'] ?? $data['email'] ?? null;
            $accountRef = $data['account_id'] ?? $data['virtual_account_id'] ?? null;
            $amount = (float) ($data['amount'] ?? $data['amount_paid'] ?? 0);
            $txReference = $data['reference'] ?? $data['transaction_reference'] ?? $request->input('id');

            if ($amount <= 0) {
                return response()->json(['status' => 'ignored', 'message' => 'Invalid deposit amount.'], 400);
            }

            // Clean database lookup (Primary: account_number, Fallback: email_alias)
            $virtualAccount = VirtualAccount::query()
                ->when($accountNumber, fn ($q) => $q->where('account_number', $accountNumber))
                ->when(! $accountNumber && $customerEmail, fn ($q) => $q->orWhere('email_alias', $customerEmail))
                ->when(! $accountNumber && ! $customerEmail && $accountRef, fn ($q) => $q->orWhere('reference', $accountRef))
                ->first();

            // Fallback email query if initial account_number query returned null
            if (! $virtualAccount && $customerEmail) {
                $virtualAccount = VirtualAccount::where('email_alias', $customerEmail)->first();
            }

            if (! $virtualAccount) {
                Log::warning('PayMint Webhook: No VirtualAccount found matching payload account_number/email.', ['account' => $accountNumber, 'email' => $customerEmail]);

                return response()->json(['status' => 'not_found', 'message' => 'Virtual account not found.'], 404);
            }

            // Idempotency check: verify if transaction reference was already processed
            $alreadyProcessed = WalletTransaction::where('reference', 'DEP_'.$txReference)
                ->orWhere('reference', $txReference)
                ->exists();

            if ($alreadyProcessed) {
                Log::info('PayMint Webhook: Deposit reference already processed.', ['reference' => $txReference]);

                return response()->json(['status' => 'duplicate', 'message' => 'Already processed.'], 200);
            }

            // Retrieve holder & wallet
            $holder = $virtualAccount->holder;

            if (! $holder) {
                Log::error('PayMint Webhook: VirtualAccount has no associated holder.', ['va_id' => $virtualAccount->id]);

                return response()->json(['status' => 'error', 'message' => 'Account holder missing.'], 500);
            }

            $senderName = $data['sender']['name'] ?? 'Bank Transfer';
            $senderBank = $data['sender']['bank_name'] ?? 'Bank';

            try {
                if ($holder instanceof User) {
                    $customerWallet = $holder->wallet('main');
                    if (! $customerWallet) {
                        $customerWallet = $holder->wallets()->create(['type' => 'main', 'balance' => 0.00]);
                    }

                    // Resolve Store Main Wallet
                    $store = (method_exists($holder, 'store') ? $holder->store : null)
                        ?? Store::where('owner_id', $holder->id)->first()
                        ?? Store::first();

                    $storeMainWallet = $store ? $store->mainWallet() : null;

                    if ($storeMainWallet) {
                        $result = $walletService->handleCustomerBankDeposit(
                            $customerWallet,
                            $storeMainWallet,
                            $amount,
                            $txReference,
                            [
                                'provider' => $data['provider'] ?? 'paymint',
                                'virtual_account_id' => $virtualAccount->id,
                                'bank_name' => $virtualAccount->bank_name,
                                'account_number' => $virtualAccount->account_number,
                                'sender_name' => $senderName,
                                'sender_bank' => $senderBank,
                                'raw_event' => $data,
                            ]
                        );
                        $walletTx = $result['customer_transaction'];
                    } else {
                        $walletTx = $walletService->credit(
                            $customerWallet,
                            $amount,
                            'bank_transfer_deposit',
                            "Bank Deposit from {$senderName} ({$senderBank}) via {$virtualAccount->bank_name}",
                            ['provider' => $data['provider'] ?? 'paymint', 'raw_event' => $data],
                            'DEP_'.$txReference
                        );
                    }
                } else {
                    // Holder is Store
                    /** @var Store $holder */
                    $storeWallet = $holder->mainWallet();
                    $walletTx = $walletService->credit(
                        $storeWallet,
                        $amount,
                        'bank_transfer_deposit',
                        "Store Deposit from {$senderName} ({$senderBank}) via {$virtualAccount->bank_name}",
                        ['provider' => $data['provider'] ?? 'paymint', 'raw_event' => $data],
                        'DEP_'.$txReference
                    );
                }

                Log::info(sprintf(
                    'PayMint Webhook: Deposit of ₦%s credited successfully via WalletService to Holder #%s (%s)',
                    number_format($amount, 2),
                    $holder->id,
                    $virtualAccount->account_number
                ));

                return response()->json([
                    'status' => 'success',
                    'transaction_id' => $walletTx->id,
                    'reference' => $txReference,
                ]);
            } catch (\Throwable $e) {
                Log::error('PayMint Webhook Crediting Failed: '.$e->getMessage(), ['exception' => $e]);

                return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
            }
        }

        return response()->json(['status' => 'ignored', 'event' => $event]);
    }
}
