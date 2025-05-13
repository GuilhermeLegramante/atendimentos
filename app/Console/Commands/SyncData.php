<?php

namespace App\Console\Commands;

use App\Models\Person;
use App\Models\Service;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SyncData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
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

        Log::info('Dados sincronizados: ' . now()->format('d/m/Y H:i:s'));
    }
}
