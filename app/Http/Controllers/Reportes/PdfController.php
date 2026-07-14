<?php

namespace App\Http\Controllers\Reportes;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\ReporteService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;


class PdfController extends Controller
{

    protected $reporteService;


    public function __construct(ReporteService $reporteService)
    {
        $this->reporteService = $reporteService;
    }



    /*
    |--------------------------------------------------------------------------
    | PDF GENERAL
    |--------------------------------------------------------------------------
    */

    public function general(Request $request)
{

    $asistencias = \App\Models\Asistencia::with([
        'user',
        'pausas'
    ]);


    if ($request->filled('search')) {

        $buscar = $request->search;

        $asistencias->whereHas('user', function($q) use ($buscar){

            $q->where('name','like',"%{$buscar}%")
              ->orWhere('email','like',"%{$buscar}%");

        });

    }



    if ($request->filled('mes')) {

        $asistencias->whereMonth(
            'fecha',
            $request->mes
        );

    }



    if ($request->filled('semana')) {

        switch($request->semana){

            case 1:
                $asistencias->whereDay('fecha','<=',7);
                break;

            case 2:
                $asistencias->whereDay('fecha','>=',8)
                            ->whereDay('fecha','<=',14);
                break;

            case 3:
                $asistencias->whereDay('fecha','>=',15)
                            ->whereDay('fecha','<=',21);
                break;

            case 4:
                $asistencias->whereDay('fecha','>=',22)
                            ->whereDay('fecha','<=',28);
                break;

            case 5:
                $asistencias->whereDay('fecha','>=',29);
                break;

        }

    }



    $asistencias = $asistencias
        ->orderByDesc('fecha')
        ->get();



    $resumen = $this->reporteService
        ->obtenerResumen($asistencias);



    $pdf = Pdf::loadView(
        'admin.reportes.pdf.general',
        compact(
            'asistencias',
            'resumen'
        )
    );


    return $pdf->download(
        'reporte-general.pdf'
    );

}



    /*
    |--------------------------------------------------------------------------
    | PDF INDIVIDUAL BECARIO
    |--------------------------------------------------------------------------
    */

    public function becario(Request $request, User $user)
    {

        $asistencias = $this->reporteService
            ->obtenerReporteBecario(
                $user,
                $request->desde,
                $request->hasta
            );



        $resumen = $this->reporteService
            ->obtenerResumen($asistencias);



        $pdf = Pdf::loadView(
            'admin.reportes.pdf.individual',
            compact(
                'user',
                'asistencias',
                'resumen'
            )
        );


        return $pdf->download(
            'reporte-'.$user->name.'.pdf'
        );

    }


}