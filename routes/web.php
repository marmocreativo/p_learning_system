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

Route::get('/', [FrontController::class, 'index']);
Route::get('scripts_ajustes', [FrontController::class, 'scripts_ajustes'])->name('scripts.ajustes');

Route::get('admin', [AdminController::class, 'index'])->middleware('auth')->name('admin');
Route::get('admin/base_de_datos', [AdminController::class, 'base_de_datos'])->middleware('auth')->name('admin.base_de_datos');
Route::get('admin/backup', [AdminController::class, 'backup'])->name('admin.backup');


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

// Rutas CRUD para las'temporadas'
Route::get('admin/temporadas', [TemporadasController::class, 'index'])->middleware('auth')->name('temporadas');
Route::get('admin/temporadas/create', [TemporadasController::class, 'create'])->middleware('auth')->name('temporadas.create');
Route::post('admin/temporadas/store', [TemporadasController::class, 'store'])->middleware('auth')->name('temporadas.store');
Route::get('admin/temporadas/{post}', [TemporadasController::class, 'show'])->middleware('auth')->name('temporadas.show');
Route::get('admin/temporadas/edit/{post}', [TemporadasController::class, 'edit'])->middleware('auth')->name('temporadas.edit');
Route::put('admin/temporadas/update/{post}', [TemporadasController::class, 'update'])->middleware('auth')->name('temporadas.update');
Route::delete('admin/temporadas/destroy/{post}', [TemporadasController::class, 'destroy'])->middleware('auth')->name('temporadas.destroy');

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
Route::get('admin/sesiones/create', [SesionesController::class, 'create'])->middleware('auth')->name('sesiones.create');
Route::post('admin/sesiones/store', [SesionesController::class, 'store'])->middleware('auth')->name('sesiones.store');
Route::post('admin/sesiones/store_pregunta', [SesionesController::class, 'store_pregunta'])->middleware('auth')->name('sesiones.store_pregunta');
Route::get('admin/sesiones/{post}', [SesionesController::class, 'show'])->middleware('auth')->name('sesiones.show');
Route::get('admin/sesiones/resultados/{post}', [SesionesController::class, 'resultados'])->middleware('auth')->name('sesiones.resultados');
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
Route::get('admin/trivias/{post}', [TriviasController::class, 'show'])->middleware('auth')->name('trivias.show');
Route::get('admin/trivias/resultados/{post}', [TriviasController::class, 'resultados'])->middleware('auth')->name('trivias.resultados');
Route::get('admin/trivias/edit/{post}', [TriviasController::class, 'edit'])->middleware('auth')->name('trivias.edit');
Route::put('admin/trivias/update_pregunta/{post}', [TriviasController::class, 'update_pregunta'])->middleware('auth')->name('trivias.update_pregunta');
Route::put('admin/trivias/update/{post}', [TriviasController::class, 'update'])->middleware('auth')->name('trivias.update');
Route::delete('admin/trivias/destroy_pregunta/{post}', [TriviasController::class, 'destroy_pregunta'])->middleware('auth')->name('trivias.destroy_pregunta');
Route::delete('admin/trivias/destroy_ganador{post}', [TriviasController::class, 'destroy_ganador'])->middleware('auth')->name('trivias.destroy_ganador');
Route::delete('admin/trivias/destroy_respuesta{post}', [TriviasController::class, 'destroy_respuesta'])->middleware('auth')->name('trivias.destroy_respuesta');
Route::delete('admin/trivias/destroy/{post}', [TriviasController::class, 'destroy'])->middleware('auth')->name('trivias.destroy');

// Rutas CRUD para las'jackpots'
Route::get('admin/jackpots', [JackpotsController::class, 'index'])->middleware('auth')->name('jackpots');
Route::get('admin/jackpots/create', [JackpotsController::class, 'create'])->middleware('auth')->name('jackpots.create');
Route::post('admin/jackpots/store_pregunta', [JackpotsController::class, 'store_pregunta'])->middleware('auth')->name('jackpots.store_pregunta');
Route::post('admin/jackpots/store', [JackpotsController::class, 'store'])->middleware('auth')->name('jackpots.store');
Route::get('admin/jackpots/{post}', [JackpotsController::class, 'show'])->middleware('auth')->name('jackpots.show');
Route::get('admin/jackpots/resultados/{post}', [JackpotsController::class, 'resultados'])->middleware('auth')->name('jackpots.resultados');
Route::get('admin/jackpots/edit/{post}', [JackpotsController::class, 'edit'])->middleware('auth')->name('jackpots.edit');
Route::put('admin/jackpots/update_pregunta/{post}', [JackpotsController::class, 'update_pregunta'])->middleware('auth')->name('jackpots.update_pregunta');
Route::put('admin/jackpots/update/{post}', [JackpotsController::class, 'update'])->middleware('auth')->name('jackpots.update');
Route::delete('admin/jackpots/destroy/{post}', [JackpotsController::class, 'destroy'])->middleware('auth')->name('jackpots.destroy');
Route::delete('admin/jackpots/destroy_pregunta/{post}', [JackpotsController::class, 'destroy_pregunta'])->middleware('auth')->name('jackpots.destroy_pregunta');

// Rutas CRUD para las'logros'
Route::get('admin/logros', [LogrosController::class, 'index'])->middleware('auth')->name('logros');
Route::get('admin/logros/create', [LogrosController::class, 'create'])->middleware('auth')->name('logros.create');
Route::post('admin/logros/store', [LogrosController::class, 'store'])->middleware('auth')->name('logros.store');
Route::get('admin/logros/{post}', [LogrosController::class, 'show'])->middleware('auth')->name('logros.show');
Route::get('admin/logros/edit/{post}', [LogrosController::class, 'edit'])->middleware('auth')->name('logros.edit');
Route::put('admin/logros/update/{post}', [LogrosController::class, 'update'])->middleware('auth')->name('logros.update');
Route::delete('admin/logros/destroy/{post}', [LogrosController::class, 'destroy'])->middleware('auth')->name('logros.destroy');
Route::delete('admin/logros/destroy_participacion/{post}', [LogrosController::class, 'destroy_participacion'])->middleware('auth')->name('logros.destroy_participacion');

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


// Rutas CRUD para los 'usuarios'
Route::get('admin/usuarios', [UsuariosController::class, 'index'])->middleware('auth')->name('admin_usuarios');
Route::get('admin/usuarios/create', [UsuariosController::class, 'create'])->middleware('auth')->name('admin_usuarios.create');
Route::post('admin/usuarios/store', [UsuariosController::class, 'store'])->middleware('auth')->name('admin_usuarios.store');
Route::get('admin/usuarios_suscritos', [UsuariosController::class, 'usuarios_suscritos'])->middleware('auth')->name('admin_usuarios_suscritos');
Route::get('admin/usuarios/suscripcion', [UsuariosController::class, 'suscripcion'])->middleware('auth')->name('admin_usuarios.suscripcion');
Route::post('admin/usuarios/suscribir', [UsuariosController::class, 'suscribir'])->middleware('auth')->name('admin_usuarios.suscribir');
Route::put('admin/usuarios/suscribir_update/{post}', [UsuariosController::class, 'suscribir_update'])->middleware('auth')->name('admin_usuarios.suscribir_update');
Route::post('/upload-csv', [CsvController::class, 'subirCSV'])->name('upload-csv');

Route::get('admin/usuarios/cambiar_a_lider', [UsuariosController::class, 'cambiar_a_lider'])->middleware('auth')->name('admin_usuarios.cambiar_a_lider');
Route::get('admin/usuarios/cambiar_a_usuario', [UsuariosController::class, 'cambiar_a_usuario'])->middleware('auth')->name('admin_usuarios.cambiar_a_usuario');

Route::get('admin/usuarios/{post}', [UsuariosController::class, 'show'])->middleware('auth')->name('admin_usuarios.show');
Route::get('admin/usuarios/edit/{post}', [UsuariosController::class, 'edit'])->middleware('auth')->name('admin_usuarios.edit');
Route::put('admin/usuarios/update/{post}', [UsuariosController::class, 'update'])->middleware('auth')->name('admin_usuarios.update');
Route::delete('admin/usuarios/destroy/{post}', [UsuariosController::class, 'destroy'])->middleware('auth')->name('admin_usuarios.destroy');


Route::delete('admin/usuarios/desuscribir/{post}', [UsuariosController::class, 'desuscribir'])->middleware('auth')->name('admin_usuarios.desuscribir');


Route::get('usuarios/{id}', function ($id) {
    return "Panel del usuario $id";
});
