<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Aqui podes definir as rotas para a API da tua aplicação. Estas rotas são
| carregadas automaticamente pelo RouteServiceProvider dentro de um grupo
| atribuído ao middleware "api".
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
