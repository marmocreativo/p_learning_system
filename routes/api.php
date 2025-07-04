<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuariosController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\CuentasController;
use App\Http\Controllers\TemporadasController;
use App\Http\Controllers\PublicacionesController;
use App\Http\Controllers\SlidersController;
use App\Http\Controllers\SesionesController;
use App\Http\Controllers\TriviasController;
use App\Http\Controllers\JackpotsController;
use App\Http\Controllers\LogrosController;
use App\Http\Controllers\CanjeoController;
use App\Http\Controllers\SkuController;


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
/**
 * RUTAS API 2025
 * Estas rutas y consultas buscan ser más optimizadas además añadiré consultas a fechas de actualización para solo obtener datos importantes
 */
// SKUs
Route::get('skus', [SkuController::class, 'getSkus']);
Route::get('skus_busqueda', [SkuController::class, 'getSkusBusqueda']);
Route::get('skus_busqueda_filtrada', [SkuController::class, 'getSkusBusquedaFiltrada']);
Route::get('update_2025', [CuentasController::class, 'update_2025'])->name('update_2025.api');
Route::get('context_2025', [CuentasController::class, 'context_2025'])->name('context_2025.api');
Route::get('check_token_api', [LoginController::class, 'check_token_api'])->name('check_token.api');
Route::post('login_gate_2025', [LoginController::class, 'login_gate_2025_api'])->name('login_gate_2025.api');
Route::get('puntaje_usuario_2025', [UsuariosController::class, 'puntaje_usuario_2025'])->name('puntaje_usuario_2025.api');
Route::get('lista_logros_2025', [LogrosController::class, 'lista_logros_2025_api'])->name('lista_logros_2025.api');
Route::get('detalles_logro_2025', [LogrosController::class, 'detalles_logro_2025_api'])->name('detalles_logro_2025.api');
Route::get('temporada_y_sesiones_2025', [TemporadasController::class, 'temporada_y_sesiones_2025'])->name('temporada_y_sesiones_2025.api');
Route::get('full_datos_sesion_2025', [SesionesController::class, 'full_datos_sesion_2025_api'])->name('full_datos_sesion_2025.api');
Route::post('registro_clic_noticia', [UsuariosController::class, 'registro_clic_noticia_api'])->name('registro_clic_noticia.api');

// Aceptar términos y condiciones
Route::post('aceptar_terminos_2025', [UsuariosController::class, 'aceptar_terminos_2025'])->name('aceptar_terminos_2025.api');
// Trivias
Route::get('datos_trivia_2025', [TriviasController::class, 'datos_trivia_2025_api'])->name('datos_trivia_2025.api');
Route::post('registrar_visita_trivia', [TriviasController::class, 'registrar_visita_api'])->name('registrar_visita_trivia.api');
// Sesiones
Route::post('registrar_visita_sesion', [SesionesController::class, 'registrar_visita_api'])->name('registrar_visita_sesion.api');



// Rutas de datos de actividades
// Sesiones
Route::get('lista_sesiones', [SesionesController::class, 'lista_api'])->name('lista_sesiones.api');
Route::get('lista_sesiones_pendientes', [SesionesController::class, 'lista_pendientes_api'])->name('lista_temporadas.api');
Route::get('datos_sesion', [SesionesController::class, 'datos_sesion_api'])->name('datos_sesion.api');
Route::get('full_datos_sesion', [SesionesController::class, 'full_datos_sesion_api'])->name('full_datos_sesion.api');
Route::get('preguntas_sesion', [SesionesController::class, 'preguntas_sesion_api'])->name('preguntas_sesion.api');
Route::get('respuestas_sesion', [SesionesController::class, 'respuestas_sesion_api'])->name('respuestas_sesion.api');
Route::get('preguntas_y_respuestas_sesion', [SesionesController::class, 'preguntas_y_respuestas_sesion_api'])->name('preguntas_y_respuestas_sesion.api');
Route::get('dudas_sesion', [SesionesController::class, 'dudas_sesion_api'])->name('dudas_sesion.api');
Route::get('anexos_sesion', [SesionesController::class, 'anexos_sesion_api'])->name('anexos_sesion.api');
Route::get('checar_visualizacion', [SesionesController::class, 'checar_visualizacion_api'])->name('checar_visualizacion.api');
Route::get('checar_full_visualizacion', [SesionesController::class, 'checar_full_visualizacion_api'])->name('checar_full_visualizacion.api');
Route::post('registrar_inicio_video', [SesionesController::class, 'registrar_inicio_video_api'])->name('registrar_inicio_video.api');
Route::post('registrar_visualizacion', [SesionesController::class, 'registrar_visualizacion_api'])->name('registrar_visualizacion.api');
Route::post('registrar_avance', [SesionesController::class, 'registrar_avance_api'])->name('registrar_avance.api');
Route::post('registrar_respuestas_evaluacion', [SesionesController::class, 'registrar_respuestas_evaluacion_api'])->name('registrar_respuestas_evaluacion.api');
Route::post('registrar_duda', [SesionesController::class, 'registrar_duda_api'])->name('registrar_duda.api');
// trivia
Route::get('todos_datos_trivia', [TriviasController::class, 'todos_datos_trivia_api'])->name('todos_datos_trivia.api');
Route::get('datos_trivia', [TriviasController::class, 'datos_trivia_api'])->name('datos_trivia.api');
Route::post('registrar_respuestas_trivia', [TriviasController::class, 'registrar_respuestas_trivia_api'])->name('registrar_respuestas_trivia.api');
Route::post('direccion_trivia', [TriviasController::class, 'direccion_trivia_api'])->name('direccion_trivia.api');
Route::post('confirmar_direccion_trivia', [TriviasController::class, 'confirmar_direccion_trivia_api'])->name('confirmar_direccion_trivia.api');
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
Route::get('top_10_region', [TemporadasController::class, 'top_10_region_api'])->name('top_10_region.api');

Route::get('usuarios_suscritos', [UsuariosController::class, 'usuarios_suscritos_api'])->name('usuarios_suscritos.api');
Route::get('puntaje_usuario', [UsuariosController::class, 'puntaje_usuario_api'])->name('puntaje_usuario.api');
Route::get('detalles_puntaje_usuario', [UsuariosController::class, 'detalles_puntaje_usuario_api'])->name('detalles_puntaje_usuario.api');
Route::get('datos_lider', [UsuariosController::class, 'datos_lider_api'])->name('datos_lider.api');
Route::get('datos_basicos_lider', [UsuariosController::class, 'datos_basicos_lider_api'])->name('datos_basicos_lider.api');
Route::get('panel_lider', [UsuariosController::class, 'panel_lider_api'])->name('panel_lider.api');

Route::get('distribuidores_super_lider', [UsuariosController::class, 'distribuidores_super_lider_api'])->name('distribuidores_super_lider.api');
Route::get('datos_basicos_super_lider', [UsuariosController::class, 'datos_basicos_super_lider_api'])->name('datos_basicos_super_lider.api');
Route::get('datos_champions_super_lider', [UsuariosController::class, 'datos_champions_super_lider_api'])->name('datos_champions_super_lider.api');
Route::get('panel_super_lider', [UsuariosController::class, 'panel_super_lider_api'])->name('panel_super_lider.api');

Route::post('agregar_usuario', [UsuariosController::class, 'agregar_usuario_api'])->name('agregar_usuario.api');
Route::post('actualizar_usuario', [UsuariosController::class, 'actualizar_usuario_api'])->name('actualizar_usuario.api');
Route::post('eliminar_usuario', [UsuariosController::class, 'eliminar_usuario_api'])->name('eliminar_usuario.api');

Route::get('lista_logros', [LogrosController::class, 'lista_logros_api'])->name('lista_logros.api');
Route::get('detalles_logro', [LogrosController::class, 'detalles_logro_api'])->name('detalles_logro.api');
Route::post('participar_logro', [LogrosController::class, 'participar_logro_api'])->name('participar_logro.api');
Route::post('validar_logro', [LogrosController::class, 'validar_logro_api'])->name('validar_logro.api');
Route::post('subir_evidencia', [LogrosController::class, 'subir_evidencia_api'])->name('subir_evidencia.api');
Route::post('borrar_evidencia', [LogrosController::class, 'borrar_evidencia_api'])->name('borrar_evidencia.api');

Route::get('canje_inicio', [CanjeoController::class, 'canje_inicio_api'])->name('canje_inicio.api');
Route::get('detalles_producto', [CanjeoController::class, 'detalles_producto_api'])->name('detalles_producto.api');
Route::post('canje_checkout', [CanjeoController::class, 'canje_checkout_api'])->name('canje_checkout.api');
Route::get('detalles_transaccion', [CanjeoController::class, 'detalles_transaccion_api'])->name('detalles_transaccion.api');
Route::post('canje_checkout_actualizar', [CanjeoController::class, 'canje_checkout_actualizar_api'])->name('canje_checkout_actualizar.api');
Route::post('canje_checkout_confirmar', [CanjeoController::class, 'canje_checkout_confirmar_api'])->name('canje_checkout_confirmar.api');
Route::get('lista_transacciones', [CanjeoController::class, 'lista_transacciones_api'])->name('lista_transacciones.api');


Route::post('login', [LoginController::class, 'login_api'])->name('login.api');
Route::post('olvide_pass', [LoginController::class, 'olvide_pass_api'])->name('olvide_pass.api');
Route::post('restaurar_pass', [LoginController::class, 'restaurar_pass_api'])->name('restaurar_pass.api');
Route::post('login_suscripcion', [LoginController::class, 'login_suscripcion_api'])->name('login_suscripcion.api');
Route::post('login_gate', [LoginController::class, 'login_gate_api'])->name('login_gate.api');

Route::get('check', [LoginController::class, 'check_login_api'])->name('checklogin.api');
Route::get('full_check', [LoginController::class, 'full_check_api'])->name('full_check.api');
Route::get('full_check_puntaje', [LoginController::class, 'full_check_puntaje_api'])->name('full_check_puntaje.api');
Route::post('actualizar_usuario_perfil', [UsuariosController::class, 'actualizar_usuario_perfil_api'])->name('actualizar_usuario_perfil.api');
Route::post('actualizar_imagen_perfil', [UsuariosController::class, 'actualizar_imagen_perfil_api'])->name('actualizar_imagen_perfil.api');
Route::post('actualizar_pass_perfil', [UsuariosController::class, 'actualizar_pass_perfil_api'])->name('actualizar_pass_perfil.api');


Route::middleware('auth:sanctum')->group(function(){
    Route::get('logout', [LoginController::class, 'logout_api']);
    
    
});

