<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class TenantAuthController extends Controller
{
    /**
     * Validate Step 1 of customer registration (name, email, phone).
     */
    public function validateRegisterStep1(Request $request): JsonResponse
    {
        $tenantId = function_exists('tenant') && tenant() ? tenant('id') : null;

        $input = $request->all();
        $phone = preg_replace('/[^0-9]/', '', $input['phone'] ?? '');
        if (str_starts_with($phone, '234') && strlen($phone) === 13) {
            $phone = '0'.substr($phone, 3);
        }
        $input['phone'] = $phone;

        $validator = Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class)->where(fn ($query) => $query->where('store_id', $tenantId)),
            ],
            'phone' => [
                'required',
                'string',
                'digits:11',
                Rule::unique(User::class)->where(fn ($query) => $query->where('store_id', $tenantId)),
            ],
        ], [
            'name.required' => 'Please enter your full name.',
            'email.required' => 'Please enter your email address.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email address is already registered to an account.',
            'phone.required' => 'Please enter your phone number.',
            'phone.digits' => 'Phone number must be exactly 11 digits (e.g. 08012345678).',
            'phone.unique' => 'This phone number is already registered to an account.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        return response()->json([
            'valid' => true,
        ]);
    }

    /**
     * Check if an identifier (email or phone) exists within the active tenant,
     * and return whether login PIN is enabled.
     */
    public function checkIdentifier(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'identifier' => ['required', 'string', 'max:255'],
        ]);

        $rawIdentifier = trim($validated['identifier']);
        $tenantId = function_exists('tenant') && tenant() ? tenant('id') : null;

        // Clean phone number (strip spaces/dashes)
        $normalizedPhone = preg_replace('/[^0-9+]/', '', $rawIdentifier);

        $user = User::query()
            ->when($tenantId, fn ($query) => $query->where('store_id', $tenantId))
            ->where(function ($query) use ($rawIdentifier, $normalizedPhone) {
                $query->where('email', $rawIdentifier)
                    ->orWhere('phone', $rawIdentifier);

                if (! empty($normalizedPhone) && $normalizedPhone !== $rawIdentifier) {
                    $query->orWhere('phone', $normalizedPhone);
                }
            })
            ->first();

        if (! $user) {
            return response()->json([
                'status' => 'not_found',
                'message' => 'No account found matching this phone number or email.',
            ], 404);
        }

        if ($user->is_active === false) {
            return response()->json([
                'status' => 'suspended',
                'message' => 'Your account has been suspended. Please contact store support.',
            ], 403);
        }

        $masked = $rawIdentifier;
        if (filter_var($rawIdentifier, FILTER_VALIDATE_EMAIL)) {
            $parts = explode('@', $rawIdentifier);
            $name = $parts[0];
            $domain = $parts[1] ?? '';
            $maskedName = strlen($name) > 2 ? substr($name, 0, 1).str_repeat('*', strlen($name) - 2).substr($name, -1) : $name;
            $masked = $maskedName.'@'.$domain;
        } elseif (strlen($rawIdentifier) >= 6) {
            $masked = substr($rawIdentifier, 0, 3).str_repeat('*', max(1, strlen($rawIdentifier) - 6)).substr($rawIdentifier, -3);
        }

        return response()->json([
            'status' => 'found',
            'login_pin_enabled' => (bool) $user->login_pin_enabled,
            'has_password' => ! empty($user->password),
            'name' => $user->name,
            'identifier' => $rawIdentifier,
            'masked_identifier' => $masked,
        ]);
    }

    /**
     * Authenticate a user via secure biometric token from mobile app.
     */
    public function biometricLogin(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'identifier' => ['required', 'string', 'max:255'],
            'biometric_token' => ['required', 'string', 'min:32', 'max:128'],
        ]);

        $rawIdentifier = trim($validated['identifier']);
        $tenantId = function_exists('tenant') && tenant() ? tenant('id') : null;
        $normalizedPhone = preg_replace('/[^0-9+]/', '', $rawIdentifier);

        $user = User::query()
            ->when($tenantId, fn ($query) => $query->where('store_id', $tenantId))
            ->where(function ($query) use ($rawIdentifier, $normalizedPhone) {
                $query->where('email', $rawIdentifier)
                    ->orWhere('phone', $rawIdentifier);

                if (! empty($normalizedPhone) && $normalizedPhone !== $rawIdentifier) {
                    $query->orWhere('phone', $normalizedPhone);
                }
            })
            ->first();

        if (! $user) {
            return response()->json([
                'message' => 'No account found matching this identifier.',
            ], 404);
        }

        if ($user->is_active === false) {
            return response()->json([
                'message' => 'Your account has been suspended. Please contact store support.',
            ], 403);
        }

        if (empty($user->biometric_token)) {
            return response()->json([
                'message' => 'Biometric login has expired or was disabled. Please enter your PIN.',
            ], 401);
        }

        $tokenHash = hash('sha256', $validated['biometric_token']);

        if (! hash_equals($user->biometric_token, $tokenHash)) {
            return response()->json([
                'message' => 'Invalid biometric credentials. Please enter your PIN.',
            ], 401);
        }

        Auth::login($user, true);
        $request->session()->regenerate();

        if ($request->wantsJson() && ! $request->header('X-Inertia')) {
            return response()->json([
                'success' => true,
                'redirect' => route('dashboard'),
            ]);
        }

        return redirect()->intended(route('dashboard'));
    }

    /**
     * Generate or refresh biometric token for the authenticated user.
     */
    public function generateBiometricToken(Request $request): JsonResponse
    {
        $user = $request->user();
        $plainToken = Str::random(64);

        $user->update([
            'biometric_token' => hash('sha256', $plainToken),
        ]);

        return response()->json([
            'success' => true,
            'biometric_token' => $plainToken,
            'identifier' => $user->phone ?: $user->email,
        ]);
    }

    /**
     * Revoke biometric token for the authenticated user.
     */
    public function revokeBiometricToken(Request $request): JsonResponse
    {
        $request->user()->update([
            'biometric_token' => null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Biometric login disabled.',
        ]);
    }
}
