<?php

namespace App\Actions\Fortify;

use App\Concerns\PasswordValidationRules;
use App\Concerns\ProfileValidationRules;
use App\Models\User;
use App\Services\ReferralService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules, ProfileValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            ...$this->profileRules(),
            'phone' => ['required', 'string', 'max:20', Rule::unique(User::class)],
            'password' => $this->passwordRules(),
        ])->validate();

        $tenantId = (function_exists('tenant') && tenant()) ? tenant('id') : ($input['store_id'] ?? null);

        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'phone' => $input['phone'],
            'password' => $input['password'],
            'store_id' => $tenantId,
        ]);

        $user->ensureReferralCode();

        $refCode = $input['ref'] ?? $input['referral_code'] ?? request('ref') ?? request('referral_code');
        if (! empty($refCode)) {
            app(ReferralService::class)->recordRegistration($user, (string) $refCode);
        }

        return $user;
    }
}
