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
Route::get('preguntas_sesion', [SesionesController::class, 'preguntas_sesion_api'])->name('preguntas_sesion.api');
Route::get('respuestas_sesion', [SesionesController::class, 'respuestas_sesion_api'])->name('respuestas_sesion.api');
Route::get('dudas_sesion', [SesionesController::class, 'dudas_sesion_api'])->name('dudas_sesion.api');
Route::get('anexos_sesion', [SesionesController::class, 'anexos_sesion_api'])->name('anexos_sesion.api');
Route::get('checar_visualizacion', [SesionesController::class, 'checar_visualizacion_api'])->name('checar_visualizacion.api');
Route::post('registrar_visualizacion', [SesionesController::class, 'registrar_visualizacion_api'])->name('registrar_visualizacion.api');
Route::post('registrar_respuestas_evaluacion', [SesionesController::class, 'registrar_respuestas_evaluacion_api'])->name('registrar_respuestas_evaluacion.api');
Route::post('registrar_duda', [SesionesController::class, 'registrar_duda_api'])->name('registrar_duda.api');
// trivia
Route::get('todos_datos_trivia', [TriviasController::class, 'todos_datos_trivia_api'])->name('todos_datos_trivia.api');
Route::get('datos_trivia', [TriviasController::class, 'datos_trivia_api'])->name('datos_trivia.api');
Route::post('registrar_respuestas_trivia', [TriviasController::class, 'registrar_respuestas_trivia_api'])->name('registrar_respuestas_trivia.api');
// Jackpot
Route::get('todos_datos_jackpot', [JackpotsController::class, 'todos_datos_jackpot_api'])->name('todos_datos_jackpot.api');
Route::get('datos_jackpot', [JackpotsController::class, 'datos_jackpot_api'])->name('datos_jackpot.api');
Route::get('preguntas_jackpot', [JackpotsController::class, 'preguntas_jackpot_api'])->name('preguntas_jackpot.api');
Route::get('respuestas_jackpot', [JackpotsController::class, 'respuestas_jackpot_api'])->name('respuestas_jackpot.api');
Route::get('intentos_jackpot', [JackpotsController::class, 'intentos_jackpot_api'])->name('intentos_jackpot.api');
Route::post('registrar_respuestas_jackpot', [JackpotsController::class, 'registrar_respuestas_jackpot_api'])->name('registrar_respuestas_jackpot.api');
Route::post('registrar_intento_jackpot', [JackpotsController::class, 'registrar_intento_jackpot_api'])->name('registrar_intento_jackpot.api');
// Páginas
Route::get('lista_publicaciones', [PublicacionesController::class, 'lista_api'])->name('lista_publicaciones.api');
Route::get('datos_publicacion', [PublicacionesController::class, 'datos_publicacion_api'])->name('datos_publicacion.api');
// sliders
Route::get('lista_sliders', [SlidersController::class, 'lista_api'])->name('lista_sliders.api');
Route::get('datos_slider', [SlidersController::class, 'datos_slider_api'])->name('datos_slider.api');


Route::get('lista_temporadas', [TemporadasController::class, 'lista_api'])->name('lista_temporadas.api');
Route::get('datos_temporada', [TemporadasController::class, 'show_api'])->name('datos_temporada.api');
Route::get('temporada_y_sesiones', [TemporadasController::class, 'temporada_y_sesiones'])->name('temporada_y_sesiones.api');

Route::get('usuarios_suscritos', [UsuariosController::class, 'usuarios_suscritos_api'])->name('usuarios_suscritos.api');
Route::get('puntaje_usuario', [UsuariosController::class, 'puntaje_usuario_api'])->name('puntaje_usuario.api');
Route::get('datos_lider', [UsuariosController::class, 'datos_lider_api'])->name('datos_lider.api');
Route::get('datos_basicos_lider', [UsuariosController::class, 'datos_basicos_lider_api'])->name('datos_basicos_lider.api');

Route::post('login', [LoginController::class, 'login_api'])->name('login.api');

Route::get('check', [LoginController::class, 'check_login_api'])->name('checklogin.api');
Route::get('full_check', [LoginController::class, 'full_check_api'])->name('full_check.api');

Route::middleware('auth:sanctum')->group(function(){
    Route::get('logout', [LoginController::class, 'logout_api']);
    
    
});

