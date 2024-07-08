<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\TreatmentController;
use App\Models\Signal;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;
use Symfony\Component\DomCrawler\Crawler;

Livewire::setScriptRoute(function ($handle) {
    return Route::get('/atendimentos/public/livewire/livewire.js', $handle);
});

Livewire::setUpdateRoute(function ($handle) {
    return Route::post('/atendimentos/public/livewire/update', $handle);
});

Route::middleware(['auth'])->group(function () {
    Route::get('/comprovante-de-atendimento/{treatmentId}', [TreatmentController::class, 'getReceipt'])->name('receipt-pdf');
});

/**
 * Ao trocar a senha do usuário, o Laravel exige um novo login.
 * Para isso, é necessário informar a rota de login
 */
Route::get('/login', function () {
    return redirect(route('filament.admin.auth.login'));
})->name('login');

Route::get('/', function () {
    return redirect(route('filament.admin.pages.dashboard'));
});

Route::get('/total-sinais', function () {
    // dd('Total cadastrado: ' . Signal::where('ok', 1)->count() . ' ainda faltam ' . Signal::count() - Signal::where('ok', 1)->count());
});

Route::get('/ajustar-sinais', function () {
    // // Primeiro, identificamos os nomes duplicados
    // $duplicatedNames = DB::table('signals')
    //     ->select('name')
    //     ->groupBy('name')
    //     ->havingRaw('COUNT(*) > 1')
    //     ->pluck('name');

    // // Em seguida, obtemos os registros mais recentes para esses nomes duplicados
    // $duplicatedSignals = DB::table('signals as s1')
    //     ->whereIn('name', $duplicatedNames)
    //     ->select('s1.*')
    //     ->whereRaw('s1.created_at = (SELECT MAX(s2.created_at) FROM signals as s2 WHERE s2.name = s1.name)')
    //     ->get();

    // dd($duplicatedSignals);
});

Route::get('/exclui-marcasOK', function () {
    set_time_limit(0);

    $file = file_get_contents('C:\Users\Marca & Sinal\Desktop\santa vitoria do palmar\backup_atualizado\deleted_brands.json');

    // Decodificar o conteúdo JSON em um array
    $data = json_decode($file, true);

    foreach (array_reverse($data) as $key => $value) {
        DB::connection('marcaesinal')
            ->table('agro_marca')
            ->where('id', $value["id"])
            ->delete();
    }

    dd('ok');
});

Route::get('/excluilocalidadesOK', function () {
    set_time_limit(0);

    $file = file_get_contents('C:\Users\Marca & Sinal\Desktop\santa vitoria do palmar\backup_atualizado\deleted_locales.json');

    // Decodificar o conteúdo JSON em um array
    $data = json_decode($file, true);
    DB::connection('marcaesinal')->statement('SET FOREIGN_KEY_CHECKS=0;');

    foreach (array_reverse($data) as $key => $value) {
        DB::connection('marcaesinal')
            ->table('agro_localidade')
            ->where('id', $value["id"])
            ->delete();
    }
    DB::connection('marcaesinal')->statement('SET FOREIGN_KEY_CHECKS=1;');

    dd('ok');
});

Route::get('/excluisinaisOK', function () {
    set_time_limit(0);

    $file = file_get_contents('C:\Users\Marca & Sinal\Desktop\santa vitoria do palmar\backup_atualizado\deleted_signals.json');

    // Decodificar o conteúdo JSON em um array
    $data = json_decode($file, true);

    foreach (array_reverse($data) as $key => $value) {
        DB::connection('marcaesinal')
            ->table('new_signals')
            ->where('id', $value["id"])
            ->delete();
    }

    dd('ok');
});

Route::get('/excluiprodutoresOK', function () {
    set_time_limit(0);

    $file = file_get_contents('C:\Users\Marca & Sinal\Desktop\santa vitoria do palmar\backup_atualizado\deleted_farmers.json');

    // Decodificar o conteúdo JSON em um array
    $data = json_decode($file, true);
    DB::connection('marcaesinal')->statement('SET FOREIGN_KEY_CHECKS=0;');

    foreach (array_reverse($data) as $key => $value) {
        DB::connection('marcaesinal')
            ->table('agro_propriedade')
            ->where('idprodutor', $value["id"])
            ->delete();

        DB::connection('marcaesinal')
            ->table('agro_produtor')
            ->where('id', $value["id"])
            ->delete();
    }

    DB::connection('marcaesinal')->statement('SET FOREIGN_KEY_CHECKS=1;');


    dd('ok');
});


Route::get('/ajuste-produtoresOK', function () {
    $file = file_get_contents('C:\Users\Marca & Sinal\Desktop\santa vitoria do palmar\backup_atualizado\script_produtores_ajuste.txt');

    $array = explode(PHP_EOL, $file); // Separa as linhas

    $deleted = [];
    $active = [];

    $farmer = [];

    foreach ($array as $key => $value) {
        $line = explode("\t", $value);

        $farmer['id'] = isset($line[0]) ? $line[0] : '';
        $farmer['name'] = isset($line[1]) ? $line[1] : '';
        $farmer['created_at'] = isset($line[7]) ? $line[7] : '';
        $farmer['deleted_at'] = isset($line[11]) ? $line[11] : "\N";

        if ($farmer['deleted_at'] != "\N") {
            $deleted[] = $farmer;
        } else {
            $active[] = $farmer;
        }
    }
    file_put_contents('C:\Users\Marca & Sinal\Desktop\santa vitoria do palmar\backup_atualizado\active_farmers.json', json_encode($active, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    file_put_contents('C:\Users\Marca & Sinal\Desktop\santa vitoria do palmar\backup_atualizado\deleted_farmers.json', json_encode($deleted, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

    dd('produtores ok');
});

Route::get('/ajuste-localidadesOK', function () {
    $file = file_get_contents('C:\Users\Marca & Sinal\Desktop\santa vitoria do palmar\backup_atualizado\script_localidades_ajuste.txt');

    $array = explode(PHP_EOL, $file); // Separa as linhas

    $deleted = [];
    $active = [];

    $locale = [];

    foreach ($array as $key => $value) {
        $line = explode("\t", $value);

        $locale['id'] = isset($line[0]) ? $line[0] : '';
        $locale['name'] = isset($line[1]) ? $line[1] : '';
        $locale['deleted_at'] = isset($line[5]) ? $line[5] : "\N";

        if ($locale['deleted_at'] != "\N") {
            $deleted[] = $locale;
        } else {
            $active[] = $locale;
        }
    }
    file_put_contents('C:\Users\Marca & Sinal\Desktop\santa vitoria do palmar\backup_atualizado\active_locales.json', json_encode($active, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    file_put_contents('C:\Users\Marca & Sinal\Desktop\santa vitoria do palmar\backup_atualizado\deleted_locales.json', json_encode($deleted, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

    dd('localidades ok');
});

Route::get('/ajuste-marcasOK', function () {
    $file = file_get_contents('C:\Users\Marca & Sinal\Desktop\santa vitoria do palmar\backup_atualizado\script_marcas_ajuste.txt');

    $array = explode(PHP_EOL, $file); // Separa as linhas

    $deletedBrands = [];
    $activeBrands = [];

    $brand = [];

    foreach ($array as $key => $value) {
        $line = explode("\t", $value);

        $brand['id'] = isset($line[0]) ? $line[0] : '';
        $brand['number'] = isset($line[1]) ? $line[1] : '';
        $brand['farmer_id'] = isset($line[2]) ? $line[2] : '';
        $brand['path'] = isset($line[4]) ? $line[4] : '';
        $brand['created_at'] = isset($line[5]) ? $line[5] : '';
        $brand['deleted_at'] = isset($line[12]) ? $line[12] : "\N";

        if ($brand['deleted_at'] != "\N") {
            $deletedBrands[] = $brand;
        } else {
            $activeBrands[] = $brand;
        }
    }
    file_put_contents('C:\Users\Marca & Sinal\Desktop\santa vitoria do palmar\backup_atualizado\active_brands.json', json_encode($activeBrands, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    file_put_contents('C:\Users\Marca & Sinal\Desktop\santa vitoria do palmar\backup_atualizado\deleted_brands.json', json_encode($deletedBrands, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

    dd('marcas ok');
});

Route::get('/ajuste-sinaisOK', function () {
    $file = file_get_contents('C:\Users\Marca & Sinal\Desktop\santa vitoria do palmar\backup_atualizado\script_sinais_ajuste.txt');

    $array = explode(PHP_EOL, $file); // Separa as linhas

    $deleted = [];
    $active = [];

    $signal = [];

    foreach ($array as $key => $value) {
        $line = explode("\t", $value);

        $signal['id'] = isset($line[0]) ? $line[0] : '';
        $signal['number'] = isset($line[1]) ? $line[1] : '';
        $signal['farmer_id'] = isset($line[2]) ? $line[2] : '';
        $signal['path'] = isset($line[4]) ? $line[4] : '';
        $signal['created_at'] = isset($line[5]) ? $line[5] : '';
        $signal['deleted_at'] = isset($line[9]) ? $line[9] : "\N";

        if ($signal['deleted_at'] != "\N") {
            $deleted[] = $signal;
        } else {
            $active[] = $signal;
        }
    }
    file_put_contents('C:\Users\Marca & Sinal\Desktop\santa vitoria do palmar\backup_atualizado\active_signals.json', json_encode($active, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    file_put_contents('C:\Users\Marca & Sinal\Desktop\santa vitoria do palmar\backup_atualizado\deleted_signals.json', json_encode($deleted, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

    dd('sinais ok');
});

Route::get('/ver-json', function () {
    $file = file_get_contents('C:\Users\Marca & Sinal\Desktop\santa vitoria do palmar\backup_atualizado\active_farmers.json');

    // Decodificar o conteúdo JSON em um array
    $data = json_decode($file, true);

    // Exibir o conteúdo usando dd
    dd(array_reverse($data));
});


Route::get('/salvar-sinais-tabela-nova', function () {
    // Salvar os sinais dos produtores na tabela new_signals no banco quente de santa vitória
    // set_time_limit(0);
    // $file = file_get_contents('https://sisprem-atendimentos.hardsoftsistemas.com/storage/sinais.csv');

    // $array = explode(PHP_EOL, $file);

    // $signals = [];

    // foreach ($array as $key => $value) {
    //     $exploit = explode(';', $value);

    //     if (isset($exploit[0]) && isset($exploit[1])) {
    //         $signal['id'] = $exploit[0];
    //         $signal['farmerId'] = $exploit[1];
    //         $signal['path'] = $exploit[3];

    //         array_push($signals, $signal);
    //     }
    // }

    // foreach ($signals as $key => $signal) {
    //     if ($signal['id'] > 5685) {
    //         DB::connection('marcaesinal')
    //             ->table('new_signals')
    //             ->insertGetId([
    //                 'id' => $signal['id'],
    //                 'farmer_id' => $signal['farmerId'],
    //                 'filename' => $signal['path'],
    //                 'path' => 'https://santa-vitoria-do-palmar.marcaesinal.com/storage/sinais/sinais/sinais_png/' . $signal['path'],
    //             ]);
    //     }
    // }

    // dd('salvou os sinais');
});

Route::get('/sinais', function () {
    set_time_limit(0);

    $file = file_get_contents('https://sisprem-atendimentos.hardsoftsistemas.com/storage/sinais.csv');

    $array = explode(PHP_EOL, $file);

    $signals = [];

    foreach ($array as $key => $value) {
        $exploit = explode(';', $value);

        if (isset($exploit[0]) && isset($exploit[1])) {
            $signal['id'] = $exploit[0];
            $signal['farmerId'] = $exploit[1];
            $signal['path'] = $exploit[3];

            array_push($signals, $signal);
        }
    }

    foreach ($signals as $key => $signal) {
        if ($signal['id'] > 1) {
            // $farmer = DB::connection('marcaesinal')->table('agro_produtor')
            //     ->join('hscad_cadmunicipal', 'hscad_cadmunicipal.inscricaomunicipal', '=', 'agro_produtor.idmunicipe')
            //     ->select(
            //         'hscad_cadmunicipal.nome AS name',
            //     )
            //     ->where('id', $signal['farmerId'])->get()->first();

            // if (isset($farmer)) {
            //     DB::table('signals')
            //         ->insertGetId([
            //             'id' => $signal['id'],
            //             'name' => $farmer->name,
            //             'path' => 'https://santa-vitoria-do-palmar.marcaesinal.com/storage/sinais/sinais/sinais_png/' . $signal['path'],
            //         ]);
            // }

            // DB::table('signals')
            //     ->where('id', $signal['id'])
            //     ->update(
            //         [
            //             'path' => $signal['path']
            //         ]
            //     );
        }
    }

    dd('editou os sinais');
});


Route::get('/sync-data', [ApiController::class, 'syncData']);

Route::get('/service-value', [ApiController::class, 'serviceValue']);

Route::get('/crawler', function () {
    // $response = Http::post('https://www.cavalocrioulo.org.br/pesquisa/pesquisar_nome_home.php', [
    //     'form_params' => [
    //         'nome' => 'zagaia ituzaingo'
    //     ],
    // ]);

    // $html = (string) $response->getBody();

    // $crawler = new Crawler($html);

    // dd($crawler);
});
