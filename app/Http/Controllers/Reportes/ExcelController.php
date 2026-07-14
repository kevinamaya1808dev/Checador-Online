<?php

namespace App\Http\Controllers\Reportes;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\ReporteService;
use Illuminate\Http\Request;
use App\Services\Excel\HistorialBecarioExcel;
use App\Services\Excel\HistorialGeneralExcel;
use Carbon\Carbon;

class ExcelController extends Controller
{
    protected ReporteService $reporteService;

    public function __construct(ReporteService $reporteService)
    {
        $this->reporteService = $reporteService;
    }

    /**
     * Reporte individual
     */
   public function reporteBecario(User $user, Request $request)
{
    // Obtener filtros
    $desde = $request->input('desde');
    $hasta = $request->input('hasta');

    // Obtener datos desde el Service
    $asistencias = $this->reporteService->obtenerReporteBecario(
        $user,
        $desde,
        $hasta
    );

    // Obtener resumen (mismo método que usa historialGeneral)
    $resumen = $this->reporteService->obtenerResumen($asistencias);

    // Nombre del archivo
    $nombreArchivo = 'Reporte_' .
        str_replace(' ', '_', $user->name) .
        '_' .
        now()->format('Ymd_His') .
        '.xlsx';

    // Ruta temporal
    $ruta = storage_path('app/temp/' . $nombreArchivo);

    // Crear carpeta si no existe
    if (!file_exists(storage_path('app/temp'))) {
        mkdir(storage_path('app/temp'), 0777, true);
    }

    // Generar Excel con diseño OllinCheck
    $excel = new HistorialBecarioExcel();

    $excel->guardarComo(
        $ruta,
        $user,
        $asistencias,
        $resumen,
        [
            'desde' => $desde,
            'hasta' => $hasta,
        ]
    );

    return response()->download($ruta)->deleteFileAfterSend(true);
}
    /**
     * Reporte general
     */
    public function historialGeneral(Request $request)
{
    /*
    |--------------------------------------------------------------------------
    | Obtener filtros
    |--------------------------------------------------------------------------
    */

    $buscar = $request->input('search');

    $mes = $request->input('mes');

    $semana = $request->input('semana');


    /*
    |--------------------------------------------------------------------------
    | Obtener registros
    |--------------------------------------------------------------------------
    */

    $asistencias = $this->reporteService
        ->obtenerHistorialGeneral(
            $buscar,
            $mes,
            $semana
        );


    /*
    |--------------------------------------------------------------------------
    | Obtener resumen
    |--------------------------------------------------------------------------
    */

    $resumen = $this->reporteService
        ->obtenerResumen($asistencias);



    /*
    |--------------------------------------------------------------------------
    | Crear nombre archivo
    |--------------------------------------------------------------------------
    */

    $nombreArchivo =

        'Historial_General_' .

        now()->format('Ymd_His') .

        '.xlsx';



    $ruta = storage_path(
        'app/temp/'.$nombreArchivo
    );



    /*
    |--------------------------------------------------------------------------
    | Crear carpeta temporal
    |--------------------------------------------------------------------------
    */

    if (!file_exists(storage_path('app/temp'))) {

        mkdir(
            storage_path('app/temp'),
            0777,
            true
        );

    }



    /*
    |--------------------------------------------------------------------------
    | Generar Excel con diseño OllinCheck
    |--------------------------------------------------------------------------
    */

    $excel = new HistorialGeneralExcel();


    $excel->guardarComo(
        $ruta,
        $asistencias,
        $resumen,
        [
            'search' => $buscar,
            'mes' => $mes,
            'semana' => $semana,
        ]
    );



    /*
    |--------------------------------------------------------------------------
    | Descargar archivo
    |--------------------------------------------------------------------------
    */

    return response()
        ->download($ruta)
        ->deleteFileAfterSend(true);
}
}