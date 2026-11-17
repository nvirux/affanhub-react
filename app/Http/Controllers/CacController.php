<?php

namespace App\Http\Controllers;

use App\Models\CacRequest;
use App\Models\StoreCacService;
use App\Models\Transaction;
use App\Services\Cac\CacService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CacController extends Controller
{
    /**
     * Display the CAC registration storefront page.
     */
    public function index(Request $request): Response
    {
        $user = $request->user();
        $store = tenant();

        $mainWallet = method_exists($user, 'wallet') ? $user->wallet('main') : null;
        $walletBalance = (float) ($mainWallet?->balance ?? 0.00);

        // Auto-seed default packages if store has none yet
        if ($store) {
            $count = StoreCacService::where('store_id', $store->id)->count();
            if ($count === 0) {
                StoreCacService::seedDefaultsForStore($store);
            }
        }

        // Fetch active packages
        $packages = StoreCacService::where('store_id', $store?->id)
            ->where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->get()
            ->map(fn (StoreCacService $s) => [
                'id' => $s->id,
                'name' => $s->name,
                'slug' => $s->slug,
                'description' => $s->description ?? '',
                'price' => (float) $s->price,
                'processing_time' => $s->processing_time ?? '3 - 5 Days',
                'fields' => $s->getNormalizedFields(),
                'required_documents' => $s->getNormalizedDocuments(),
                'instructions' => $s->instructions ?? '',
            ]);

        // Fetch recent CAC orders for this customer
        $recentOrders = CacRequest::where('user_id', $user->id)
            ->where('store_id', $store?->id)
            ->latest()
            ->take(20)
            ->get()
            ->map(fn (CacRequest $r) => [
                'id' => $r->id,
                'reference' => $r->reference,
                'service_name' => $r->service_name,
                'business_name' => $r->business_name,
                'amount_paid' => (float) $r->amount_paid,
                'status' => $r->status,
                'rejection_reason' => $r->rejection_reason,
                'admin_notes' => $r->admin_notes,
                'has_result_file' => filled($r->result_file_path),
                'submitted_fields' => $r->submitted_fields,
                'uploaded_documents' => $r->uploaded_documents,
                'created_at' => $r->created_at->format('M d, Y · h:i A'),
            ]);

        return Inertia::render('Storefront/Cac/Index', [
            'packages' => $packages,
            'recentOrders' => $recentOrders,
            'walletBalance' => $walletBalance,
        ]);
    }

    /**
     * Submit a new CAC registration order.
     */
    public function store(Request $request, CacService $cacService): RedirectResponse|JsonResponse
    {
        $user = $request->user();
        $store = tenant();

        $validated = $request->validate([
            'service_id' => 'required|integer',
            'transaction_pin' => 'required|string',
            'fields' => 'nullable|array',
            'documents' => 'nullable|array',
        ]);

        $cacServiceRecord = StoreCacService::where('id', $validated['service_id'])
            ->where('store_id', $store?->id)
            ->where('is_active', true)
            ->first();

        if (! $cacServiceRecord) {
            return $this->returnErrorResponse($request, 'The selected CAC package is currently unavailable.', 404);
        }

        $submittedFields = $request->input('fields', []);
        $uploadedFiles = $request->file('documents', []);

        try {
            $cacRequest = $cacService->submitApplication(
                store: $store,
                user: $user,
                service: $cacServiceRecord,
                submittedFields: $submittedFields,
                uploadedFiles: $uploadedFiles,
                transactionPin: $validated['transaction_pin']
            );

            // Record transaction in history
            Transaction::create([
                'store_id' => $store?->id,
                'user_id' => $user->id,
                'service_id' => $cacServiceRecord->service_id,
                'reference' => $cacRequest->reference,
                'service_type' => 'cac_registration',
                'amount' => (float) $cacServiceRecord->price,
                'amount_paid' => (float) $cacServiceRecord->price,
                'cost_price' => 0,
                'vendor_cost' => 0,
                'profit' => (float) $cacServiceRecord->price,
                'platform_profit' => 0,
                'recipient' => $cacRequest->business_name ?? $cacServiceRecord->name,
                'status' => 'successful',
                'api_response' => [
                    'service' => 'cac_registration',
                    'package_title' => $cacServiceRecord->name,
                    'business_name' => $cacRequest->business_name,
                ],
            ]);

            if ($request->header('X-Inertia')) {
                return back()->with('success', "Your {$cacServiceRecord->name} application has been submitted successfully! Order reference: {$cacRequest->reference}");
            }

            return response()->json([
                'success' => true,
                'message' => "Your {$cacServiceRecord->name} application has been submitted successfully! Order reference: {$cacRequest->reference}",
                'reference' => $cacRequest->reference,
                'order' => [
                    'reference' => $cacRequest->reference,
                    'service_name' => $cacServiceRecord->name,
                    'business_name' => $cacRequest->business_name,
                    'amount_paid' => (float) $cacServiceRecord->price,
                    'status' => 'pending',
                    'created_at' => now()->format('M d, Y · h:i A'),
                ],
            ]);
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Throwable $e) {
            Log::error('CAC Application Error: '.$e->getMessage(), ['trace' => $e->getTraceAsString()]);

            return $this->returnErrorResponse($request, 'Failed to process CAC application: '.$e->getMessage(), 500);
        }
    }

    /**
     * Download completed official CAC certificate or status report.
     */
    public function downloadCertificate(Request $request, string $reference): StreamedResponse|RedirectResponse
    {
        $user = $request->user();
        $store = tenant();

        $record = CacRequest::where('reference', $reference)
            ->where('store_id', $store?->id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        if (empty($record->result_file_path) || ! Storage::disk('public')->exists($record->result_file_path)) {
            return back()->with('error', 'The CAC certificate for this order is not yet ready or has not been uploaded.');
        }

        $filename = 'CAC_Certificate_'.$record->reference.'.'.pathinfo($record->result_file_path, PATHINFO_EXTENSION);

        return Storage::disk('public')->download($record->result_file_path, $filename);
    }

    /**
     * Unified error responder supporting both Inertia page sessions and JSON APIs.
     */
    protected function returnErrorResponse(Request $request, string $message, int $status = 400): RedirectResponse|JsonResponse
    {
        if ($request->header('X-Inertia')) {
            return back()->withErrors(['message' => $message]);
        }

        return response()->json([
            'success' => false,
            'message' => $message,
        ], $status);
    }
}
