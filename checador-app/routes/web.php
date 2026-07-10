<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AsistenciaController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BecarioController;
use App\Http\Controllers\HistorialController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
;

Route::get('/', function () {
    return redirect()->route('login');
});


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');



Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/exportar', [AdminController::class, 'exportarReporte'])->name('admin.exportar');
    Route::get('/admin/asistencias-tiempo', [AdminController::class, 'tiempos'])->name('admin.tiempos');
    Route::get('/admin/historial', [HistorialController::class, 'index'])->name('admin.historial');

});

Route::put('/admin/user/{id}', [App\Http\Controllers\HomeController::class, 'update'])->name('users.update');


Route::middleware(['auth', 'role:becario'])->group(function () {
    Route::get('/becario/dashboard', [BecarioController::class, 'index'])->name('becario.dashboard');   
    Route::post('/entrada', [BecarioController::class, 'registrarEntrada'])->name('becario.checar');
    Route::post('/salida', [BecarioController::class, 'registrarSalida'])->name('becario.salida');
    Route::post('/iniciar-pausa', [BecarioController::class, 'iniciarPausa'])->name('becario.iniciarPausa');
    Route::post('/finalizar-pausa', [BecarioController::class, 'finalizarPausa'])->name('becario.finalizarPausa');
});

// Rutas de Login
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');



// Agrega esto en routes/web.php

Route::post('/admin/becarios/store', [AdminController::class, 'storeBecario'])->name('admin.becarios.store');

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::post('/home/user/store', [HomeController::class, 'storeUser'])->name('users.store');
    Route::post('/home/user/toggle/{id}', [HomeController::class, 'toggleAdmin'])->name('users.toggle');
    Route::delete('/home/user/{id}', [HomeController::class, 'deleteUser'])->name('users.delete');
});

