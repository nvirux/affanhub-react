<?php

namespace App\Filament\Merchant\Pages\Auth;

use App\Rules\TurnstileRule;
use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Forms\Components\ViewField;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Schema;

class Login extends BaseLogin
{
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getRememberFormComponent(),
                $this->getTurnstileFormComponent(),
            ]);
    }

    protected function getTurnstileFormComponent(): Component
    {
        return ViewField::make('turnstile_token')
            ->hiddenLabel()
            ->view('filament.components.turnstile')
            ->rule(new TurnstileRule('merchant_auth'))
            ->dehydrated(false);
    }
}
