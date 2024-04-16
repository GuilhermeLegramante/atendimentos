<?php

namespace App\Filament\Pages\Auth;

use Filament\Forms\Components\Component;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Auth\EditProfile as BaseEditProfile;
use Illuminate\Support\Facades\Hash;

class EditProfile extends BaseEditProfile
{
    protected static string $view = 'pages.auth.edit-profile';

    protected static string $layout = 'filament-panels::components.layout.index';


    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informações do Usuário')
                    ->aside()
                    ->schema([
                        TextInput::make('username')
                            ->label('Login')
                            ->required()
                            ->maxLength(255),
                        $this->getNameFormComponent(),
                        $this->getEmailFormComponent(),
                        $this->getPasswordFormComponent(),
                        $this->getPasswordConfirmationFormComponent(),
                    ])
            ]);
    }

    public static function getSlug(): string
    {
        return "perfil";
    }

    protected function hasFullWidthFormActions(): bool
    {
        return false;
    }

    protected function getPasswordFormComponent(): Component
    {
        return TextInput::make('password')
            ->label(__('filament-panels::pages/auth/edit-profile.form.password.label'))
            ->password()
            ->rule('min:4')
            ->autocomplete('new-password')
            ->dehydrated(fn ($state): bool => filled($state))
            ->dehydrateStateUsing(fn ($state): string => Hash::make($state))
            ->live()
            ->same('passwordConfirmation');
    }

}
