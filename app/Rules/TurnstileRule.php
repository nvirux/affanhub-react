<?php

namespace App\Rules;

use App\Services\Security\TurnstileService;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class TurnstileRule implements ValidationRule
{
    public bool $implicit = true;

    public function __construct(
        protected ?string $expectedAction = null
    ) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Bypass during automated testing unless testing turnstile specifically
        if (app()->environment('testing') && ! config('services.turnstile.force_testing', false)) {
            return;
        }

        $token = is_string($value) ? trim($value) : null;

        if (empty($token)) {
            $fail('Please complete the security check to continue.');

            return;
        }

        $service = app(TurnstileService::class);

        if (! $service->verify($token, request()->ip(), $this->expectedAction)) {
            $fail('Security verification failed or expired. Please refresh and try again.');
        }
    }
}
