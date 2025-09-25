<?php

namespace App\Filament\Resources\AuthorizationResource\Pages;

use App\Filament\Resources\AuthorizationResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Exceptions\Halt;
use Filament\Support\Facades\FilamentView;

class CreateAuthorization extends CreateRecord
{
    protected static string $resource = AuthorizationResource::class;

    protected static bool $canCreateAnother = false;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (!empty($data['services_selected'])) {
            $pivotData = [];

            foreach ($data['services_selected'] as $serviceSelected) {
                if (!empty($serviceSelected['service_id'])) {
                    $pivotData[$serviceSelected['service_id']] = [
                        'status' => isset($serviceSelected['status']) ? (int) $serviceSelected['status'] : 0
                    ];
                }
            }

            $data['services'] = $pivotData;
        }

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        // return route('authorization-pdf', ['id' => $this->getRecord()->id]);
        return AuthorizationResource::getUrl('index');
    }

    public function create(bool $another = false): void
    {
        $this->authorizeAccess();

        try {
            $this->callHook('beforeValidate');

            $data = $this->form->getState();

            $this->callHook('afterValidate');

            $data = $this->mutateFormDataBeforeCreate($data);

            $this->callHook('beforeCreate');

            $this->record = $this->handleRecordCreation($data);

            // 🚀 Aqui é onde salvamos o pivot corretamente
            if (!empty($data['services']) && is_array($data['services'])) {
                $this->record->services()->attach($data['services']);
            }

            $this->callHook('afterCreate');
        } catch (Halt $exception) {
            return;
        }

        $this->rememberData();

        $this->getCreatedNotification()?->send();

        if ($another) {
            // Ensure that the form record is anonymized so that relationships aren't loaded.
            $this->form->model($this->getRecord()::class);
            $this->record = null;

            $this->fillForm();

            return;
        }

        $redirectUrl = $this->getRedirectUrl();

        $this->redirect($redirectUrl, navigate: FilamentView::hasSpaMode() && is_app_url($redirectUrl));
    }
}
