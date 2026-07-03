<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AsistenciaController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BecarioController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::post('/checar', [BecarioController::class, 'store'])->name('becario.checar');

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
});

Route::middleware(['auth', 'role:becario'])->group(function () {
    Route::get('/becario/dashboard', [BecarioController::class, 'index'])->name('becario.dashboard');   
    Route::post('/asistencia/entrada', [AsistenciaController::class, 'registrarEntrada'])->name('asistencia.entrada');
    Route::post('/asistencia/salida', [AsistenciaController::class, 'registrarSalida'])->name('asistencia.salida');
});

// Rutas de Login
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');



// Agrega esto en routes/web.php
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login');
})->name('logout');

Route::post('/admin/becarios/store', [AdminController::class, 'storeBecario'])->name('admin.becarios.store');