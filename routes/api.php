<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuariosController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\TemporadasController;
use App\Http\Controllers\PublicacionesController;
use App\Http\Controllers\SlidersController;
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
// Páginas
Route::get('lista_publicaciones', [PublicacionesController::class, 'lista_api'])->name('lista_publicaciones.api');
Route::get('datos_publicacion', [PublicacionesController::class, 'datos_publicacion_api'])->name('datos_publicacion.api');
// sliders
Route::get('lista_sliders', [SlidersController::class, 'lista_api'])->name('lista_sliders.api');
Route::get('datos_slider', [SlidersController::class, 'datos_slider_api'])->name('datos_slider.api');


Route::get('lista_temporadas', [TemporadasController::class, 'lista_api'])->name('lista_temporadas.api');
Route::get('datos_temporada', [TemporadasController::class, 'show_api'])->name('datos_temporada.api');

Route::get('usuarios_suscritos', [UsuariosController::class, 'usuarios_suscritos_api'])->name('usuarios_suscritos.api');

Route::post('login', [LoginController::class, 'login_api'])->name('login.api');

Route::get('check', [LoginController::class, 'check_login_api'])->name('checklogin.api');

Route::middleware('auth:sanctum')->group(function(){
    Route::get('logout', [LoginController::class, 'logout_api']);
    
});

