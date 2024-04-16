<?php

namespace App\Filament\Pages\Auth;

use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Auth\Register as AuthRegister;
use Filament\Pages\Page;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class Register extends AuthRegister
{
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                $this->getUsernameFormComponent(),
                $this->getNameFormComponent(),
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
            ])
            ->statePath('data');
    }

    protected function getUsernameFormComponent(): Component
    {
        return TextInput::make('username')
            ->label('Login')
            ->required()
            ->maxLength(255)
            ->autofocus();
    }

    protected function getPasswordFormComponent(): Component
    {
        return TextInput::make('password')
            ->label('Senha')
            ->password()
            ->required()
            ->rule('min:4')
            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
            ->same('passwordConfirmation')
            ->validationAttribute('senha');
    }
}
