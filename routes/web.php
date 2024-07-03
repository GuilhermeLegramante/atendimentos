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
    dd('Total cadastrado: ' . Signal::where('ok', 1)->count() . ' ainda faltam ' . Signal::count() - Signal::where('ok', 1)->count());
});

Route::get('/ajustar-sinais', function () {
    // Primeiro, identificamos os nomes duplicados
    $duplicatedNames = DB::table('signals')
        ->select('name')
        ->groupBy('name')
        ->havingRaw('COUNT(*) > 1')
        ->pluck('name');

    // Em seguida, obtemos os registros mais recentes para esses nomes duplicados
    $duplicatedSignals = DB::table('signals as s1')
        ->whereIn('name', $duplicatedNames)
        ->select('s1.*')
        ->whereRaw('s1.created_at = (SELECT MAX(s2.created_at) FROM signals as s2 WHERE s2.name = s1.name)')
        ->get();

    dd($duplicatedSignals);
});

Route::get('/salvar-sinais-tabela-nova', function () {
    // Salvar os sinais dos produtores na tabela new_signals no banco quente de santa vitória
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
            DB::connection('marcaesinal')
                ->table('new_signals')
                ->insertGetId([
                    'id' => $signal['id'],
                    'farmer_id' => $signal['farmerId'],
                    'filename' => $signal['path'],
                    'path' => 'https://santa-vitoria-do-palmar.marcaesinal.com/storage/sinais/sinais/sinais_png/' . $signal['path'],
                ]);
        }
    }

    dd('salvou os sinais');
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
