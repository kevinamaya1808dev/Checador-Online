<?php

namespace App\Http\Controllers;

use App\Models\Asistencia;
use App\Models\Pausa;
use App\Support\EstadoTurnoPresenter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BecarioController extends Controller
{
    public function index()
    {
        $usuario = Auth::user();

        $asistencia = Asistencia::where('user_id', Auth::id())
            ->where('fecha', now()->toDateString())
            ->latest()
            ->first();

        $estado = 'inactivo';

        $horaEntrada = null;
        $horaSalida = null;
        $pausaInicio = null;

        $segundosPausaAcumulados = 0;

        if ($asistencia && !$asistencia->hora_salida) {
            $horaEntrada = $asistencia->fecha . ' ' . $asistencia->hora_entrada;

            $pausa = Pausa::where('asistencia_id', $asistencia->id)
                ->whereNull('fin_pausa')
                ->latest()
                ->first();

            if ($pausa) {
                $estado = 'pausado';
                $pausaInicio = $asistencia->fecha . ' ' . $pausa->inicio_pausa;
            } else {
                $estado = 'trabajando';
            }

            // sumar pausas terminadas SOLO de la jornada activa
            $pausas = Pausa::where('asistencia_id', $asistencia->id)
                ->whereNotNull('fin_pausa')
                ->get();

            foreach ($pausas as $p) {
                $inicio = \Carbon\Carbon::parse($p->inicio_pausa);
                $fin = \Carbon\Carbon::parse($p->fin_pausa);

                $segundosPausaAcumulados += $inicio->diffInSeconds($fin);
            }
        }

        return view('becario.dashboard', [
            'presenter' => new EstadoTurnoPresenter($estado ?? null),
            'horaEntrada' => $horaEntrada ?? null,
            'pausaInicio' => $pausaInicio ?? null,
            'horaSalida' => $horaSalida ?? null,
            'segundosPausaAcumulados' => $segundosPausaAcumulados ?? 0,
        ]);
    }

    public function registrarEntrada()
    {
        // Buscar si existe una jornada activa
        $asistenciaActiva = Asistencia::where('user_id', Auth::id())
            ->where('fecha', now()->toDateString())
            ->whereNull('hora_salida')
            ->first();

        if ($asistenciaActiva) {
            return back()->with('error', 'Ya tienes una jornada activa.');
        }

        Asistencia::create([
            'user_id'      => Auth::id(),
            'hora_entrada' => now()->format('H:i:s'), // FIX: antes era now() (datetime completo)
            'fecha'        => now()->toDateString(),
        ]);

        return back()->with('success', 'Entrada registrada.');
    }

    public function registrarSalida()
    {
        $asistencia = Asistencia::where('user_id', Auth::id())
            ->where('fecha', now()->toDateString())
            ->latest()
            ->first();

        if ($asistencia) {
            $pausaActiva = Pausa::where('user_id', Auth::id())
                ->whereNull('fin_pausa')
                ->exists();

            if ($pausaActiva) {
                return back()->with(
                    'error',
                    'Primero finaliza la pausa.'
                );
            }

            $asistencia->update([
                'hora_salida' => now()->format('H:i:s'), // FIX: antes era now() (datetime completo)
            ]);

            return back()->with('success', 'Salida registrada.');
        }

        return back()->with('error', 'No tienes una entrada activa hoy.');
    }

    public function iniciarPausa(Request $request)
    {
        $asistencia = Asistencia::where('user_id', Auth::id())
            ->where('fecha', now()->toDateString())
            ->whereNull('hora_salida')
            ->latest()
            ->first();

        if (!$asistencia) {
            return back()->with('error', 'No existe una jornada activa.');
        }

        // Verificar si ya existe una pausa activa
        $pausaActiva = Pausa::where('user_id', Auth::id())
            ->where('asistencia_id', $asistencia->id)
            ->whereNull('fin_pausa')
            ->exists();

        if ($pausaActiva) {
            return back()->with('error', 'Ya tienes una pausa activa.');
        }

        Pausa::create([
            'user_id'       => Auth::id(),
            'asistencia_id' => $asistencia->id,
            'inicio_pausa'  => now()->format('H:i:s'), // FIX: antes era now() (datetime completo)
            'motivo'        => $request->motivo,
            'fecha'         => now()->toDateString(),
        ]);

        return back()->with('success', 'Pausa iniciada.');
    }

    public function finalizarPausa()
    {
        $pausa = Pausa::where('user_id', Auth::id())
            ->whereNull('fin_pausa')
            ->latest()
            ->first();

        if (!$pausa) {
            return back()->with('error', 'No tienes una pausa activa.');
        }

        $pausa->update([
            'fin_pausa' => now()->format('H:i:s'), // FIX: antes era now() (datetime completo)
        ]);

        return back()->with('success', 'Pausa finalizada.');
    }

    public function store(Request $request)
    {
        \App\Models\Asistencia::create([
            'user_id'      => Auth::id(),
            'hora_entrada' => now()->format('H:i:s'), // Esto ya estaba correcto (formato 24h)
            'fecha'        => now()->format('Y-m-d'),
        ]);

        return back()->with('success', 'Checado');
    }
}