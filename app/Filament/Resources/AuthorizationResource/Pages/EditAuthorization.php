<?php

namespace App\Filament\Resources\AuthorizationResource\Pages;

use App\Filament\Resources\AuthorizationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Exceptions\Halt;
use Filament\Support\Facades\FilamentView;

class EditAuthorization extends EditRecord
{
    protected static string $resource = AuthorizationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
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
        return AuthorizationResource::getUrl('index');
    }

    public function save(bool $shouldRedirect = true): void
    {
        $this->authorizeAccess();

        try {
            $this->callHook('beforeValidate');

            $data = $this->form->getState();

            $this->callHook('afterValidate');

            $data = $this->mutateFormDataBeforeSave($data);

            // Sincroniza os serviços para evitar duplicidade
            if (!empty($data['services']) && is_array($data['services'])) {
                $this->record->services()->sync($data['services']);
            }

            $this->callHook('beforeSave');

            $this->handleRecordUpdate($this->getRecord(), $data);

            $this->callHook('afterSave');
        } catch (Halt $exception) {
            return;
        }

        $this->rememberData();

        $this->getSavedNotification()?->send();

        if ($shouldRedirect && ($redirectUrl = $this->getRedirectUrl())) {
            $this->redirect($redirectUrl, navigate: FilamentView::hasSpaMode() && is_app_url($redirectUrl));
        }
    }
}
