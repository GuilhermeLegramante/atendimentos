<?php

namespace App\Http\Controllers;

use App\Models\Person;
use App\Models\Service;
use App\Models\Treatment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ApiController extends Controller
{
    public function getTreatments()
    {
        return Treatment::with('patient', 'partner', 'providedServices', 'providedServices.service')
            // ->where('ok', false)
            ->paginate(100);
    }

    public function syncData()
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
                    if ($service->value != $value['value']) { // Caso o valor esteja diferente, atualiza o serviço
                        Service::where('code', $value['code'])
                            ->update(
                                ['value' => $value['value']]
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
    }

    /**
     * @param $date (data do atendimento)
     * @param $serviceCode (codigo do serviço)
     * @param $type (0 = segurado, 1 = dependente)
     * @param $citizenId (id do munícipe)
     */
    public function serviceValue()
    {
        $url = 'http://45.4.21.126:8080/web/contracheque/public/valor-servico?date=2024-06-01&serviceCode=9900020010&type=0&citizenId=2';

        $service = Http::get($url);

        dd($service->json());
    }
}
