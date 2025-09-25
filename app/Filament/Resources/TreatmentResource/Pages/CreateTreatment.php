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

    protected function afterCreate(): void
    {
        $partner = \App\Models\Person::find($this->record->partner_id);
        $patient = \App\Models\Person::find($this->record->patient_id);

        foreach ($this->record->providedServices as $item) {
            // Recarrega o serviço
            $service = \App\Models\Service::find($item->service_id);

            // Só atualiza se o conveniado NÃO pode editar valores
            if (!$partner || !$partner->can_edit_values) {
                $defaultValue = $service->value;

                $item->value = $defaultValue;

                $total = (float) $defaultValue * (float) $item->quantity;

                $percentual = $patient && $patient->dependent == 1
                    ? $service->dependent_value
                    : $service->titular_value;

                $patientValue = ($total * $percentual) / 100;
                $item->patient_value = number_format($patientValue, 2, '.', '');

                $item->save();
            }
        }
    }

    protected function getRedirectUrl(): string
    {
        return route('receipt-pdf', ['treatmentId' => $this->getRecord()->id]);
    }
}
