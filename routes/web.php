<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CuentasController;
use App\Http\Controllers\DistribuidoresController;
use App\Http\Controllers\TemporadasController;
use App\Http\Controllers\PublicacionesController;
use App\Http\Controllers\SlidersController;
use App\Http\Controllers\NotificacionesController;
use App\Http\Controllers\SesionesController;
use App\Http\Controllers\TriviasController;
use App\Http\Controllers\JackpotsController;
use App\Http\Controllers\UsuariosController;
use App\Http\Controllers\ConfiguracionesController;
use App\Http\Controllers\ClasesController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogrosController;
use App\Http\Controllers\CsvController;
use App\Http\Controllers\CanjeoController;
use App\Http\Controllers\PopupsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [CuentasController::class, 'index'])->middleware('auth')->name('home');
Route::get('scripts_ajustes', [FrontController::class, 'scripts_ajustes'])->name('scripts.ajustes');
Route::get('relacion_suscripciones', [FrontController::class, 'relacion_suscripciones'])->name('relacion_suscripciones');
Route::get('eliminar_suscripciones_duplicadas', [FrontController::class, 'eliminar_suscripciones_duplicadas'])->name('eliminar_suscripciones_duplicadas');
Route::get('migrar', [FrontController::class, 'migrar'])->name('migrar');

Route::get('admin', [CuentasController::class, 'index'])->middleware('auth')->name('admin');
Route::get('admin/base_de_datos', [AdminController::class, 'base_de_datos'])->middleware('auth')->name('admin.base_de_datos');
Route::get('admin/backup', [AdminController::class, 'backup'])->name('admin.backup');
Route::get('admin/enviar_correo', [AdminController::class, 'enviarCorreo'])->name('admin.enviarCorreo');

Route::get('admin/logros/reporte', [LogrosController::class, 'reporte'])->name('logros.reporte');


// Rutas Login
Route::get('login', [LoginController::class, 'login_form'])->name('login');
Route::post('login/verificar', [LoginController::class, 'login_verificar'])->name('login.verificar');
Route::get('login/registro_form', [LoginController::class, 'registro_form'])->name('login.registro_form');
Route::post('login/registro', [LoginController::class, 'registro'])->name('login.registro');
Route::get('login/logout', [LoginController::class, 'logout'])->name('login.logout');


// Rutas CRUD para las'configuraciones'
Route::get('admin/configuraciones', [ConfiguracionesController::class, 'index'])->middleware('auth')->name('configuraciones');
Route::get('admin/configuraciones/create', [ConfiguracionesController::class, 'create'])->middleware('auth')->name('configuraciones.create');
Route::post('admin/configuraciones/store', [ConfiguracionesController::class, 'store'])->middleware('auth')->name('configuraciones.store');
Route::get('admin/configuraciones/{post}', [ConfiguracionesController::class, 'show'])->middleware('auth')->name('configuraciones.show');
Route::get('admin/configuraciones/edit/{post}', [ConfiguracionesController::class, 'edit'])->middleware('auth')->name('configuraciones.edit');
Route::put('admin/configuraciones/update/{post}', [ConfiguracionesController::class, 'update'])->middleware('auth')->name('configuraciones.update');
Route::delete('admin/configuraciones/destroy/{post}', [ConfiguracionesController::class, 'destroy'])->middleware('auth')->name('configuraciones.destroy');

// Rutas CRUD para las'clases'
Route::get('admin/clases', [ClasesController::class, 'index'])->middleware('auth')->name('clases');
Route::get('admin/clases/create', [ClasesController::class, 'create'])->middleware('auth')->name('clases.create');
Route::post('admin/clases/store', [ClasesController::class, 'store'])->middleware('auth')->name('clases.store');
Route::get('admin/clases/{post}', [ClasesController::class, 'show'])->middleware('auth')->name('clases.show');
Route::get('admin/clases/edit/{post}', [ClasesController::class, 'edit'])->middleware('auth')->name('clases.edit');
Route::put('admin/clases/update/{post}', [ClasesController::class, 'update'])->middleware('auth')->name('clases.update');
Route::delete('admin/clases/destroy/{post}', [ClasesController::class, 'destroy'])->middleware('auth')->name('clases.destroy');



// Rutas CRUD para las'cuentas'
Route::get('admin/cuentas', [CuentasController::class, 'index'])->middleware('auth')->name('cuentas');
Route::get('admin/cuentas/create', [CuentasController::class, 'create'])->middleware('auth')->name('cuentas.create');
Route::post('admin/cuentas/store', [CuentasController::class, 'store'])->middleware('auth')->name('cuentas.store');
Route::get('admin/cuentas/{post}', [CuentasController::class, 'show'])->middleware('auth')->name('cuentas.show');
Route::get('admin/cuentas/edit/{post}', [CuentasController::class, 'edit'])->middleware('auth')->name('cuentas.edit');
Route::put('admin/cuentas/update/{post}', [CuentasController::class, 'update'])->middleware('auth')->name('cuentas.update');
Route::delete('admin/cuentas/destroy/{post}', [CuentasController::class, 'destroy'])->middleware('auth')->name('cuentas.destroy');

// Rutas CRUD para las'distribuidores'
Route::get('admin/distribuidores', [DistribuidoresController::class, 'index'])->middleware('auth')->name('distribuidores');
Route::get('admin/distribuidores/create', [DistribuidoresController::class, 'create'])->middleware('auth')->name('distribuidores.create');
Route::post('admin/distribuidores/store', [DistribuidoresController::class, 'store'])->middleware('auth')->name('distribuidores.store');
Route::get('admin/distribuidores/{post}', [DistribuidoresController::class, 'show'])->middleware('auth')->name('distribuidores.show');
Route::get('admin/distribuidores/edit/{post}', [DistribuidoresController::class, 'edit'])->middleware('auth')->name('distribuidores.edit');
Route::put('admin/distribuidores/update/{post}', [DistribuidoresController::class, 'update'])->middleware('auth')->name('distribuidores.update');
Route::delete('admin/distribuidores/destroy/{post}', [DistribuidoresController::class, 'destroy'])->middleware('auth')->name('distribuidores.destroy');
Route::get('admin/distribuidores_suscritos', [DistribuidoresController::class, 'distribuidores_suscritos'])->middleware('auth')->name('distribuidores.suscritos');
Route::get('admin/distribuidores_suscritos/suscripcion', [DistribuidoresController::class, 'suscripcion'])->middleware('auth')->name('distribuidores_suscritos.suscripcion');
Route::post('admin/distribuidores_suscritos/suscribir', [DistribuidoresController::class, 'suscribir'])->middleware('auth')->name('distribuidores_suscritos.suscribir');
Route::delete('admin/distribuidores_suscritos/desuscribir/{post}', [DistribuidoresController::class, 'desuscribir'])->middleware('auth')->name('distribuidores_suscritos.desuscribir');
Route::post('admin/distribuidores/crear_sucursal', [DistribuidoresController::class, 'crear_sucursal'])->middleware('auth')->name('distribuidores.crear_sucursal');
Route::delete('admin/distribuidores/borrar_sucursal/{post}', [DistribuidoresController::class, 'borrar_sucursal'])->middleware('auth')->name('distribuidores.borrar_sucursal');
// Rutas CRUD para las'temporadas'
Route::get('admin/temporadas', [TemporadasController::class, 'index'])->middleware('auth')->name('temporadas');
Route::get('admin/temporadas/create', [TemporadasController::class, 'create'])->middleware('auth')->name('temporadas.create');
Route::post('admin/temporadas/store', [TemporadasController::class, 'store'])->middleware('auth')->name('temporadas.store');
Route::get('admin/temporadas/reporte_excel/{post}', [TemporadasController::class, 'reporte_excel'])->name('temporadas.reporte_excel');
Route::get('admin/temporadas/reporte/{post}', [TemporadasController::class, 'reporte'])->middleware('auth')->name('temporadas.reporte');
Route::get('admin/temporadas/estadísticas/{id}', [TemporadasController::class, 'estadisticas'])->middleware('auth')->name('temporadas.estadisticas');
Route::get('admin/temporadas/{post}', [TemporadasController::class, 'show'])->middleware('auth')->name('temporadas.show');
Route::get('admin/temporadas/edit/{post}', [TemporadasController::class, 'edit'])->middleware('auth')->name('temporadas.edit');
Route::put('admin/temporadas/update/{post}', [TemporadasController::class, 'update'])->middleware('auth')->name('temporadas.update');
Route::delete('admin/temporadas/destroy/{post}', [TemporadasController::class, 'destroy'])->middleware('auth')->name('temporadas.destroy');
Route::get('admin/top_10_region', [TemporadasController::class, 'top_10_region'])->name('top_10_region');
Route::get('admin/top_10_borrar_corte', [TemporadasController::class, 'top_10_borrar_corte'])->name('top_10_borrar_corte');
Route::post('admin/actualizar_premio_top_10', [TemporadasController::class, 'actualizar_premio_top_10'])->name('actualizar_premio_top_10');

// Rutas CRUD para las'publicaciones'
Route::get('admin/publicaciones', [PublicacionesController::class, 'index'])->middleware('auth')->name('publicaciones');
Route::get('admin/publicaciones/create', [PublicacionesController::class, 'create'])->middleware('auth')->name('publicaciones.create');
Route::post('admin/publicaciones/store', [PublicacionesController::class, 'store'])->middleware('auth')->name('publicaciones.store');
Route::get('admin/publicaciones/{post}', [PublicacionesController::class, 'show'])->middleware('auth')->name('publicaciones.show');
Route::get('admin/publicaciones/edit/{post}', [PublicacionesController::class, 'edit'])->middleware('auth')->name('publicaciones.edit');
Route::put('admin/publicaciones/update/{post}', [PublicacionesController::class, 'update'])->middleware('auth')->name('publicaciones.update');
Route::delete('admin/publicaciones/destroy/{post}', [PublicacionesController::class, 'destroy'])->middleware('auth')->name('publicaciones.destroy');

// Rutas CRUD para las'sesiones'
Route::get('admin/sesiones', [SesionesController::class, 'index'])->middleware('auth')->name('sesiones');
Route::get('admin/sesiones/completadas', [SesionesController::class, 'completadas'])->middleware('auth')->name('sesiones.completadas');
Route::get('admin/sesiones/reporte_completadas/{post}', [SesionesController::class, 'reporte_completadas'])->middleware('auth')->name('sesiones.reporte_completadas');
Route::get('admin/sesiones/reporte_completadas_excel', [SesionesController::class, 'reporte_completadas_excel'])->middleware('auth')->name('sesiones.reporte_completadas_excel');
Route::get('admin/sesiones/create', [SesionesController::class, 'create'])->middleware('auth')->name('sesiones.create');
Route::post('admin/sesiones/store', [SesionesController::class, 'store'])->middleware('auth')->name('sesiones.store');
Route::post('admin/sesiones/store_pregunta', [SesionesController::class, 'store_pregunta'])->middleware('auth')->name('sesiones.store_pregunta');
Route::get('admin/sesiones/reparar/{post}', [SesionesController::class, 'reparar'])->middleware('auth')->name('sesiones.reparar');
Route::get('admin/sesiones/resultados_excel', [SesionesController::class, 'resultados_excel'])->middleware('auth')->name('sesiones.resultados_excel');
Route::get('admin/sesiones/{post}', [SesionesController::class, 'show'])->middleware('auth')->name('sesiones.show');
Route::get('admin/sesiones/resultados/{post}', [SesionesController::class, 'resultados'])->middleware('auth')->name('sesiones.resultados');
Route::get('admin/sesiones/dudas/{post}', [SesionesController::class, 'dudas'])->middleware('auth')->name('sesiones.dudas');
Route::put('admin/sesiones/dudas/edit/{post}', [SesionesController::class, 'dudas_edit'])->middleware('auth')->name('sesiones.dudas_edit');
Route::delete('admin/sesiones/dudas/destroy/{post}', [SesionesController::class, 'destroy_dudas'])->middleware('auth')->name('sesiones.destroy_dudas');

Route::get('admin/sesiones/edit/{post}', [SesionesController::class, 'edit'])->middleware('auth')->name('sesiones.edit');
Route::put('admin/sesiones/update_pregunta/{post}', [SesionesController::class, 'update_pregunta'])->middleware('auth')->name('sesiones.update_pregunta');
Route::put('admin/sesiones/update/{post}', [SesionesController::class, 'update'])->middleware('auth')->name('sesiones.update');
Route::delete('admin/sesiones/destroy_pregunta/{post}', [SesionesController::class, 'destroy_pregunta'])->middleware('auth')->name('sesiones.destroy_pregunta');
Route::delete('admin/sesiones/destroy_visualizacion{post}', [SesionesController::class, 'destroy_visualizacion'])->middleware('auth')->name('sesiones.destroy_visualizacion');
Route::delete('admin/sesiones/destroy_respuesta{post}', [SesionesController::class, 'destroy_respuesta'])->middleware('auth')->name('sesiones.destroy_respuesta');
Route::delete('admin/sesiones/destroy/{post}', [SesionesController::class, 'destroy'])->middleware('auth')->name('sesiones.destroy');

// Rutas CRUD para las'trivias'
Route::get('admin/trivias', [TriviasController::class, 'index'])->middleware('auth')->name('trivias');
Route::get('admin/trivias/create', [TriviasController::class, 'create'])->middleware('auth')->name('trivias.create');
Route::post('admin/trivias/store_pregunta', [TriviasController::class, 'store_pregunta'])->middleware('auth')->name('trivias.store_pregunta');
Route::post('admin/trivias/store', [TriviasController::class, 'store'])->middleware('auth')->name('trivias.store');
Route::get('admin/trivias/resultados_excel', [TriviasController::class, 'resultados_excel'])->middleware('auth')->name('trivias.resultados_excel');
Route::get('admin/trivias/resultados/{post}', [TriviasController::class, 'resultados'])->middleware('auth')->name('trivias.resultados');
Route::get('admin/trivias/{post}', [TriviasController::class, 'show'])->middleware('auth')->name('trivias.show');
Route::get('admin/trivias/edit/{post}', [TriviasController::class, 'edit'])->middleware('auth')->name('trivias.edit');
Route::put('admin/trivias/update_pregunta/{post}', [TriviasController::class, 'update_pregunta'])->middleware('auth')->name('trivias.update_pregunta');
Route::put('admin/trivias/update/{post}', [TriviasController::class, 'update'])->middleware('auth')->name('trivias.update');
Route::delete('admin/trivias/destroy_pregunta/{post}', [TriviasController::class, 'destroy_pregunta'])->middleware('auth')->name('trivias.destroy_pregunta');
Route::delete('admin/trivias/destroy_participacion', [TriviasController::class, 'destroy_participacion'])->middleware('auth')->name('trivias.destroy_participacion');
Route::delete('admin/trivias/destroy_ganador{post}', [TriviasController::class, 'destroy_ganador'])->middleware('auth')->name('trivias.destroy_ganador');
Route::delete('admin/trivias/destroy_respuesta{post}', [TriviasController::class, 'destroy_respuesta'])->middleware('auth')->name('trivias.destroy_respuesta');
Route::delete('admin/trivias/destroy/{post}', [TriviasController::class, 'destroy'])->middleware('auth')->name('trivias.destroy');

// Rutas CRUD para las'jackpots'
Route::get('admin/jackpots', [JackpotsController::class, 'index'])->middleware('auth')->name('jackpots');
Route::get('admin/jackpots/create', [JackpotsController::class, 'create'])->middleware('auth')->name('jackpots.create');
Route::post('admin/jackpots/store_pregunta', [JackpotsController::class, 'store_pregunta'])->middleware('auth')->name('jackpots.store_pregunta');
Route::post('admin/jackpots/store', [JackpotsController::class, 'store'])->middleware('auth')->name('jackpots.store');
Route::get('admin/jackpots/resultados_excel', [JackpotsController::class, 'resultados_excel'])->middleware('auth')->name('jackpots.resultados_excel');
Route::get('admin/jackpots/{post}', [JackpotsController::class, 'show'])->middleware('auth')->name('jackpots.show');
Route::get('admin/jackpots/resultados/{post}', [JackpotsController::class, 'resultados'])->middleware('auth')->name('jackpots.resultados');
Route::get('admin/jackpots/edit/{post}', [JackpotsController::class, 'edit'])->middleware('auth')->name('jackpots.edit');
Route::put('admin/jackpots/update_pregunta/{post}', [JackpotsController::class, 'update_pregunta'])->middleware('auth')->name('jackpots.update_pregunta');
Route::put('admin/jackpots/update/{post}', [JackpotsController::class, 'update'])->middleware('auth')->name('jackpots.update');
Route::delete('admin/jackpots/destroy/{post}', [JackpotsController::class, 'destroy'])->middleware('auth')->name('jackpots.destroy');
Route::delete('admin/jackpots/destroy_pregunta/{post}', [JackpotsController::class, 'destroy_pregunta'])->middleware('auth')->name('jackpots.destroy_pregunta');
Route::delete('admin/jackpots/destroy_intento/{post}', [JackpotsController::class, 'destroy_intento'])->middleware('auth')->name('jackpots.destroy_intento');
Route::delete('admin/jackpots/destroy_respuesta/{post}', [JackpotsController::class, 'destroy_respuesta'])->middleware('auth')->name('jackpots.destroy_respuesta');

// Rutas CRUD para las'logros'
Route::get('admin/logros', [LogrosController::class, 'index'])->middleware('auth')->name('logros');
Route::get('admin/logros/create', [LogrosController::class, 'create'])->middleware('auth')->name('logros.create');
Route::get('admin/logros/detalles_participacion', [LogrosController::class, 'detalles_participacion'])->middleware('auth')->name('logros.detalles_participacion');
Route::post('admin/logros/store', [LogrosController::class, 'store'])->middleware('auth')->name('logros.store');
Route::get('admin/logros/{post}', [LogrosController::class, 'show'])->middleware('auth')->name('logros.show');
Route::get('admin/logros/edit/{post}', [LogrosController::class, 'edit'])->middleware('auth')->name('logros.edit');
Route::put('admin/logros/update/{post}', [LogrosController::class, 'update'])->middleware('auth')->name('logros.update');
Route::put('admin/logros/participacion_update/{post}', [LogrosController::class, 'participacion_update'])->middleware('auth')->name('logros.participacion_update');
Route::delete('admin/logros/destroy/{post}', [LogrosController::class, 'destroy'])->middleware('auth')->name('logros.destroy');
Route::delete('admin/logros/destroy_participacion/{post}', [LogrosController::class, 'destroy_participacion'])->middleware('auth')->name('logros.destroy_participacion');
Route::delete('admin/logros/destroy_anexo/{post}', [LogrosController::class, 'destroy_anexo'])->middleware('auth')->name('logros.destroy_anexo');
Route::put('admin/logros/actualizar_anexo/{post}', [LogrosController::class, 'actualizar_anexo'])->middleware('auth')->name('logros.actualizar_anexo');


// Rutas CRUD para las'sliders'
Route::get('admin/sliders', [SlidersController::class, 'index'])->middleware('auth')->name('sliders');
Route::get('admin/sliders/create', [SlidersController::class, 'create'])->middleware('auth')->name('sliders.create');
Route::post('admin/sliders/store', [SlidersController::class, 'store'])->middleware('auth')->name('sliders.store');
Route::get('admin/sliders/{post}', [SlidersController::class, 'show'])->middleware('auth')->name('sliders.show');
Route::get('admin/sliders/edit/{post}', [SlidersController::class, 'edit'])->middleware('auth')->name('sliders.edit');
Route::put('admin/sliders/update/{post}', [SlidersController::class, 'update'])->middleware('auth')->name('sliders.update');
Route::delete('admin/sliders/destroy/{post}', [SlidersController::class, 'destroy'])->middleware('auth')->name('sliders.destroy');

// Rutas CRUD para las'notificaciones'
Route::get('admin/notificaciones', [NotificacionesController::class, 'index'])->middleware('auth')->name('notificaciones');
Route::get('admin/notificaciones/create', [NotificacionesController::class, 'create'])->middleware('auth')->name('notificaciones.create');
Route::post('admin/notificaciones/store', [NotificacionesController::class, 'store'])->middleware('auth')->name('notificaciones.store');
Route::get('admin/notificaciones/{post}', [NotificacionesController::class, 'show'])->middleware('auth')->name('notificaciones.show');
Route::get('admin/notificaciones/edit/{post}', [NotificacionesController::class, 'edit'])->middleware('auth')->name('notificaciones.edit');
Route::put('admin/notificaciones/update/{post}', [NotificacionesController::class, 'update'])->middleware('auth')->name('notificaciones.update');
Route::delete('admin/notificaciones/destroy/{post}', [NotificacionesController::class, 'destroy'])->middleware('auth')->name('notificaciones.destroy');

// Rutas CRUD para los popups y cintillos
Route::get('admin/popups', [PopupsController::class, 'index'])->middleware('auth')->name('popups');
Route::post('admin/popups/crear_popup', [PopupsController::class, 'crear_popup'])->middleware('auth')->name('popup.create');
Route::post('admin/popups/actualizar_popup', [PopupsController::class, 'actualizar_popup'])->middleware('auth')->name('popup.update');
Route::post('admin/cintillo/crear_cintillo', [PopupsController::class, 'crear_cintillo'])->middleware('auth')->name('cintillo.create');
Route::delete('admin/popups/destroy/{post}', [PopupsController::class, 'borrar_popup'])->middleware('auth')->name('popup.destroy');
Route::delete('admin/cintillo/destroy/{post}', [PopupsController::class, 'borrar_cintillo'])->middleware('auth')->name('cintillo.destroy');



// Rutas CRUD para las'canjeo'
Route::get('admin/canjeo/cortes', [CanjeoController::class, 'cortes'])->middleware('auth')->name('canjeo.cortes');
Route::get('admin/canjeo/exportar_corte', [CanjeoController::class, 'exportar_corte'])->middleware('auth')->name('canjeo.exportar_corte');
Route::get('admin/canjeo/checar_mail_canje', [CanjeoController::class, 'checar_mail_canje'])->middleware('auth')->name('canjeo.checar_mail_canje');
Route::post('admin/canjeo/cortes/guardar', [CanjeoController::class, 'cortes_guardar'])->middleware('auth')->name('canjeo.cortes_guardar');
Route::put('admin/canjeo/cortes/actualizar/{post}', [CanjeoController::class, 'cortes_actualizar'])->middleware('auth')->name('canjeo.cortes_actualizar');
Route::put('admin/canjeo/cortes_usuario/actualizar/{post}', [CanjeoController::class, 'cortes_usuario_actualizar'])->middleware('auth')->name('canjeo.cortes_usuario_actualizar');
Route::delete('admin/canjeo/cortes/borrar{post}', [CanjeoController::class, 'cortes_borrar'])->middleware('auth')->name('canjeo.cortes_borrar');
Route::delete('admin/canjeo/cortes_usuario/borrar{post}', [CanjeoController::class, 'cortes_usuario_borrar'])->middleware('auth')->name('canjeo.cortes_usuario_borrar');

Route::get('admin/canjeo/productos', [CanjeoController::class, 'productos'])->middleware('auth')->name('canjeo.productos');
Route::get('admin/canjeo/productos/crear', [CanjeoController::class, 'productos_crear'])->middleware('auth')->name('canjeo.productos_crear');
Route::post('admin/canjeo/productos/guardar', [CanjeoController::class, 'productos_guardar'])->middleware('auth')->name('canjeo.productos_guardar');
Route::post('admin/canjeo/productos/galeria/guardar', [CanjeoController::class, 'productos_galeria_guardar'])->middleware('auth')->name('canjeo.productos_galeria_guardar');
Route::delete('admin/canjeo/productos/galeria/borrar/{post}', [CanjeoController::class, 'productos_galeria_borrar'])->middleware('auth')->name('canjeo.productos_galeria_borrar');
Route::post('admin/canjeo/productos/galeria/reorder', [CanjeoController::class, 'productos_galeria_reordenar'])->name('canjeo.productos_galeria_reordenar');
Route::get('admin/canjeo/productos/editar/{post}', [CanjeoController::class, 'productos_editar'])->middleware('auth')->name('canjeo.productos_editar');
Route::put('admin/canjeo/productos/actualizar/{post}', [CanjeoController::class, 'productos_actualizar'])->middleware('auth')->name('canjeo.productos_actualizar');
Route::delete('admin/canjeo/productos/borrar{post}', [CanjeoController::class, 'productos_borrar'])->middleware('auth')->name('canjeo.productos_borrar');

Route::get('admin/canjeo/transacciones', [CanjeoController::class, 'transacciones'])->middleware('auth')->name('canjeo.transacciones');
Route::get('admin/canjeo/transacciones_usuario', [CanjeoController::class, 'transacciones_usuario'])->middleware('auth')->name('canjeo.transacciones_usuario');
Route::get('admin/canjeo/detalles_transaccion', [CanjeoController::class, 'detalles_transaccion'])->middleware('auth')->name('canjeo.detalles_transaccion');
Route::put('admin/canjeo/detalles_transaccion/actualizar/{post}', [CanjeoController::class, 'actualizar_transaccion'])->middleware('auth')->name('canjeo.actualizar_transaccion');



// Rutas CRUD para los 'usuarios'
Route::get('admin/usuarios', [UsuariosController::class, 'index'])->middleware('auth')->name('admin_usuarios');
Route::get('admin/usuarios/create', [UsuariosController::class, 'create'])->middleware('auth')->name('admin_usuarios.create');
Route::post('admin/usuarios/store', [UsuariosController::class, 'store'])->middleware('auth')->name('admin_usuarios.store');

Route::get('admin/usuarios_suscritos/puntos_extra', [UsuariosController::class, 'usuarios_suscritos_puntos_extra'])->name('admin_usuarios_puntos_extra');
Route::post('admin/usuarios_suscritos/puntos_extra/agregar', [UsuariosController::class, 'usuarios_agregar_puntos_extra'])->middleware('auth')->name('admin_usuarios_agregar_puntos_extra');
Route::delete('admin/usuarios_suscritos/puntos_extra/borrar/{post}', [UsuariosController::class, 'usuarios_borrar_puntos_extra'])->middleware('auth')->name('admin_usuarios_borrar_puntos_extra');

Route::get('admin/usuarios_suscritos/reporte_temporada', [UsuariosController::class, 'usuarios_suscritos_reporte_temporada'])->name('admin_usuarios_suscritos_reporte_temporada');
Route::get('admin/usuarios_suscritos/reporte_interno', [UsuariosController::class, 'usuarios_suscritos_reporte_interno'])->name('admin_usuarios_suscritos_reporte_interno');
Route::get('admin/usuarios_suscritos/reporte_region', [UsuariosController::class, 'usuarios_suscritos_region_reporte'])->name('admin_usuarios_suscritos_region_reporte');
Route::get('admin/usuarios_suscritos/reporte', [UsuariosController::class, 'usuarios_suscritos_reporte'])->name('admin_usuarios_suscritos_reporte');
Route::get('admin/usuarios_suscritos/puntaje', [UsuariosController::class, 'usuarios_suscritos_puntaje'])->name('admin_usuarios_suscritos_puntaje');
Route::get('admin/usuarios_suscritos', [UsuariosController::class, 'usuarios_suscritos'])->middleware('auth')->name('admin_usuarios_suscritos');
Route::get('admin/usuarios/suscripcion', [UsuariosController::class, 'suscripcion'])->middleware('auth')->name('admin_usuarios.suscripcion');
Route::post('admin/usuarios/suscribir', [UsuariosController::class, 'suscribir'])->middleware('auth')->name('admin_usuarios.suscribir');
Route::put('admin/usuarios/suscribir_update/{post}', [UsuariosController::class, 'suscribir_update'])->middleware('auth')->name('admin_usuarios.suscribir_update');
Route::put('admin/usuarios/suscribir_full_update/{post}', [UsuariosController::class, 'suscribir_full_update'])->middleware('auth')->name('admin_usuarios.suscribir_full_update');
Route::put('admin/usuarios/restaurar_pass', [UsuariosController::class, 'restaurar_pass'])->middleware('auth')->name('admin_usuarios.restaurar_pass');
Route::post('admin/usuarios/importar', [CsvController::class, 'importar_usuarios'])->name('admin_usuarios.importar');
Route::post('/upload-csv', [CsvController::class, 'subirCSV'])->name('upload-csv');
Route::post('/registros_pasados', [CsvController::class, 'registros_pasados'])->name('registros_pasados.csv');
Route::post('/actualizar_pass', [CsvController::class, 'actualizar_pass'])->name('actualizar_pass.csv');

Route::get('admin/usuarios/borrar_tokens', [UsuariosController::class, 'borrar_tokens'])->middleware('auth')->name('admin_usuarios.borrar_tokens');
Route::get('admin/usuarios/cambiar_a_lider', [UsuariosController::class, 'cambiar_a_lider'])->middleware('auth')->name('admin_usuarios.cambiar_a_lider');
Route::get('admin/usuarios/cambiar_a_usuario', [UsuariosController::class, 'cambiar_a_usuario'])->middleware('auth')->name('admin_usuarios.cambiar_a_usuario');

Route::get('admin/usuarios/reporte_sesiones/{post}', [UsuariosController::class, 'reporte_sesiones'])->middleware('auth')->name('admin_usuarios.reporte_sesiones');
Route::get('admin/usuarios/{post}', [UsuariosController::class, 'show'])->middleware('auth')->name('admin_usuarios.show');
Route::get('admin/usuarios/edit/{post}', [UsuariosController::class, 'edit'])->middleware('auth')->name('admin_usuarios.edit');
Route::put('admin/usuarios/update/{post}', [UsuariosController::class, 'update'])->middleware('auth')->name('admin_usuarios.update');

Route::delete('admin/usuarios/destroy/{post}', [UsuariosController::class, 'destroy'])->middleware('auth')->name('admin_usuarios.destroy');


Route::delete('admin/usuarios/desuscribir/{post}', [UsuariosController::class, 'desuscribir'])->middleware('auth')->name('admin_usuarios.desuscribir');


Route::get('usuarios/{id}', function ($id) {
    return "Panel del usuario $id";
});


/**
 * Rutas 2025
 */

 Route::post('/imp_distribuidores', [CsvController::class, 'imp_distribuidores_2025'])->name('imp_distribuidores_2025');
 Route::post('/imp_sucursales', [CsvController::class, 'importarSucursales'])->name('imp_sucursales_2025');
 Route::post('/imp_usuarios', [CsvController::class, 'importarUsuarios'])->name('imp_usuarios_2025');


