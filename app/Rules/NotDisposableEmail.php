<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class NotDisposableEmail implements ValidationRule
{
    /**
     * Common disposable / throwaway email providers.
     *
     * @var array<string>
     */
    protected static array $disposableDomains = [
        'tempmail.com',
        'tempmail.net',
        'temp-mail.org',
        'temp-mail.io',
        'guerrillamail.com',
        'guerrillamail.net',
        'guerrillamail.biz',
        'guerrillamail.org',
        'guerrillamailblock.com',
        'sharklasers.com',
        'grr.la',
        '10minutemail.com',
        '10minutemail.net',
        '10minutemail.org',
        'mailinator.com',
        'mailinator2.com',
        'throwawaymail.com',
        'fakemailgenerator.com',
        'yopmail.com',
        'yopmail.net',
        'yopmail.fr',
        'dispostable.com',
        'trashmail.com',
        'trashmail.net',
        'getairmail.com',
        'crazymailing.com',
        'mohmal.com',
        'maildrop.cc',
        'inboxkitten.com',
        'mytemp.email',
        'generator.email',
        'emailondeck.com',
        'tempail.com',
        'burners.me',
        'dropmail.me',
        'getnada.com',
        'nada.ltd',
        'tmail.ws',
        'tmpmail.net',
        'tmpmail.org',
        'disposablemail.com',
    ];

    /**
     * Run the validation rule.
     *
     * @param  Closure(string, ?string=): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value) || ! filter_var($value, FILTER_VALIDATE_EMAIL)) {
            return;
        }

        $parts = explode('@', strtolower(trim($value)));
        $domain = end($parts);

        if (in_array($domain, static::$disposableDomains, true)) {
            $fail('Please use a permanent email address (e.g. Gmail, Yahoo, Outlook).');
        }
    }
}
