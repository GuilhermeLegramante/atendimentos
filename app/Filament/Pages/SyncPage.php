<?php

namespace App\Filament\Pages;

use App\Models\Person;
use App\Models\Service;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;

class SyncPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-arrow-path';

    protected static string $view = 'filament.pages.sync-page';

    protected static ?string $title = 'Sincronizar Dados';

    protected static ?string $slug = 'sincronizar-dados';

    public static function getNavigationGroup(): ?string
    {
        return 'Configurações';
    }

    public function mount()
    {
        // Adicione sua lógica de processamento aqui
        $this->processData();

        $this->linkPartnersToAdmins();

        // Exiba uma notificação de sucesso
        Notification::make()
            ->title('Sucesso')
            ->body('Dados sincronizados com sucesso!')
            ->success()
            ->send();


        return Redirect::route('filament.admin.pages.dashboard');
    }

    protected function linkPartnersToAdmins()
    {
        // Pega todos os IDs dos usuários administradores
        $adminUserIds = DB::table('users')
            ->where('is_admin', true)
            ->pluck('id');

        // Pega todos os IDs das pessoas
        $peopleIds = DB::table('people')->pluck('id');

        // Faz o vínculo evitando duplicatas
        foreach ($adminUserIds as $userId) {
            foreach ($peopleIds as $peopleId) {
                $exists = DB::table('user_people')
                    ->where('user_id', $userId)
                    ->where('person_id', $peopleId)
                    ->exists();

                if (! $exists) {
                    DB::table('user_people')->insert([
                        'user_id' => $userId,
                        'person_id' => $peopleId,
                    ]);
                }
            }
        }
    }

    protected function processData()
    {
        set_time_limit(0);

        $url = 'https://sisprem.hardsoftsfa.com.br/web/contracheque/public/';

        $services = Http::timeout(30)->get($url . 'servicos');

        $people = Http::timeout(30)->get($url . 'pessoas');

        foreach ($services->json() as $value) {
            if (isset($value['code']) && isset($value['name'])) {
                $service = Service::where('code', $value['code'])->get()->first();

                if (!isset($service)) {
                    Service::create([
                        'code' => $value['code'],
                        'name' => $value['name'],
                        'value' => (float) $value['value'],
                        'titular_value' => (float) $value['titularValue'],
                        'dependent_value' => (float) $value['dependentValue'],
                        'created_at' => now()
                    ]);
                } else {
                    if ($service->value != $value['value'] || $service->name != $value['name']) { // Caso o valor esteja diferente, atualiza o serviço
                        Service::where('code', $value['code'])
                            ->update(
                                [
                                    'code' => $value['code'],
                                    'name' => $value['name'],
                                    'value' => (float) $value['value'],
                                    'titular_value' => (float) $value['titularValue'],
                                    'dependent_value' => (float) $value['dependentValue'],
                                    'updated_at' => now()
                                ]
                            );
                    }
                }
            }
        }

        foreach ($people->json() as $value) {
            if (isset($value['registration'])) {
               $isActive = (int) ($value['seguradoAtivo'] || $value['dependenteAtivo']);

                Person::updateOrCreate(
                    ['registration' => $value['registration']], // Condição de busca
                    [
                        'name' => $value['name'],
                        'cpf_cnpj' => $value['cpfCnpj'],
                        'is_active' => $isActive,
                        'partner' => $value['conveniado'],
                        'patient' => $value['segurado'],
                        'dependent' => $value['dependente'],
                    ] // Dados para criação ou atualização
                );
            }
        }
    }
}
