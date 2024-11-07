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
        return Treatment::with('patient', 'partner', 'providedServices', 'providedServices.service')->paginate(50);
    }

    public function syncData()
    {
        $url = 'http://45.4.21.126:8080/web/contracheque/public/';

        $services = Http::get($url . 'servicos');

        dd($services);

        $people = Http::get($url . 'pessoas');

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
            if (isset($value['inscricao'])) {
                $person = Person::where('registration', $value['inscricao'])->get()->first();

                if (!isset($person)) {
                    Person::create([
                        'registration' => $value['inscricao'],
                        'name' => $value['nome'],
                        'partner' => $value['conveniado'],
                        'patient' => $value['segurado'],
                        'dependent' => $value['dependente'],
                    ]);
                }
            }
        }

        dd('Dados sincronizados com sucesso!');
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
