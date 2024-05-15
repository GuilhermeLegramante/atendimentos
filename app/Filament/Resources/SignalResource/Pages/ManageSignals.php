<?php

namespace App\Filament\Resources\SignalResource\Pages;

use App\Filament\Resources\SignalResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageSignals extends ManageRecords
{
    protected static string $resource = SignalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
