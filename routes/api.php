<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\TemporadasController;
use App\Http\Controllers\SesionesController;
use App\Http\Controllers\TriviasController;
use App\Http\Controllers\JackpotsController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('prueba', function () {
    return "Esta es la API de prueba";
});

// Rutas de datos de actividades
// Sesiones
Route::get('lista_sesiones', [SesionesController::class, 'lista_api'])->name('lista_sesiones.api');
Route::get('lista_sesiones_pendientes', [SesionesController::class, 'lista_pendientes_api'])->name('lista_temporadas.api');
Route::get('datos_sesion', [SesionesController::class, 'datos_sesion_api'])->name('datos_sesion.api');
// trivia
Route::get('datos_trivia', [TriviasController::class, 'datos_trivia_api'])->name('datos_trivia.api');
// Jackpot
Route::get('datos_jackpot', [JackpotsController::class, 'datos_jackpot_api'])->name('datos_jackpot.api');


Route::get('lista_temporadas', [TemporadasController::class, 'lista_api'])->name('lista_temporadas.api');
Route::get('datos_temporada', [TemporadasController::class, 'show_api'])->name('datos_temporada.api');

Route::post('login', [LoginController::class, 'login_api'])->name('login.api');

Route::middleware('auth:sanctum')->group(function(){
    Route::get('logout', [LoginController::class, 'logout_api']);
    Route::get('check', [LoginController::class, 'check_login_api'])->name('checklogin.api');
});

