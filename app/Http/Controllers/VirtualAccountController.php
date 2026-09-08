<?php

namespace App\Http\Controllers;

use App\Services\Payment\PayMintService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class VirtualAccountController extends Controller
{
    protected PayMintService $payMintService;

    public function __construct(PayMintService $payMintService)
    {
        $this->payMintService = $payMintService;
    }

    /**
     * Generate a virtual account for the authenticated user (Storefront customer or Merchant).
     */
    public function generate(Request $request)
    {
        $user = Auth::user();

        if (! $user) {
            return Redirect::back()->with('error', 'Unauthenticated user.');
        }

        $validated = $request->validate([
            'type' => 'required|in:nin,bvn',
            'number' => 'required|string|size:11',
            'phone' => 'nullable|string|min:10|max:14',
            'name' => 'nullable|string|max:255',
        ]);

        try {
            if (! empty($validated['name']) && $validated['name'] !== $user->name) {
                $user->name = trim($validated['name']);
            }
            if (! empty($validated['phone'])) {
                $user->phone = trim($validated['phone']);
            }
            $user->save();

            $account = $this->payMintService->createVirtualAccount(
                $user,
                $validated['type'],
                $validated['number'],
                $user->phone
            );

            return Redirect::back()->with('success', 'Virtual account generated successfully! Bank: '.$account->bank_name);
        } catch (\Throwable $e) {
            return Redirect::back()->with('error', $e->getMessage());
        }
    }
}
