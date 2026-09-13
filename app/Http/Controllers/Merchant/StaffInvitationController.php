<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\Owner;
use App\Models\StaffInvitation;
use Filament\Facades\Filament;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\ViewErrorBag;
use Illuminate\Validation\Rules\Password;

class StaffInvitationController extends Controller
{
    /**
     * Show the staff invitation acceptance page.
     */
    public function show(string $token)
    {
        $invitation = StaffInvitation::where('token', $token)->first();

        if (! $invitation || $invitation->isExpired()) {
            return view('merchant.invitation.invalid', [
                'isExpired' => $invitation ? $invitation->isExpired() : false,
            ]);
        }

        $store = $invitation->store;
        $existingOwner = Owner::where('email', strtolower($invitation->email))->first();
        $currentUser = Auth::guard('owner')->user();

        return view('merchant.invitation.accept', [
            'invitation' => $invitation,
            'store' => $store,
            'existingOwner' => $existingOwner,
            'currentUser' => $currentUser,
            'errors' => session('errors', new ViewErrorBag),
        ]);
    }

    /**
     * Accept invitation for an existing AffanHub owner account.
     */
    public function acceptExisting(Request $request, string $token)
    {
        $invitation = StaffInvitation::where('token', $token)->first();

        if (! $invitation || $invitation->isExpired()) {
            return redirect()->route('merchant.invitation.show', ['token' => $token]);
        }

        $store = $invitation->store;
        $currentUser = Auth::guard('owner')->user();

        // If user is already authenticated as the invited email
        if ($currentUser && strtolower($currentUser->email) === strtolower($invitation->email)) {
            $store->members()->syncWithoutDetaching([
                $currentUser->id => ['role' => $invitation->role],
            ]);

            $invitation->delete();

            session()->flash('notification', [
                'status' => 'success',
                'title' => 'Invitation Accepted',
                'body' => "You have joined {$store->name} as {$invitation->role}!",
            ]);

            return redirect()->to($this->getMerchantStoreUrl($store));
        }

        // Otherwise authenticate with provided password
        $request->validate([
            'password' => ['required', 'string'],
        ]);

        if (! Auth::guard('owner')->attempt([
            'email' => strtolower($invitation->email),
            'password' => $request->password,
        ])) {
            return back()->withErrors(['password' => 'Incorrect password for this account.'])->withInput();
        }

        $owner = Auth::guard('owner')->user();

        $store->members()->syncWithoutDetaching([
            $owner->id => ['role' => $invitation->role],
        ]);

        $invitation->delete();

        return redirect()->to($this->getMerchantStoreUrl($store));
    }

    /**
     * Register a new account and accept the staff invitation.
     */
    public function registerAndAccept(Request $request, string $token)
    {
        $invitation = StaffInvitation::where('token', $token)->first();

        if (! $invitation || $invitation->isExpired()) {
            return redirect()->route('merchant.invitation.show', ['token' => $token]);
        }

        $request->validate([
            'name' => ['required', 'string', 'max:70'],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $store = $invitation->store;
        $email = strtolower(trim($invitation->email));

        // Create the Owner account
        $newOwner = Owner::create([
            'name' => $request->name,
            'email' => $email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'email_verified_at' => now(),
            'max_stores' => 3,
        ]);

        // Attach to the store with the invited role
        $store->members()->attach($newOwner->id, [
            'role' => $invitation->role,
        ]);

        // Clean up invitation
        $invitation->delete();

        // Log the user in
        Auth::guard('owner')->login($newOwner);

        return redirect()->to($this->getMerchantStoreUrl($store));
    }

    /**
     * Resolve the direct Filament Merchant URL for a store.
     */
    protected function getMerchantStoreUrl($store): string
    {
        $request = request();
        $scheme = $request->getScheme();
        $host = $request->getHost();
        $port = $request->getPort();
        $portString = ($port && ! in_array($port, [80, 443])) ? ":{$port}" : '';

        if (str_contains($host, 'localhost') || $host === '127.0.0.1') {
            return "{$scheme}://merchant.localhost{$portString}/{$store->public_id}";
        }

        $appHost = parse_url(config('app.url'), PHP_URL_HOST) ?? $host;
        if (! str_starts_with($appHost, 'merchant.')) {
            $appHost = 'merchant.'.$appHost;
        }

        return "{$scheme}://{$appHost}{$portString}/{$store->public_id}";
    }
}
