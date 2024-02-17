<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CuentasController;
use App\Http\Controllers\DistribuidoresController;
use App\Http\Controllers\TemporadasController;
use App\Http\Controllers\PublicacionesController;
use App\Http\Controllers\UsuariosController;
use App\Http\Controllers\ConfiguracionesController;
use App\Http\Controllers\ClasesController;
use App\Http\Controllers\LoginController;

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

Route::get('/', FrontController::class);

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


// Rutas CRUD para los 'usuarios'
Route::get('admin/usuarios', [UsuariosController::class, 'index'])->middleware('auth')->name('admin_usuarios');
Route::get('admin/usuarios/create', [UsuariosController::class, 'create'])->middleware('auth')->name('admin_usuarios.create');
Route::post('admin/usuarios/store', [UsuariosController::class, 'store'])->middleware('auth')->name('admin_usuarios.store');
Route::get('admin/usuarios/{post}', [UsuariosController::class, 'show'])->middleware('auth')->name('admin_usuarios.show');
Route::get('admin/usuarios/edit/{post}', [UsuariosController::class, 'edit'])->middleware('auth')->name('admin_usuarios.edit');
Route::put('admin/usuarios/update/{post}', [UsuariosController::class, 'update'])->middleware('auth')->name('admin_usuarios.update');
Route::delete('admin/usuarios/destroy/{post}', [UsuariosController::class, 'destroy'])->middleware('auth')->name('admin_usuarios.destroy');


Route::get('usuarios/{id}', function ($id) {
    return "Panel del usuario $id";
});
