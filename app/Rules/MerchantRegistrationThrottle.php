<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\RateLimiter;

class MerchantRegistrationThrottle implements ValidationRule
{
    public function __construct(
        protected int $maxAttempts = 3,
        protected int $decaySeconds = 86400
    ) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $ip = request()->ip() ?: '127.0.0.1';
        $throttleKey = 'merchant_register_ip:'.$ip;

        if (RateLimiter::tooManyAttempts($throttleKey, $this->maxAttempts)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            $hours = max(1, (int) ceil($seconds / 3600));

            $fail("Too many accounts have been created from your network. Please try again in {$hours} hour(s) or contact support.");
        }
    }
}
