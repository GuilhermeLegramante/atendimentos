<?php

use Illuminate\Support\Facades\Route;
use Livewire\Livewire;

Livewire::setScriptRoute(function ($handle) {
    return Route::get('/atendimentos/public/livewire/livewire.js', $handle);
});

Livewire::setUpdateRoute(function ($handle) {
    return Route::post('/atendimentos/public/livewire/update', $handle);
});

/**
 * Ao trocar a senha do usuário, o Laravel exige um novo login.
 * Para isso, é necessário informar a rota de login
 */
Route::redirect('/atendimentos/public/admin/login', '/atendimentos/public/admin/login')->name('login');

Route::get('/', function () {
    return view('welcome');
});
