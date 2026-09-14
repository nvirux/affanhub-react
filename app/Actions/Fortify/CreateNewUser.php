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

        // Normalize phone to 11 digits
        $phone = preg_replace('/[^0-9]/', '', $input['phone'] ?? '');
        if (str_starts_with($phone, '234') && strlen($phone) === 13) {
            $phone = '0'.substr($phone, 3);
        }
        $input['phone'] = $phone;

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class)->where(fn ($q) => $q->where('store_id', $tenantId)),
            ],
            'phone' => [
                'required',
                'string',
                'digits:11',
                Rule::unique(User::class)->where(fn ($q) => $q->where('store_id', $tenantId)),
            ],
        ];

        if (isset($input['pin']) || ! isset($input['password'])) {
            $rules['pin'] = ['required', 'digits:4', 'confirmed'];
        } else {
            $rules['password'] = $this->passwordRules();
        }

        Validator::make($input, $rules, [
            'name.required' => 'Please enter your full name.',
            'email.required' => 'Please enter your email address.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email address is already registered to an account.',
            'phone.required' => 'Please enter your phone number.',
            'phone.digits' => 'Phone number must be exactly 11 digits (e.g. 08012345678).',
            'phone.unique' => 'This phone number is already registered to an account.',
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
