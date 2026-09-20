<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreMobileApp extends Model
{
    protected $table = 'store_mobile_apps';

    protected $fillable = [
        'store_id',
        'app_name',
        'package_id',
        'app_icon_path',
        'version_code',
        'version_name',
        'apk_download_url',
        'aab_download_url',
        'status',
        'price_paid',
        'include_playstore',
        'playstore_status',
        'playstore_paid',
        'playstore_url',
        'custom_keystore_path',
        'custom_keystore_alias',
        'custom_keystore_password',
        'github_run_id',
        'failure_reason',
        'last_built_at',
    ];

    protected $casts = [
        'version_code' => 'integer',
        'price_paid' => 'decimal:2',
        'playstore_paid' => 'decimal:2',
        'include_playstore' => 'boolean',
        'last_built_at' => 'datetime',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function isBuilding(): bool
    {
        return $this->status === 'building';
    }

    public function isReady(): bool
    {
        return $this->status === 'ready' && ! empty($this->apk_download_url);
    }

    public function isPlayStoreRequested(): bool
    {
        return $this->include_playstore || in_array($this->playstore_status, ['pending_submission', 'submitted', 'published']);
    }

    public function isPlayStorePublished(): bool
    {
        return $this->playstore_status === 'published';
    }

    public function incrementVersion(): void
    {
        $this->version_code = ($this->version_code ?? 0) + 1;

        $parts = explode('.', $this->version_name ?? '1.0.0');
        $patch = isset($parts[2]) ? ((int) $parts[2] + 1) : 1;
        $this->version_name = ($parts[0] ?? '1').'.'.($parts[1] ?? '0').'.'.$patch;
    }

    public static function generatePackageId(string $slug): string
    {
        return static::generateUniquePackageId($slug);
    }

    public static function generateUniquePackageId(string $name, ?int $storeId = null): string
    {
        $clean = preg_replace('/[^a-z0-9]/', '', strtolower($name));
        $base = 'com.affanhub.'.($clean ?: 'store');

        $packageId = $base;
        $counter = 1;

        while (static::where('package_id', $packageId)
            ->when($storeId, fn ($q) => $q->where('store_id', '!=', $storeId))
            ->exists()) {
            $packageId = "{$base}{$counter}";
            $counter++;
        }

        return $packageId;
    }
}
