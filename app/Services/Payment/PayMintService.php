<?php

namespace App\Services\Payment;

use App\Models\User;
use App\Models\VirtualAccount;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use PayMint\Laravel\Facades\PayMint;

class PayMintService
{
    /**
     * Create a Virtual Account via PayMint SDK for any polymorphic holder model.
     *
     * @param Model $holder (e.g. User customer or Merchant Store/Owner)
     * @param string $kycType ('nin' or 'bvn')
     * @param string $kycNumber (11-digit number)
     * @param string|null $phone (11-digit phone number, falls back to $holder->phone)
     * @param string|null $customAccountName (Optional display name)
     * @param string $preferredBank ('palmpay')
     * @return VirtualAccount
     */
    public function createVirtualAccount(
        Model $holder,
        string $kycType,
        string $kycNumber,
        ?string $phone = null,
        ?string $customAccountName = null,
        string $preferredBank = 'palmpay'
    ): VirtualAccount {
        if (! in_array($kycType, ['nin', 'bvn'])) {
            throw new \InvalidArgumentException('KYC type must be either nin or bvn.');
        }

        // Validate and normalize phone number
        $phoneNumber = $phone ?? $holder->phone ?? null;
        $normalizedPhone = $this->normalizePhoneNumber($phoneNumber);

        // Actual Customer Real Name
        $name = trim($holder->name ?? $holder->store_name ?? 'Customer');

        // Account Display Name formatting logic
        if ($customAccountName) {
            $accountName = trim($customAccountName);
        } elseif ($holder instanceof User) {
            $storeName = tenant('name') ?: 'AffanHub';
            $accountName = trim($storeName . ' - ' . $name);
        } else {
            // Merchant / Store Owner
            $accountName = trim('AffanHub - ' . ($holder->name ?? 'Merchant Store'));
        }

        // Unique, traceable email alias
        $emailAlias = $this->customerEmailAlias($holder);

        // Prepare PayMint payload
        $payload = [
            'name' => $name,
            'account_name' => $accountName,
            'email' => $emailAlias,
            'phone' => $normalizedPhone,
            $kycType => $kycNumber,
            'preferred_bank' => $preferredBank,
        ];

        Log::info('Initiating PayMint Virtual Account Payload:', ['payload' => $payload]);

        try {
            // Call PayMint SDK
            $response = PayMint::virtualAccounts()->create($payload);

            // Format response array if object returned
            $resData = is_array($response) ? $response : json_decode(json_encode($response), true);

            Log::info('PayMint Virtual Account API Response:', ['response' => $resData]);

            // Check response status
            if (($resData['status'] ?? '') !== 'success' && ! isset($resData['data'])) {
                $errorMsg = $resData['message'] ?? 'Failed to generate virtual account with PayMint.';
                throw new \Exception($errorMsg);
            }

            $accountData = $resData['data']['accounts'][0] ?? null;

            if (! $accountData) {
                throw new \Exception('No virtual account details returned from PayMint.');
            }

            // Save raw unmasked NIN or BVN on holder model
            if ($holder instanceof User) {
                $kycField = strtolower($kycType);
                $holder->$kycField = $kycNumber;
                $holder->save();
            }

            // Reference identifier
            $reference = $accountData['id'] ?? ('va_' . Str::random(12));

            // Save or update in virtual_accounts table polymorphically
            return VirtualAccount::updateOrCreate(
                [
                    'holder_type' => get_class($holder),
                    'holder_id' => $holder->id,
                ],
                [
                    'bank_name' => $accountData['bank_name'] ?? 'PalmPay',
                    'account_number' => $accountData['account_number'],
                    'account_name' => $accountData['account_name'] ?? $accountName,
                    'email_alias' => $emailAlias,
                    'provider' => 'paymint',
                    'status' => $accountData['status'] ?? 'active',
                    'reference' => $reference,
                    'meta' => [
                        'customer_id' => $resData['data']['customer']['id'] ?? null,
                        'paymint_account_id' => $accountData['id'] ?? null,
                        'kyc_type' => $kycType,
                        'email_alias' => $emailAlias,
                        'raw_response' => $resData,
                    ],
                ]
            );
        } catch (\Throwable $e) {
            Log::error('PayMint Virtual Account Creation Error: ' . $e->getMessage(), [
                'exception' => $e,
                'holder_id' => $holder->id,
            ]);

            throw new \Exception('PayMint Account Error: ' . $e->getMessage());
        }
    }

    /**
     * Create a unique email alias to trace webhooks back to tenant and holder.
     * Format for User: t{store_id}-u{user_id}@va.affanhub.com (e.g. t1-u94@va.affanhub.com)
     * Format for Store: t{store_id}-merchant@va.affanhub.com (e.g. t1-merchant@va.affanhub.com)
     */
    public function customerEmailAlias(Model $holder): string
    {
        if ($holder instanceof User) {
            $storeId = $holder->store_id ?? ($holder->store->id ?? 1);
            return sprintf('t%s-u%s@va.affanhub.com', $storeId, $holder->id);
        }

        // Holder is Store
        $storeId = $holder->id ?? 1;
        return sprintf('t%s-merchant@va.affanhub.com', $storeId);
    }

    /**
     * Normalize and strictly validate Nigerian phone numbers.
     */
    public function normalizePhoneNumber(?string $phoneNumber): string
    {
        if (empty($phoneNumber)) {
            throw new \InvalidArgumentException('A valid phone number is required for virtual account creation.');
        }

        // Strip non-digits
        $digits = preg_replace('/\D+/', '', (string) $phoneNumber);

        // Convert international format 2348012345678 (13 digits) -> 08012345678 (11 digits)
        if (str_starts_with($digits, '234') && strlen($digits) === 13) {
            $digits = '0' . substr($digits, 3);
        }

        // Validate exact 11-digit local phone number length
        if (strlen($digits) !== 11) {
            throw new \InvalidArgumentException('Please enter a valid 11-digit phone number (e.g. 08012345678).');
        }

        return $digits;
    }
}
