<?php

namespace App\Filament\Merchant\Pages\Auth;

use Filament\Actions\Action;
use Filament\Auth\Pages\Register as BaseRegister;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Schema;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;

class Register extends BaseRegister
{
    public function getHeading(): string|Htmlable
    {
        return 'Create your Merchant Account';
    }

    public function getSubheading(): string|Htmlable|null
    {
        return 'Start vending data, airtime, and utility bills under your own brand.';
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getNameFormComponent(),
                $this->getEmailFormComponent(),
                $this->getPhoneFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
                $this->getTermsFormComponent(),
            ]);
    }

    protected function getNameFormComponent(): Component
    {
        return TextInput::make('name')
            ->label('Full Name')
            ->required()
            ->maxLength(255)
            ->placeholder('e.g. Al-Amin Abdullahi')
            ->autofocus();
    }

    protected function getEmailFormComponent(): Component
    {
        return TextInput::make('email')
            ->label('Email Address')
            ->email()
            ->required()
            ->maxLength(255)
            ->unique($this->getUserModel())
            ->placeholder('alamin@example.com');
    }

    protected function getPhoneFormComponent(): Component
    {
        return TextInput::make('phone')
            ->label('Phone Number')
            ->tel()
            ->required()
            ->maxLength(15)
            ->unique($this->getUserModel())
            ->placeholder('08012345678')
            ->helperText('Used for important security and account recovery notifications.');
    }

    protected function getTermsFormComponent(): Component
    {
        return Checkbox::make('terms')
            ->label(new HtmlString('I agree to the <a href="/terms" target="_blank" style="color: #d97706; text-decoration: underline; font-weight: 600;">Terms of Service</a> and <a href="/privacy" target="_blank" style="color: #d97706; text-decoration: underline; font-weight: 600;">Privacy Policy</a>.'))
            ->accepted()
            ->validationMessages([
                'accepted' => 'You must accept the terms of service to create your account.',
            ])
            ->dehydrated(false);
    }

    public function getRegisterFormAction(): Action
    {
        return Action::make('register')
            ->label('Create Account')
            ->submit('register');
    }
}
