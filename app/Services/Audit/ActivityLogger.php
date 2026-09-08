<?php

namespace App\Services\Audit;

use App\Models\ActivityLog;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Auth;

class ActivityLogger
{
    public static function log(string $event, string $description, ?array $properties = null, ?string $tenantId = null): ActivityLog
    {
        $causer = null;

        if (Auth::guard('admin')->check()) {
            $causer = Auth::guard('admin')->user();
        } elseif (Auth::guard('owner')->check()) {
            $causer = Auth::guard('owner')->user();
        } elseif (Auth::check()) {
            $causer = Auth::user();
        }

        // Auto-detect current active tenant if exists
        if (empty($tenantId)) {
            if (class_exists(Filament::class) && Filament::getTenant()) {
                $tenantId = Filament::getTenant()->getKey();
            } elseif (function_exists('tenant') && tenant()) {
                $tenantId = tenant()->id;
            }
        }

        return ActivityLog::create([
            'tenant_id' => $tenantId,
            'causer_type' => $causer ? get_class($causer) : null,
            'causer_id' => $causer ? $causer->getKey() : null,
            'event' => $event,
            'description' => $description,
            'properties' => $properties,
        ]);
    }
}
