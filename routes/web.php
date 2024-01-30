<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\AdminController;
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

Route::get('admin', AdminController::class)->middleware('auth')->name('admin');

// Rutas Login
Route::get('login', [LoginController::class, 'login_form'])->name('login');
Route::post('login/verificar', [LoginController::class, 'login_verificar'])->name('login.verificar');
Route::get('login/registro_form', [LoginController::class, 'registro_form'])->name('login.registro_form');
Route::post('login/registro', [LoginController::class, 'registro'])->name('login.registro');
Route::get('login/logout', [LoginController::class, 'logout'])->name('login.logout');


// Rutas CRUD para las'clases'
Route::get('admin/clases', [ClasesController::class, 'index'])->middleware('auth')->name('clases');
Route::get('admin/clases/create', [ClasesController::class, 'create'])->middleware('auth')->name('clases.create');
Route::post('admin/clases/store', [ClasesController::class, 'store'])->middleware('auth')->name('clases.store');
Route::get('admin/clases/{post}', [ClasesController::class, 'show'])->middleware('auth')->name('clases.show');
Route::get('admin/clases/edit/{post}', [ClasesController::class, 'edit'])->middleware('auth')->name('clases.edit');
Route::put('admin/clases/update/{post}', [ClasesController::class, 'update'])->middleware('auth')->name('clases.update');
Route::delete('admin/clases/destroy/{post}', [ClasesController::class, 'destroy'])->middleware('auth')->name('clases.destroy');


Route::get('usuarios/{id}', function ($id) {
    return "Panel del usuario $id";
});
