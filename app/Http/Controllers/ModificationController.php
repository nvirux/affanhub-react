<?php

namespace App\Http\Controllers;

use App\Models\ModificationRequest;
use App\Models\StoreModificationService;
use App\Models\Transaction;
use App\Services\WalletService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ModificationController extends Controller
{
    /**
     * Display the NIN modification storefront page.
     */
    public function ninIndex(Request $request): Response
    {
        return $this->renderView($request, 'nin');
    }

    /**
     * Display the BVN modification storefront page.
     */
    public function bvnIndex(Request $request): Response
    {
        return $this->renderView($request, 'bvn');
    }

    /**
     * Display the modification storefront page (router / fallback).
     */
    public function index(Request $request): Response
    {
        $type = $request->query('type', 'nin');

        return $this->renderView($request, in_array($type, ['nin', 'bvn']) ? $type : 'nin');
    }

    /**
     * Render the storefront modification page for the specific category.
     */
    protected function renderView(Request $request, string $category): Response
    {
        $user = $request->user();
        $store = tenant();

        $mainWallet = method_exists($user, 'wallet') ? $user->wallet('main') : null;
        $walletBalance = (float) ($mainWallet?->balance ?? 0.00);

        // Auto-seed default modification services if store has none yet
        if ($store) {
            $count = StoreModificationService::where('store_id', $store->id)->count();
            if ($count === 0) {
                StoreModificationService::seedDefaultsForStore($store);
            }
        }

        // Fetch active modification services scoped to this category
        $services = StoreModificationService::where('store_id', $store?->id)
            ->where('is_active', true)
            ->where(function ($query) use ($category) {
                if ($category === 'nin') {
                    $query->whereHas('service', fn ($q) => $q->where('key', 'nin_modification'))
                        ->orWhere('slug', 'like', 'nin_%');
                } else {
                    $query->whereHas('service', fn ($q) => $q->where('key', 'bvn_modification'))
                        ->orWhere('slug', 'like', 'bvn_%');
                }
            })
            ->with('service')
            ->orderBy('sort_order', 'asc')
            ->get()
            ->map(fn (StoreModificationService $s) => [
                'id' => $s->id,
                'name' => $s->name,
                'slug' => $s->slug,
                'service_key' => $s->service?->key ?? (str_starts_with($s->slug, 'nin_') ? 'nin_modification' : 'bvn_modification'),
                'category' => str_starts_with($s->slug, 'bvn_') || $s->service?->key === 'bvn_modification' ? 'bvn' : 'nin',
                'description' => $s->description ?? '',
                'price' => (float) $s->price,
                'processing_time' => $s->processing_time ?? '24 - 48 Hours',
                'fields' => $s->getNormalizedFields(),
                'required_documents' => $s->getNormalizedDocuments(),
                'instructions' => $s->instructions ?? '',
            ])
            ->values()
            ->toArray();

        // Recent modification requests submitted by current user (scoped to category)
        $recentRequests = ModificationRequest::where('user_id', $user->id)
            ->where('store_id', $store?->id)
            ->where(function ($query) use ($category) {
                if ($category === 'nin') {
                    $query->where('service_name', 'like', '%NIN%')
                        ->orWhereHas('service', fn ($q) => $q->where('key', 'nin_modification'));
                } else {
                    $query->where('service_name', 'like', '%BVN%')
                        ->orWhereHas('service', fn ($q) => $q->where('key', 'bvn_modification'));
                }
            })
            ->latest()
            ->limit(10)
            ->get()
            ->map(fn (ModificationRequest $r) => [
                'id' => $r->id,
                'reference' => $r->reference,
                'service_name' => $r->service_name,
                'identifier' => $r->identifier,
                'amount_paid' => (float) $r->amount_paid,
                'status' => $r->status,
                'old_data' => $r->old_data,
                'new_data' => $r->new_data,
                'submitted_fields' => $r->submitted_fields ?? $r->new_data ?? [],
                'rejection_reason' => $r->rejection_reason,
                'admin_notes' => $r->admin_notes,
                'has_result_file' => ! empty($r->result_file_path),
                'download_url' => ! empty($r->result_file_path) ? route('identity.modification.download', ['reference' => $r->reference]) : null,
                'created_at' => $r->created_at->format('M d, Y · h:i A'),
                'processed_at' => $r->processed_at?->format('M d, Y · h:i A'),
            ])
            ->values()
            ->toArray();

        return Inertia::render('Storefront/Identity/Modification', [
            'category' => $category,
            'services' => $services,
            'recentRequests' => $recentRequests,
            'walletBalance' => $walletBalance,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'phone' => $user->phone,
                'email' => $user->email,
                'has_pin' => $user->hasTransactionPin(),
            ],
        ]);
    }

    /**
     * Submit a modification request with required documents and wallet payment.
     */
    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $user = $request->user();
        $store = tenant();

        $validated = $request->validate([
            'service_id' => ['required', 'integer'],
            'identifier' => ['required', 'string', 'max:50'],
            'fields' => ['nullable', 'array'],
            'old_data' => ['nullable', 'array'],
            'new_data' => ['nullable', 'array'],
            'pin' => ['nullable', 'string', 'digits:4'],
            'transaction_pin' => ['nullable', 'string', 'digits:4'],
        ]);

        // Verify transaction PIN if customer has one configured
        $pin = $request->input('transaction_pin') ?? $request->input('pin');
        if ($user->hasTransactionPin()) {
            if (empty($pin) || ! Hash::check($pin, $user->transaction_pin_hash)) {
                return $this->returnErrorResponse(
                    $request,
                    'Invalid transaction PIN. Please enter your correct 4-digit PIN.',
                    422,
                    ['transaction_pin' => 'Incorrect transaction PIN. Please try again.']
                );
            }
        }

        // Fetch modification service
        $modService = StoreModificationService::where('id', $validated['service_id'])
            ->where('store_id', $store?->id)
            ->where('is_active', true)
            ->first();

        if (! $modService) {
            return $this->returnErrorResponse($request, 'Selected modification service is not available or inactive.', 404);
        }

        $price = (float) $modService->price;
        $mainWallet = method_exists($user, 'wallet') ? $user->wallet('main') : null;
        $walletBalance = (float) ($mainWallet?->balance ?? 0.00);

        if (! $mainWallet || $walletBalance < $price) {
            return $this->returnErrorResponse(
                $request,
                'Insufficient wallet balance (₦'.number_format($walletBalance, 2).'). You need ₦'.number_format($price, 2).' for this service.',
                422
            );
        }

        // Validate dynamic fields
        $configuredFields = $modService->getNormalizedFields();
        $submittedFields = $request->input('fields') ?? [];

        // Support backward compatibility for legacy new_data / old_data payloads
        if (empty($submittedFields) && (! empty($request->input('new_data')) || ! empty($request->input('old_data')))) {
            $legacyCombined = array_merge($request->input('old_data') ?? [], $request->input('new_data') ?? []);
            $i = 0;
            $legacyValues = array_values($legacyCombined);
            foreach ($configuredFields as $field) {
                $label = $field['label'];
                $submittedFields[$label] = $legacyCombined[$label] ?? ($legacyValues[$i] ?? '');
                $i++;
            }
        }

        foreach ($configuredFields as $field) {
            $label = $field['label'];
            $value = trim((string) ($submittedFields[$label] ?? ''));

            if ($field['is_required'] && $value === '') {
                return $this->returnErrorResponse(
                    $request,
                    "Please provide: {$label}",
                    422,
                    ["fields.{$label}" => "The {$label} field is required."]
                );
            }
        }

        // Validate uploaded documents against required checklist
        $configuredDocs = $modService->getNormalizedDocuments();
        $uploadedPaths = [];
        $documentsArray = $request->file('documents') ?? [];

        foreach ($configuredDocs as $doc) {
            $docName = $doc['name'];
            $isRequired = $doc['is_required'];

            $file = $documentsArray[$docName]
                ?? $request->file("documents.{$docName}")
                ?? $request->file('doc_'.md5($docName))
                ?? $request->file(str_replace(' ', '_', $docName));

            if (! $file) {
                if ($isRequired) {
                    return $this->returnErrorResponse(
                        $request,
                        "Please upload a clear copy of required document: {$docName}",
                        422,
                        ["documents.{$docName}" => "Document {$docName} is required."]
                    );
                }

                continue; // Optional document not provided
            }

            $extension = strtolower($file->getClientOriginalExtension());
            if (! in_array($extension, ['jpg', 'jpeg', 'png', 'webp', 'pdf'])) {
                return $this->returnErrorResponse(
                    $request,
                    "Document '{$docName}' must be an image (JPG, PNG) or PDF file.",
                    422
                );
            }

            if ($file->getSize() > 5 * 1024 * 1024) {
                return $this->returnErrorResponse(
                    $request,
                    "Document '{$docName}' exceeds maximum file size (5MB).",
                    422
                );
            }

            $path = $file->store("modifications/store_{$store?->id}/".date('Y_m'), 'central_public');
            $uploadedPaths[$docName] = $path;
        }

        $reference = ModificationRequest::generateReference();
        $walletService = app(WalletService::class);

        // Debit Customer Wallet atomically and create records
        DB::beginTransaction();
        try {
            // 1. Debit customer wallet
            $walletService->debit(
                $mainWallet,
                $price,
                'modification_payment',
                "{$modService->name} for {$validated['identifier']}",
                [
                    'service_name' => $modService->name,
                    'identifier' => $validated['identifier'],
                    'order_reference' => $reference,
                ],
                $reference
            );

            // 2. Create Transaction for user statement
            Transaction::create([
                'store_id' => $store?->id,
                'user_id' => $user->id,
                'service_id' => $modService->service_id,
                'reference' => $reference,
                'service_type' => $modService->service?->key ?? 'identity_modification',
                'amount' => $price,
                'amount_paid' => $price,
                'cost_price' => 0,
                'vendor_cost' => 0,
                'profit' => $price,
                'platform_profit' => 0,
                'recipient' => $validated['identifier'],
                'status' => 'successful',
                'api_response' => [
                    'service' => 'identity_modification',
                    'service_title' => $modService->name,
                    'identifier' => $validated['identifier'],
                ],
            ]);

            // 3. Create Modification Request
            ModificationRequest::create([
                'reference' => $reference,
                'store_id' => $store?->id,
                'user_id' => $user->id,
                'store_modification_service_id' => $modService->id,
                'service_id' => $modService->service_id,
                'service_name' => $modService->name,
                'identifier' => $validated['identifier'],
                'old_data' => $validated['old_data'] ?? [],
                'new_data' => $validated['new_data'] ?? $submittedFields,
                'submitted_fields' => $submittedFields,
                'uploaded_documents' => $uploadedPaths,
                'amount_paid' => $price,
                'status' => 'pending',
                'settlement_type' => 'online_profit_credit',
            ]);

            DB::commit();

            if ($request->header('X-Inertia')) {
                return back()->with('success', "Your {$modService->name} request has been submitted successfully! Ref: {$reference}");
            }

            return response()->json([
                'success' => true,
                'message' => "Your {$modService->name} request has been submitted successfully! Ref: {$reference}",
                'reference' => $reference,
                'order' => [
                    'reference' => $reference,
                    'service_name' => $modService->name,
                    'identifier' => $validated['identifier'],
                    'amount_paid' => $price,
                    'status' => 'pending',
                    'created_at' => now()->format('M d, Y · h:i A'),
                ],
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Modification Submission Error: '.$e->getMessage(), ['trace' => $e->getTraceAsString()]);

            return $this->returnErrorResponse($request, 'Failed to process modification order: '.$e->getMessage(), 500);
        }
    }

    /**
     * Download completed modification result slip.
     */
    public function downloadResult(Request $request, string $reference): StreamedResponse|JsonResponse
    {
        $user = $request->user();
        $store = tenant();

        $record = ModificationRequest::where('reference', $reference)
            ->where('store_id', $store?->id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        if (empty($record->result_file_path) || ! Storage::disk('central_public')->exists($record->result_file_path)) {
            return response()->json([
                'success' => false,
                'message' => 'Result document is not available yet or pending merchant completion.',
            ], 404);
        }

        $filename = "{$record->service_name}_{$record->identifier}_result.".pathinfo($record->result_file_path, PATHINFO_EXTENSION);

        return Storage::disk('central_public')->download($record->result_file_path, $filename);
    }

    /**
     * Return error response appropriate for Inertia or JSON requests.
     */
    protected function returnErrorResponse(Request $request, string $message, int $status = 422, array $errors = []): JsonResponse|RedirectResponse
    {
        if ($request->header('X-Inertia')) {
            return back()->withErrors(array_merge(['message' => $message], $errors))->with('error', $message);
        }

        return response()->json(array_merge([
            'success' => false,
            'message' => $message,
        ], $errors), $status);
    }
}
