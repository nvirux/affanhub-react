<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Owner;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User;
use Throwable;

class MerchantGoogleAuthController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     */
    public function redirect(): RedirectResponse
    {
        if (request()->filled('test_event_code')) {
            session(['meta_test_event_code' => request()->query('test_event_code')]);
        }

        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google and authenticate the merchant.
     */
    public function callback(): RedirectResponse
    {
        $panel = filament()->getPanel('merchant');
        $loginUrl = $panel->getLoginUrl();

        try {
            /** @var User $googleUser */
            $googleUser = Socialite::driver('google')->user();
        } catch (Throwable $e) {
            Log::warning('Merchant Google OAuth error: '.$e->getMessage(), [
                'exception' => $e,
            ]);

            return redirect()->to($loginUrl)->with('error', 'Unable to sign in with Google. Please try again or log in with your email.');
        }

        $email = $googleUser->getEmail();
        $googleId = $googleUser->getId();

        if (empty($email)) {
            return redirect()->to($loginUrl)->with('error', 'Your Google account did not provide an email address.');
        }

        // Find owner by Google ID or by email
        $owner = Owner::where('google_id', $googleId)->first();

        if (! $owner) {
            $owner = Owner::where('email', $email)->first();
        }

        $wasJustVerified = false;

        if ($owner) {
            $updates = [];

            if (empty($owner->google_id)) {
                $updates['google_id'] = $googleId;
            }

            if (! $owner->hasVerifiedEmail()) {
                $updates['email_verified_at'] = now();
                $wasJustVerified = true;
            }

            if (empty($owner->avatar) && ! empty($googleUser->getAvatar())) {
                $updates['avatar'] = $googleUser->getAvatar();
            }

            if (! empty($updates)) {
                $owner->updateQuietly($updates);
            }
        } else {
            $isNewOwner = true;
            $owner = Owner::create([
                'name' => $googleUser->getName() ?: Str::headline(explode('@', $email)[0]),
                'email' => $email,
                'google_id' => $googleId,
                'avatar' => $googleUser->getAvatar(),
                'email_verified_at' => now(),
                'password' => null,
                'max_stores' => 3,
            ]);
        }

        Auth::guard('owner')->login($owner, remember: true);
        request()->session()->regenerate();

        if (! empty($isNewOwner) || ! empty($wasJustVerified)) {
            $testCode = request()->query('test_event_code')
                ?? session('meta_test_event_code')
                ?? env('META_TEST_EVENT_CODE');

            $pixelParams = [
                'content_name' => 'Merchant Account Registered (Google OAuth)',
                'status' => true,
                'value' => 1.00,
                'currency' => 'NGN',
            ];

            if (! empty($testCode)) {
                $pixelParams['test_event_code'] = $testCode;
            }

            request()->session()->put('pixel_event', [
                'event' => 'CompleteRegistration',
                'params' => $pixelParams,
            ]);
        }

        if (empty($owner->phone)) {
            return redirect()->route('merchant.phone.complete');
        }

        $tenant = $owner->getDefaultTenant($panel);

        if ($tenant) {
            return redirect()->to($panel->getUrl($tenant));
        }

        return redirect()->to($panel->getTenantRegistrationUrl());
    }
}
