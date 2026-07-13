<?php

namespace App\Http\Controllers\Reportes;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\ReporteService;
use Illuminate\Http\Request;
use Spatie\SimpleExcel\SimpleExcelWriter;
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
    // Obtener filtros (se usarán más adelante)
    $desde = $request->input('desde');
    $hasta = $request->input('hasta');

    // Obtener datos desde el Service
    $asistencias = $this->reporteService->obtenerReporteBecario(
        $user,
        $desde,
        $hasta
    );

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

    // Crear Excel
    $writer = SimpleExcelWriter::create($ruta);

    foreach ($asistencias as $asistencia) {

        $writer->addRow([

            'Fecha' => Carbon::parse($asistencia->fecha)->format('d/m/Y'),

            'Entrada' => $asistencia->hora_entrada
                ? Carbon::parse($asistencia->hora_entrada)->format('h:i:s A')
                : '--',

            'Salida' => $asistencia->hora_salida
                ? Carbon::parse($asistencia->hora_salida)->format('h:i:s A')
                : '--',

            'Pausas' => $asistencia->pausas->count(),

            'Tiempo de pausas' => $asistencia->tiempoPausas(),

            'Tiempo trabajado' => $asistencia->formatoTiempo(
                $asistencia->tiempoTrabajado()
            ),

            'Horas extra' => $asistencia->horasExtrasTotalFormato(),

        ]);
    }

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
    | Crear nombre del archivo
    |--------------------------------------------------------------------------
    */

    $nombreArchivo =

        'Historial_General_' .

        now()->format('Ymd_His') .

        '.xlsx';



    $ruta = storage_path(
        'app/temp/'.$nombreArchivo
    );



    if (!file_exists(storage_path('app/temp'))) {

        mkdir(
            storage_path('app/temp'),
            0777,
            true
        );

    }



    $writer = SimpleExcelWriter::create($ruta);



    foreach ($asistencias as $asistencia) {

        $writer->addRow([

            'Becario' => $asistencia->user->name,

            'Fecha' => Carbon::parse(
                $asistencia->fecha
            )->format('d/m/Y'),

            'Entrada' =>

                $asistencia->hora_entrada

                    ? Carbon::parse(
                        $asistencia->hora_entrada
                    )->format('h:i:s A')

                    : '--',

            'Salida' =>

                $asistencia->hora_salida

                    ? Carbon::parse(
                        $asistencia->hora_salida
                    )->format('h:i:s A')

                    : '--',

            'Pausas' =>

                $asistencia->pausas->count(),

            'Tiempo pausa' =>

                $asistencia->tiempoPausas(),

            'Tiempo trabajado' =>

                $asistencia->formatoTiempo(
                    $asistencia->tiempoTrabajado()
                ),

            'Horas extra' =>

                $asistencia->horasExtrasTotalFormato()

        ]);

    }



    return response()

        ->download($ruta)

        ->deleteFileAfterSend(true);
}
}