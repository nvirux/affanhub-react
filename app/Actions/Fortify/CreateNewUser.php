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
        $tenantId = (function_exists('tenant') && tenant()) ? tenant('id') : ($input['store_id'] ?? null);

        $rules = [
            ...$this->profileRules(),
            'phone' => ['required', 'string', 'max:20', Rule::unique(User::class)],
        ];

        if (isset($input['pin']) || ! isset($input['password'])) {
            $rules['pin'] = ['required', 'digits:4', 'confirmed'];
        } else {
            $rules['password'] = $this->passwordRules();
        }

        Validator::make($input, $rules, [
            'pin.digits' => 'Your Login PIN must be exactly 4 digits.',
            'pin.confirmed' => 'The Login PIN confirmation does not match.',
        ])->validate();

        $hasPin = isset($input['pin']) && ! empty($input['pin']);

        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'phone' => $input['phone'],
            'password' => $hasPin ? null : ($input['password'] ?? null),
            'login_pin_hash' => $hasPin ? $input['pin'] : null,
            'login_pin_enabled' => $hasPin,
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
