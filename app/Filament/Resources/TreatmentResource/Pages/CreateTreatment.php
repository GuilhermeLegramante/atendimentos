<?php

namespace App\Filament\Resources\ClientResource\Pages;

use App\Filament\Resources\ClientResource;
use App\Filament\Resources\TreatmentResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreateTreatment extends CreateRecord
{
    protected static string $resource = TreatmentResource::class;

    protected static ?string $navigationLabel = 'Criar Atendimento';

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->user()->id;
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return route('receipt-pdf', ['treatmentId' => $this->getRecord()->id]);
    }
}
