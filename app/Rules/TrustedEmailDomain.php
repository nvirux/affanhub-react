<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class TrustedEmailDomain implements ValidationRule
{
    /**
     * Allowed trusted email providers.
     *
     * @var array<string>
     */
    protected static array $trustedDomains = [
        'gmail.com',
        'googlemail.com',
        'yahoo.com',
        'yahoo.co.uk',
        'ymail.com',
        'outlook.com',
        'hotmail.com',
        'live.com',
        'msn.com',
        'icloud.com',
        'me.com',
        'proton.me',
        'protonmail.com',
    ];

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value) || ! filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $fail('Please enter a valid email address.');

            return;
        }

        // Disallow quotes or escape characters often used by automated fuzzers
        if (str_contains($value, '"') || str_contains($value, "'") || str_contains($value, '\\')) {
            $fail('Please enter a standard email address without quotation marks or special characters.');

            return;
        }

        $parts = explode('@', strtolower(trim($value)));
        $domain = end($parts);

        if (! in_array($domain, static::$trustedDomains, true)) {
            $fail('Please use a trusted email provider (Gmail, Yahoo, Outlook, Hotmail, or iCloud). Temporary, custom, or unverified email domains are not allowed.');
        }
    }
}
