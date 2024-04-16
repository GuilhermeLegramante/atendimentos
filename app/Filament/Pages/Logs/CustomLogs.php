<?php

namespace App\Filament\Pages\Logs;

use FilipFonal\FilamentLogManager\Pages\Logs;

class CustomLogs extends Logs
{
    public static function getNavigationGroup(): ?string
    {
        return 'Configurações';
    }

    public static function canAccess(): bool
    {
        return auth()->user()->is_admin;
    }
}
