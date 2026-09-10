<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Admin;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class ImpersonationController extends Controller
{
    /**
     * Consume a one-time secure impersonation token and sign in to the merchant panel.
     */
    public function consume(Request $request): RedirectResponse
    {
        $token = $request->query('token');
        $tenantPublicId = $request->query('tenant');

        if (! $token) {
            abort(403, 'Missing impersonation token.');
        }

        $cacheKey = "impersonate_token_{$token}";
        $data = Cache::get($cacheKey);

        if (! $data) {
            abort(403, 'Invalid or expired impersonation token. Please return to Central Admin and try again.');
        }

        // Burn token immediately to prevent reuse
        Cache::forget($cacheKey);

        // Authenticate into owner guard
        Auth::guard('owner')->loginUsingId($data['owner_id']);

        // Set impersonation flags in session
        session()->put('impersonated_by_admin', $data['admin_id']);
        session()->put('impersonated_admin_name', $data['admin_name']);
        session()->put('impersonated_store_id', $data['store_id']);
        session()->put('impersonated_store_name', $data['store_name']);

        $scheme = $request->getScheme();
        $host = $request->getHost();
        $port = $request->getPort();
        $portStr = ($port && ! in_array($port, [80, 443])) ? ":{$port}" : '';

        return redirect()->to("{$scheme}://{$host}{$portStr}/{$tenantPublicId}");
    }

    /**
     * Leave the impersonation session, log out of the owner guard, and return to Central Admin.
     */
    public function leave(Request $request): RedirectResponse
    {
        if (session()->has('impersonated_by_admin')) {
            $adminId = session()->get('impersonated_by_admin');
            $storeId = session()->get('impersonated_store_id');

            ActivityLog::create([
                'tenant_id' => $storeId,
                'causer_type' => Admin::class,
                'causer_id' => $adminId,
                'event' => 'admin_impersonate_store_end',
                'description' => 'Super Admin ended impersonation session.',
                'properties' => [
                    'store_id' => $storeId,
                    'admin_id' => $adminId,
                ],
            ]);

            Auth::guard('owner')->logout();
            session()->forget([
                'impersonated_by_admin',
                'impersonated_admin_name',
                'impersonated_store_id',
                'impersonated_store_name',
            ]);
        }

        $scheme = $request->getScheme();
        $host = $request->getHost();
        $cleanHost = preg_replace('/^(admin|merchant)\./', '', $host);
        $port = $request->getPort();
        $portStr = ($port && ! in_array($port, [80, 443])) ? ":{$port}" : '';

        return redirect()->away("{$scheme}://admin.{$cleanHost}{$portStr}/stores");
    }
}
