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

        // Exiba uma notificação de sucesso
        Notification::make()
            ->title('Sucesso')
            ->body('Dados sincronizados com sucesso!')
            ->success()
            ->send();

        return Redirect::route('filament.admin.pages.dashboard');
    }

    protected function processData()
    {
        set_time_limit(0);

        $url = 'http://45.4.20.6:8080/web/contracheque/public/';

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
                }
            }
        }

        foreach ($people->json() as $value) {
            if (isset($value['registration'])) {
                Person::updateOrCreate(
                    ['registration' => $value['registration']], // Condição de busca
                    [
                        'name' => $value['name'],
                        'cpf_cnpj' => $value['cpfCnpj'],
                        'is_active' => $value['isActive'],
                        'partner' => $value['conveniado'],
                        'patient' => $value['segurado'],
                        'dependent' => $value['dependente'],
                    ] // Dados para criação ou atualização
                );
            }
        }
    }
}
