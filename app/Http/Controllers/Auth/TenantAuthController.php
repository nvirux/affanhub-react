<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TenantAuthController extends Controller
{
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
}
