<?php

namespace App\Http\Controllers\Reportes;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\ReporteService;
use Illuminate\Http\Request;

class ReporteController extends Controller
{
    protected ReporteService $reporteService;

public function __construct(ReporteService $reporteService)
{
    $this->reporteService = $reporteService;
}

   public function show(User $user)
{
    /*
    |--------------------------------------------------------------------------
    | Obtener asistencias del becario
    |--------------------------------------------------------------------------
    */

    $asistencias = $this->reporteService
        ->obtenerReporteBecario($user);

    /*
    |--------------------------------------------------------------------------
    | Obtener resumen
    |--------------------------------------------------------------------------
    */

    $resumen = $this->reporteService
        ->obtenerResumen($asistencias);

    /*
    |--------------------------------------------------------------------------
    | Vista
    |--------------------------------------------------------------------------
    */

    return view(
        'admin.historial.reporte',
        [
            'user' => $user,
            'asistencias' => $asistencias,
            'resumen' => $resumen,
        ]
    );
}
}