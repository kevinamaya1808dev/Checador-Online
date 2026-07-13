<?php

namespace App\Http\Controllers\Reportes;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class ReporteController extends Controller
{
    public function show(\App\Models\User $user)
{
    $asistencias = $user->asistencias()
    ->with('pausas')
    ->orderByDesc('fecha')
    ->orderByDesc('hora_entrada')
    ->get();

    $totalJornadas = $asistencias->count();

    $totalTrabajo = $asistencias->sum(function ($a) {
        return $a->tiempoTrabajado();
    });

    $totalPausas = $asistencias->sum(function ($a) {
        return $a->tiempoPausasSegundos();
    });

    $totalExtras = $asistencias->sum(function ($a) {
        return $a->tiempoHorasExtras();
    });

    return view(
        'admin.historial.reporte',
        compact(
            'user',
            'asistencias',
            'totalJornadas',
            'totalTrabajo',
            'totalPausas',
            'totalExtras'
        )
    );
}
}