<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class PlatformSetting extends Model
{
    protected $table = 'platform_settings';

    protected $fillable = [
        'key',
        'value',
        'type',
        'description',
    ];

    /**
     * Get a setting by key with caching.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        return Cache::remember("platform_setting_{$key}", 3600, function () use ($key, $default) {
            $setting = static::where('key', $key)->first();

            if (! $setting || $setting->value === null) {
                return $default;
            }

            return match ($setting->type) {
                'boolean', 'bool' => filter_var($setting->value, FILTER_VALIDATE_BOOLEAN),
                'integer', 'int' => (int) $setting->value,
                'float', 'double' => (float) $setting->value,
                'json', 'array' => json_decode($setting->value, true) ?? $default,
                default => (string) $setting->value,
            };
        });
    }

    /**
     * Set a setting value and clear cache.
     */
    public static function set(string $key, mixed $value, ?string $type = null, ?string $description = null): static
    {
        $detectedType = $type;
        if (! $detectedType) {
            if (is_bool($value)) {
                $detectedType = 'boolean';
            } elseif (is_int($value)) {
                $detectedType = 'integer';
            } elseif (is_float($value)) {
                $detectedType = 'float';
            } elseif (is_array($value)) {
                $detectedType = 'json';
            } else {
                $detectedType = 'string';
            }
        }

        $storedValue = is_array($value) ? json_encode($value) : (string) $value;

        $setting = static::updateOrCreate(
            ['key' => $key],
            [
                'value' => $storedValue,
                'type' => $detectedType,
                'description' => $description ?? $key,
            ]
        );

        Cache::forget("platform_setting_{$key}");

        return $setting;
    }
}
