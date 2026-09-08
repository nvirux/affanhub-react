<?php

namespace App\Services\Branding;

class ColorHelper
{
    /**
     * Curated fintech color presets.
     */
    public const PRESETS = [
        'sapphire' => [
            'name' => 'Sapphire Blue',
            'hex' => '#2563EB',
            'description' => 'Trustworthy & modern fintech blue',
        ],
        'emerald' => [
            'name' => 'Emerald Green',
            'hex' => '#10B981',
            'description' => 'Vibrant telecom & financial green',
        ],
        'indigo' => [
            'name' => 'Royal Indigo',
            'hex' => '#6366F1',
            'description' => 'Deep tech & premium digital purple',
        ],
        'sunset' => [
            'name' => 'Sunset Orange',
            'hex' => '#F97316',
            'description' => 'Energetic & bold customer brand',
        ],
        'crimson' => [
            'name' => 'Crimson Ruby',
            'hex' => '#E11D48',
            'description' => 'Striking & high-impact scarlet',
        ],
        'teal' => [
            'name' => 'Deep Teal',
            'hex' => '#0D9488',
            'description' => 'Clean, professional & calming teal',
        ],
        'bronze' => [
            'name' => 'Rich Bronze',
            'hex' => '#D97706',
            'description' => 'Warm, regal gold & bronze',
        ],
        'charcoal' => [
            'name' => 'Midnight Charcoal',
            'hex' => '#1E293B',
            'description' => 'Sleek, minimalist dark slate',
        ],
    ];

    /**
     * Default platform primary color.
     */
    public const DEFAULT_HEX = '#2563EB';

    /**
     * Maximum allowed luminance (colors brighter than this are rejected, e.g. white or pale pastels).
     */
    public const MAX_LUMINANCE = 0.82;

    /**
     * Luminance threshold above which the text foreground switches from white to dark slate.
     */
    public const CONTRAST_THRESHOLD = 0.45;

    /**
     * Normalize a hex color string to uppercase with a leading '#'.
     */
    public static function normalizeHex(?string $hex): string
    {
        if (blank($hex)) {
            return self::DEFAULT_HEX;
        }

        $hex = trim($hex);
        if (! str_starts_with($hex, '#')) {
            $hex = '#'.$hex;
        }

        return strtoupper($hex);
    }

    /**
     * Check if a string is a valid 6-character hex color (e.g. #2563EB).
     */
    public static function isValidHex(?string $hex): bool
    {
        if (blank($hex)) {
            return false;
        }

        $normalized = self::normalizeHex($hex);

        return (bool) preg_match('/^#([A-F0-9]{6})$/', $normalized);
    }

    /**
     * Calculate relative luminance according to WCAG 2.1 specifications.
     * Value ranges from 0.0 (darkest black) to 1.0 (lightest white).
     */
    public static function getLuminance(string $hex): float
    {
        $normalized = self::normalizeHex($hex);

        if (! self::isValidHex($normalized)) {
            return 0.5;
        }

        $r = hexdec(substr($normalized, 1, 2)) / 255;
        $g = hexdec(substr($normalized, 3, 2)) / 255;
        $b = hexdec(substr($normalized, 5, 2)) / 255;

        $rLinear = ($r <= 0.03928) ? ($r / 12.92) : pow(($r + 0.055) / 1.055, 2.4);
        $gLinear = ($g <= 0.03928) ? ($g / 12.92) : pow(($g + 0.055) / 1.055, 2.4);
        $bLinear = ($b <= 0.03928) ? ($b / 12.92) : pow(($b + 0.055) / 1.055, 2.4);

        return (0.2126 * $rLinear) + (0.7152 * $gLinear) + (0.0722 * $bLinear);
    }

    /**
     * Check if a color is allowed as a primary brand color.
     * Rejects pure white, near-white, or excessively light colors that break UI contrast.
     */
    public static function isAllowed(?string $hex): bool
    {
        if (! self::isValidHex($hex)) {
            return false;
        }

        $luminance = self::getLuminance($hex);

        return $luminance < self::MAX_LUMINANCE;
    }

    /**
     * Determine optimal foreground text color (White vs Dark Slate) for a given background.
     */
    public static function getContrastForeground(?string $hex): string
    {
        if (! self::isValidHex($hex)) {
            return '#FFFFFF';
        }

        $luminance = self::getLuminance($hex);

        // If luminance is above threshold, use dark slate text (#0F172A), otherwise white (#FFFFFF)
        return $luminance > self::CONTRAST_THRESHOLD ? '#0F172A' : '#FFFFFF';
    }

    /**
     * Get all curated presets.
     */
    public static function getPresets(): array
    {
        return self::PRESETS;
    }

    /**
     * Find if a given hex matches an existing preset key.
     */
    public static function findMatchingPreset(?string $hex): ?string
    {
        $normalized = self::normalizeHex($hex);

        foreach (self::PRESETS as $key => $preset) {
            if (strtoupper($preset['hex']) === $normalized) {
                return $key;
            }
        }

        return null;
    }
}
