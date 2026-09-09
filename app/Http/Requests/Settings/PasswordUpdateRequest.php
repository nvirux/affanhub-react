<?php

namespace App\Http\Requests\Settings;

use App\Concerns\PasswordValidationRules;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;

class PasswordUpdateRequest extends FormRequest
{
    use PasswordValidationRules;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $user = $this->user();

        $rules = [
            'password' => $this->passwordRules(),
        ];

        if ($user && ! $user->hasPassword()) {
            $rules['login_pin'] = ['required', 'digits:4'];
        } else {
            $rules['current_password'] = $this->currentPasswordRules();
        }

        return $rules;
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $user = $this->user();
            if ($user && ! $user->hasPassword() && $this->filled('login_pin')) {
                if (! $user->hasLoginPin() || ! Hash::check($this->login_pin, $user->login_pin_hash)) {
                    $validator->errors()->add('login_pin', __('The provided Login PIN is incorrect.'));
                }
            }
        });
    }
}
