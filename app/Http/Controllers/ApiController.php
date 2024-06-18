<?php

namespace App\Http\Controllers;

use App\Models\Person;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ApiController extends Controller
{
    public function syncData()
    {
        $url = 'http://45.4.21.126:8080/web/contracheque/public/';

        $services = Http::get($url . 'servicos');

        $people = Http::get($url . 'pessoas');

        foreach ($services->json() as $value) {
            if (isset($value['code']) && isset($value['name'])) {
                $service = Service::where('code', $value['code'])->get()->first();

                if (!isset($service)) {
                    Service::create($value);
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
}
