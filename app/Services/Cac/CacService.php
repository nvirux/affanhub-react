<?php

namespace App\Services\Cac;

use App\Models\CacRequest;
use App\Models\Store;
use App\Models\StoreCacService;
use App\Models\User;
use App\Services\WalletService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CacService
{
    public function __construct(
        protected WalletService $walletService
    ) {}

    /**
     * Validate dynamic fields and document uploads against service rules.
     *
     * @param  array<string, mixed>  $submittedFields
     * @param  array<string, UploadedFile>  $uploadedFiles
     *
     * @throws ValidationException
     */
    public function validateSubmission(StoreCacService $service, array $submittedFields, array $uploadedFiles): void
    {
        $errors = [];

        // 1. Validate Form Fields
        foreach ($service->getNormalizedFields() as $field) {
            $label = $field['label'];
            $key = Str::slug($label, '_');
            $isRequired = $field['is_required'] ?? true;
            $value = $submittedFields[$key] ?? $submittedFields[$label] ?? null;

            if ($isRequired && (is_null($value) || trim((string) $value) === '')) {
                $errors["fields.{$key}"] = ["The field '{$label}' is required."];
            }
        }

        // 2. Validate Required Documents
        foreach ($service->getNormalizedDocuments() as $doc) {
            $name = $doc['name'];
            $key = Str::slug($name, '_');
            $isRequired = $doc['is_required'] ?? true;
            $file = $uploadedFiles[$key] ?? $uploadedFiles[$name] ?? null;

            if ($isRequired && ! ($file instanceof UploadedFile)) {
                $errors["documents.{$key}"] = ["Document '{$name}' is required."];
            } elseif ($file instanceof UploadedFile) {
                $extension = strtolower($file->getClientOriginalExtension());
                $allowedExtensions = ['pdf', 'jpg', 'jpeg', 'png'];

                if (! in_array($extension, $allowedExtensions)) {
                    $errors["documents.{$key}"] = ["'{$name}' must be a PDF, JPG, or PNG file."];
                }

                // Max 5MB per document
                if ($file->getSize() > 5 * 1024 * 1024) {
                    $errors["documents.{$key}"] = ["'{$name}' must not exceed 5MB."];
                }
            }
        }

        if (! empty($errors)) {
            throw ValidationException::withMessages($errors);
        }
    }

    /**
     * Submit a new CAC application with atomic wallet payment and file uploads.
     *
     * @param  array<string, mixed>  $submittedFields
     * @param  array<string, UploadedFile>  $uploadedFiles
     *
     * @throws ValidationException
     */
    public function submitApplication(
        Store $store,
        User $user,
        StoreCacService $service,
        array $submittedFields,
        array $uploadedFiles,
        string $transactionPin
    ): CacRequest {
        // 1. Validate Transaction PIN
        if (empty($user->transaction_pin_hash)) {
            throw ValidationException::withMessages([
                'transaction_pin' => ['Please set up your transaction PIN in your security settings before proceeding.'],
            ]);
        }

        if (! Hash::check($transactionPin, $user->transaction_pin_hash)) {
            throw ValidationException::withMessages([
                'transaction_pin' => ['The transaction PIN you entered is incorrect.'],
            ]);
        }

        // 2. Dynamic Schema Validation
        $this->validateSubmission($service, $submittedFields, $uploadedFiles);

        $amount = (float) $service->price;
        $reference = CacRequest::generateReference();

        // 3. Extract primary business name for easy listing
        $businessName = null;
        foreach ($submittedFields as $k => $v) {
            if (is_string($v) && (str_contains(strtolower($k), 'name') || is_null($businessName))) {
                $businessName = trim($v);
                break;
            }
        }

        return DB::transaction(function () use ($store, $user, $service, $submittedFields, $uploadedFiles, $amount, $reference, $businessName) {
            // Lock and verify customer wallet
            $customerWallet = $user->wallet('main');
            if (! $customerWallet) {
                throw ValidationException::withMessages([
                    'balance' => ['Customer main wallet not found.'],
                ]);
            }

            // Lock row for concurrency
            $customerWallet = DB::table('wallets')
                ->where('id', $customerWallet->id)
                ->lockForUpdate()
                ->first();

            if (! $customerWallet || (float) $customerWallet->balance < $amount) {
                throw ValidationException::withMessages([
                    'balance' => [
                        'Insufficient wallet balance. This service costs ₦'.number_format($amount, 2).', but your balance is ₦'.number_format((float) ($customerWallet->balance ?? 0), 2),
                    ],
                ]);
            }

            // Store uploaded documents
            $uploadedDocsData = [];
            foreach ($uploadedFiles as $key => $file) {
                if ($file instanceof UploadedFile) {
                    $path = $file->store("cac_documents/{$store->id}/{$reference}", 'public');
                    $uploadedDocsData[] = [
                        'key' => $key,
                        'original_name' => $file->getClientOriginalName(),
                        'file_path' => $path,
                        'size' => $file->getSize(),
                    ];
                }
            }

            // Debit user wallet
            $userMainWalletModel = $user->wallet('main');
            $this->walletService->debit(
                $userMainWalletModel,
                $amount,
                'cac_service_payment',
                "Payment for {$service->name} (Ref: {$reference})",
                [
                    'order_reference' => $reference,
                    'service_name' => $service->name,
                    'store_id' => $store->id,
                ],
                'DEB_'.$reference
            );

            // Settle online store main wallet credit
            $storeMainWallet = $store->mainWallet();
            if ($storeMainWallet) {
                $this->walletService->credit(
                    $storeMainWallet,
                    $amount,
                    'customer_cac_payment',
                    "Customer payment received for {$service->name} (#{$reference})",
                    [
                        'order_reference' => $reference,
                        'customer_id' => $user->id,
                    ],
                    'ORD_'.$reference
                );
            }

            // Create CacRequest record
            return CacRequest::create([
                'reference' => $reference,
                'store_id' => $store->id,
                'user_id' => $user->id,
                'store_cac_service_id' => $service->id,
                'service_name' => $service->name,
                'business_name' => $businessName,
                'submitted_fields' => $submittedFields,
                'uploaded_documents' => $uploadedDocsData,
                'amount_paid' => $amount,
                'status' => 'pending',
                'settlement_type' => 'online_profit_credit',
            ]);
        });
    }

    /**
     * Mark an order as processing.
     */
    public function markProcessing(CacRequest $request, ?string $notes = null): void
    {
        $request->markProcessing($notes);
    }

    /**
     * Complete order and attach result file.
     */
    public function complete(CacRequest $request, ?string $resultFilePath = null, ?string $notes = null): void
    {
        $request->complete($resultFilePath, $notes);
    }

    /**
     * Reject order and refund customer wallet balance.
     */
    public function reject(CacRequest $request, string $reason, ?string $notes = null): void
    {
        $request->reject($reason, $notes);
    }
}
