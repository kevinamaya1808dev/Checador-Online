<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BecarioController;
use App\Http\Controllers\HistorialController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Reportes\ExcelController;
use App\Http\Controllers\Reportes\PdfController;
use App\Http\Controllers\Reportes\ReporteController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
;


Route::get('/', function () {
    return redirect()->route('login');
});


// Rutas de Login (Sin cambios)
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');



// =========================================================================
// MURALLA DIGITAL - APARTADO ADMIN
// Cualquier vista, función o proceso administrativo queda estrictamente 
// encapsulado aquí bajo verificación de autenticación y rol de administrador.
// =========================================================================
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/asistencias-tiempo', [AdminController::class, 'tiempos'])->name('admin.tiempos');
    Route::get('/admin/historial', [HistorialController::class, 'index'])->name('admin.historial');
    Route::get('/admin/historial/reporte/{user}', [App\Http\Controllers\Reportes\ReporteController::class, 'show'])->name('admin.historial.reporte');
    Route::get('/admin/historial/reporte/{user}/excel', [ReporteController::class, 'exportarExcel'])->name('admin.historial.reporte.excel');
    Route::get('/admin/historial/{asistencia}', [HistorialController::class, 'show'])->name('admin.historial.show');
    Route::get('/admin/reportes/{user}/excel', [ExcelController::class,'reporteBecario'])->name('admin.reportes.excel');
    Route::get('/admin/reportes/excel/general', [ExcelController::class,'historialGeneral'])->name('admin.reportes.general.excel');

    Route::get(
        '/admin/reportes/pdf/general',
        [PdfController::class, 'general']
    )->name('admin.reportes.pdf.general');

    Route::get(
        '/admin/reportes/pdf/becario/{user}',
        [PdfController::class, 'becario']
    )->name('admin.reportes.pdf.becario');

    // [BLOQUEO DE SEGURIDAD]: Rutas que estaban vulnerables (fuera de la muralla) y fueron encapsuladas
    Route::put('/admin/user/{id}', [App\Http\Controllers\HomeController::class, 'update'])->name('users.update');
    Route::post('/admin/becarios/store', [AdminController::class, 'storeBecario'])->name('admin.becarios.store');

    // [BLOQUEO DE SEGURIDAD]: Procesos administrativos de usuarios trasladados desde el grupo 'auth' inseguro
    Route::post('/home/user/store', [HomeController::class, 'storeUser'])->name('users.store');
    Route::post('/home/user/toggle/{id}', [HomeController::class, 'toggleAdmin'])->name('users.toggle');
    Route::delete('/home/user/{id}', [HomeController::class, 'deleteUser'])->name('users.delete');
});
// =========================================================================
// FIN DE LA MURALLA DIGITAL ADMIN
// =========================================================================



Route::middleware(['auth', 'role:becario'])->group(function () {
    Route::get('/becario/dashboard', [BecarioController::class, 'index'])->name('becario.dashboard');   
    Route::post('/entrada', [BecarioController::class, 'registrarEntrada'])->name('becario.checar');
    Route::post('/salida', [BecarioController::class, 'registrarSalida'])->name('becario.salida');
    Route::post('/iniciar-pausa', [BecarioController::class, 'iniciarPausa'])->name('becario.iniciarPausa');
    Route::post('/finalizar-pausa', [BecarioController::class, 'finalizarPausa'])->name('becario.finalizarPausa');
});



// [AJUSTE DE SEGURIDAD]: Se especifica el guard 'auth:web' para forzar la redirección al login
// si no existe una sesión activa válida.
Route::middleware(['auth:web'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
});