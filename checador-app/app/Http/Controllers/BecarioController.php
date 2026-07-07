<?php

namespace App\Http\Controllers;

use App\Models\Asistencia;
use App\Models\Pausa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BecarioController extends Controller
{
public function index()
{

    $asistencia = Asistencia::where('user_id',Auth::id())
        ->where('fecha',now()->toDateString())
        ->latest()
        ->first();



    $tiempoTrabajado = "00:00:00";
    $tiempoPausa = "00:00:00";


    if($asistencia)
    {

        $tiempoTrabajado =
            $asistencia->formatoTiempo(
                $asistencia->tiempoTrabajado()
            );


        $tiempoPausa =
            $asistencia->tiempoPausas();

    }



    $estadoActual="Activo";


    $pausaActiva=Pausa::where('user_id',Auth::id())
        ->whereNull('fin_pausa')
        ->first();



    if($pausaActiva)
    {
        $estadoActual="En pausa";
    }



    return view('becario.dashboard',
    compact(
        'tiempoTrabajado',
        'tiempoPausa',
        'estadoActual'
    ));

}

    public function registrarEntrada() {
        Asistencia::create([
            'user_id' => Auth::id(),
            'hora_entrada' => now(),
            'fecha' => now()->toDateString(),
        ]);
        return back()->with('success', 'Entrada registrada.');
    }

    public function registrarSalida() {
        $asistencia = Asistencia::where('user_id', Auth::id())
            ->where('fecha', now()->toDateString())
            ->whereNull('hora_salida')
            ->latest()
            ->first();

        if ($asistencia) {
            $asistencia->update(['hora_salida' => now()]);
            return back()->with('success', 'Salida registrada.');
        }
        return back()->with('error', 'No tienes una entrada activa hoy.');
    }

    public function iniciarPausa(Request $request) {
        Pausa::create([
            'user_id' => Auth::id(),
            'inicio_pausa' => now(),
            'motivo' => $request->motivo,
            'fecha' => now()->toDateString(),
        ]);
        return back()->with('success', 'Pausa iniciada.');
    }

    public function finalizarPausa() {
        $pausa = Pausa::where('user_id', Auth::id())
            ->whereNull('fin_pausa')
            ->latest()
            ->first();

        if ($pausa) {
            $pausa->update(['fin_pausa' => now()]);
            return back()->with('success', 'Pausa finalizada.');
        }
        return back()->with('error', 'No tienes una pausa activa.');
    }
    public function store(Request $request) {
    \App\Models\Asistencia::create([
        'user_id' => Auth::id(),
        'hora_entrada' => now()->format('H:i:s'), // Esto guarda en formato 24h (ej: 14:30:00)
        'fecha' => now()->format('Y-m-d'),
    ]);
    return back()->with('success', 'Checado');
}
}