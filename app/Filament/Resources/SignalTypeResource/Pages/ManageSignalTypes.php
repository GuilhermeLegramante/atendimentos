<?php

namespace App\Filament\Resources\SignalTypeResource\Pages;

use App\Filament\Resources\SignalTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageSignalTypes extends ManageRecords
{
    protected static string $resource = SignalTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
