<?php

namespace App\Filament\Resources\TreatmentResource\Pages;

use App\Filament\Resources\TreatmentResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageTreatments extends ManageRecords
{
    protected static string $resource = TreatmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->mutateFormDataUsing(function (array $data): array {
                    return $data;
                }),
        ];
    }
}
