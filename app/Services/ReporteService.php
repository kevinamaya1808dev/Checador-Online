<?php

namespace App\Services;

use App\Models\User;
use App\Models\Asistencia;

class ReporteService
{

    public function obtenerReporteBecario(
        User $user,
        $desde = null,
        $hasta = null
    )
    {

        $query = Asistencia::query()
            ->with([
                'user',
                'pausas'
            ])
            ->where(
                'user_id',
                $user->id
            );

        if ($desde) {

            $query->whereDate(
                'fecha',
                '>=',
                $desde
            );

        }

        if ($hasta) {

            $query->whereDate(
                'fecha',
                '<=',
                $hasta
            );

        }

        return $query
            ->orderByDesc('fecha')
            ->orderByDesc('hora_entrada')
            ->get();

    }
    public function obtenerHistorialGeneral(
    $buscar = null,
    $mes = null,
    $semana = null
)
{

    $query = Asistencia::query()

        ->with([
            'user',
            'pausas'
        ])

        ->join(
            'users',
            'users.id',
            '=',
            'asistencias.user_id'
        )

        ->select('asistencias.*')

        ->orderBy('users.name');



    /*
    |--------------------------------------------------------------------------
    | Buscar becario
    |--------------------------------------------------------------------------
    */

    if ($buscar) {

        $query->where(
            'users.name',
            'like',
            "%{$buscar}%"
        );

    }



   /*
|--------------------------------------------------------------------------
| FILTRO MES
|--------------------------------------------------------------------------
*/

if ($mes) {

    $query->whereMonth(
        'fecha',
        $mes
    );

}


/*
|--------------------------------------------------------------------------
| FILTRO SEMANA
|--------------------------------------------------------------------------
*/

if ($semana) {

    switch($semana){

        case 1:

            $query->whereDay('fecha','>=',1)
                  ->whereDay('fecha','<=',7);

            break;


        case 2:

            $query->whereDay('fecha','>=',8)
                  ->whereDay('fecha','<=',14);

            break;


        case 3:

            $query->whereDay('fecha','>=',15)
                  ->whereDay('fecha','<=',21);

            break;


        case 4:

            $query->whereDay('fecha','>=',22)
                  ->whereDay('fecha','<=',28);

            break;


        case 5:

            $query->whereDay('fecha','>=',29);

            break;

    }

}



    return $query->get();

}

/*
|--------------------------------------------------------------------------
| Obtener resumen del reporte
|--------------------------------------------------------------------------
*/

public function obtenerResumen($asistencias)
{
    $resumen = [

        'jornadas' => $asistencias->count(),

        'segundos_trabajados' => 0,

        'segundos_pausa' => 0,

        'segundos_extra' => 0,

    ];

    foreach ($asistencias as $asistencia) {

        $resumen['segundos_trabajados'] +=
            $asistencia->tiempoTrabajado();

        $resumen['segundos_pausa'] +=
            $asistencia->tiempoPausasSegundos();

        $resumen['segundos_extra'] +=
            $asistencia->tiempoHorasExtras();

    }

    $resumen['horas_trabajadas'] =
    $this->segundosAHoras(
        $resumen['segundos_trabajados']
    );

$resumen['tiempo_pausa'] =
    $this->segundosAHoras(
        $resumen['segundos_pausa']
    );

$resumen['horas_extra'] =
    $this->segundosAHoras(
        $resumen['segundos_extra']
    );

    return $resumen;
}

/*
|--------------------------------------------------------------------------
| Convertir segundos a formato HH:MM:SS
|--------------------------------------------------------------------------
|
| A diferencia de gmdate(), este método soporta más de 24 horas.
|
*/

private function segundosAHoras(int $segundos): string
{
    $horas = floor($segundos / 3600);

    $minutos = floor(($segundos % 3600) / 60);

    $segundos = $segundos % 60;

    return sprintf(
        '%02d:%02d:%02d',
        $horas,
        $minutos,
        $segundos
    );
}

}