<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\TreatmentController;
use App\Models\Person;
use App\Models\Signal;
use App\Models\User;
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
    Route::get('/relatorio-atendimentos', [TreatmentController::class, 'treatmentsReport'])->name('treatments-report-pdf');
    Route::get('/modelo-relatorio-atendimentos', [TreatmentController::class, 'treatmentsListModel'])->name('treatments-list-model-pdf');
    Route::get('/guia-tratamento-odontologico', [TreatmentController::class, 'dentalTreatmentGuide'])->name('dental-treatment-guide-pdf');
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

Route::get('/salvar-conveniados', function () {
    $userId = 5; // Substitua pelo ID do usuário específico

    $partners = Person::where('partner', 1)->get();

    foreach ($partners as $key => $partner) {
        // Encontrar o usuário específico
        $user = User::find($userId);

        if ($user) {
            // Verificar se o relacionamento entre o usuário e o partner já existe
            $existingRelation = $user->partners()->where('person_id', $partner->id)->exists();

            if (!$existingRelation) {
                // Se o relacionamento não existe, adicionar o partner
                $user->partners()->attach($partner->id);
            }
        }
    }

    dd('Feito!');
});

Route::get('/sync-data', [ApiController::class, 'syncData']);

Route::get('/service-value', [ApiController::class, 'serviceValue']);
