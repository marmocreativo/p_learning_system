<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\AdminController;
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
