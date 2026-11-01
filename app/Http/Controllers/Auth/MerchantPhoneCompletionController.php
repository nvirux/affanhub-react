<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class MerchantPhoneCompletionController extends Controller
{
    /**
     * Show the form to complete the merchant's phone number.
     */
    public function create(): View|RedirectResponse
    {
        $owner = Auth::guard('owner')->user();

        if (! $owner) {
            return redirect()->to(filament()->getPanel('merchant')->getLoginUrl());
        }

        if (! empty($owner->phone)) {
            $panel = filament()->getPanel('merchant');
            $tenant = $owner->getDefaultTenant($panel);

            return $tenant
                ? redirect()->to($panel->getUrl($tenant))
                : redirect()->to($panel->getTenantRegistrationUrl());
        }

        return view('filament.merchant.auth.complete-phone', [
            'owner' => $owner,
        ]);
    }

    /**
     * Store the phone number for the authenticated merchant.
     */
    public function store(Request $request): RedirectResponse
    {
        $owner = Auth::guard('owner')->user();

        if (! $owner) {
            return redirect()->to(filament()->getPanel('merchant')->getLoginUrl());
        }

        $validated = $request->validate([
            'phone' => [
                'required',
                'string',
                'digits:11',
                'regex:/^0[789][01]\d{8}$/',
                'unique:owners,phone,'.$owner->id,
            ],
        ], [
            'phone.required' => 'Please enter your phone number to continue.',
            'phone.unique' => 'This phone number is already registered to another merchant account.',
            'phone.digits' => 'Phone number must be exactly 11 digits.',
            'phone.regex' => 'Please enter a valid 11-digit Nigerian phone number (e.g. 08012345678).',
        ]);

        $sanitizedPhone = preg_replace('/[^\d+]/', '', $validated['phone']);

        $owner->update([
            'phone' => $sanitizedPhone,
        ]);

        $panel = filament()->getPanel('merchant');
        $tenant = $owner->getDefaultTenant($panel);

        return $tenant
            ? redirect()->to($panel->getUrl($tenant))
            : redirect()->to($panel->getTenantRegistrationUrl());
    }
}
