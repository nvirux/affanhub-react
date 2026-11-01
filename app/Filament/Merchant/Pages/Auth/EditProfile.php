<?php

namespace App\Filament\Merchant\Pages\Auth;

use Filament\Auth\Pages\EditProfile as BaseEditProfile;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Schema;

class EditProfile extends BaseEditProfile
{
    protected function getEmailFormComponent(): Component
    {
        return TextInput::make('email')
            ->label('Email Address')
            ->disabled()
            ->dehydrated(false)
            ->helperText('Email address cannot be changed directly for security reasons. Please contact support to update your email.');
    }

    protected function getPhoneFormComponent(): Component
    {
        return TextInput::make('phone')
            ->label('Phone Number')
            ->tel()
            ->required()
            ->length(11)
            ->rules(['digits:11', 'regex:/^0[789][01]\d{8}$/'])
            ->validationMessages([
                'digits' => 'Phone number must be exactly 11 digits.',
                'regex' => 'Please enter a valid 11-digit Nigerian phone number (e.g. 08012345678).',
            ])
            ->unique(ignoreRecord: true);
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
                $this->getCurrentPasswordFormComponent(),
            ]);
    }
}
