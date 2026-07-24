<?php

namespace App\Http\Controllers;

use App\Models\Asistencia;
use App\Models\Pausa;
use App\Support\EstadoTurnoPresenter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BecarioController extends Controller
{
    // Límites de jornada laboral
    private const HORA_APERTURA_REGISTRO = '08:30:00'; // Antes de esto no se puede registrar entrada
    private const HORA_FIN_JORNADA       = '11:31:00'; // A partir de aquí, el tiempo cuenta como hora extra
    private const HORA_CORTE_EXTRAS      = '23:59:59'; // Corte de horas extra (medianoche)

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
        $horaActual  = now();
        $aperturaHoy = Carbon::today()->setTimeFromTimeString(self::HORA_APERTURA_REGISTRO);

        // Restricción: no se puede registrar entrada antes de las 8:30 a.m.
        if ($horaActual->lt($aperturaHoy)) {
            return back()->with(
                'error',
                'Aún no puedes registrar tu entrada. El registro de asistencia se habilita a partir de las 8:30 a.m.'
            );
        }

        // Buscar si existe una jornada activa (sin importar si es de hoy o de un día anterior
        // que quedó sin cerrar, para no permitir doble entrada mientras algo sigue abierto).
        $asistenciaActiva = Asistencia::where('user_id', Auth::id())
            ->whereNull('hora_salida')
            ->first();

        if ($asistenciaActiva) {
            if ($asistenciaActiva->fecha === now()->toDateString()) {
                return back()->with('error', 'Ya tienes una jornada activa.');
            }

            // Jornada de un día anterior sin cerrar: la cerramos por corte de horas extra
            // antes de permitir la nueva entrada.
            $asistenciaActiva->update(['hora_salida' => self::HORA_CORTE_EXTRAS]);
        }

        Asistencia::create([
            'user_id'      => Auth::id(),
            'hora_entrada' => $horaActual->format('H:i:s'),
            'fecha'        => now()->toDateString(),
        ]);

        return back()->with('success', 'Entrada registrada correctamente.');
    }

    public function registrarSalida()
    {
        // Buscar la jornada abierta más reciente, sin limitarla a "hoy":
        // si cruzó la medianoche, el registro sigue teniendo la fecha de ayer.
        $asistencia = Asistencia::where('user_id', Auth::id())
            ->whereNull('hora_salida')
            ->orderByDesc('fecha')
            ->first();

        if (!$asistencia) {
            return back()->with('error', 'No tienes una entrada activa hoy.');
        }

        $pausaActiva = Pausa::where('asistencia_id', $asistencia->id)
            ->whereNull('fin_pausa')
            ->exists();

        if ($pausaActiva) {
            return back()->with('error', 'Primero finaliza la pausa.');
        }

        $hoy = now()->toDateString();

        // Corte de horas extra: si la jornada es de un día anterior, ya se cerró
        // automáticamente a las 00:00 y no se permite marcar salida "en vivo".
        if ($asistencia->fecha !== $hoy) {
            $asistencia->update(['hora_salida' => self::HORA_CORTE_EXTRAS]);

            return back()->with(
                'warning',
                'Término de labores: tu jornada del ' .
                    Carbon::parse($asistencia->fecha)->format('d/m/Y') .
                    ' fue cerrada automáticamente por el corte de horas extra (00:00 hrs). ' .
                    'Si detectas alguna inconsistencia, repórtalo con tu administrador.'
            );
        }

        $horaActual = now();
        $finJornada = Carbon::today()->setTimeFromTimeString(self::HORA_FIN_JORNADA);

        $asistencia->update(['hora_salida' => $horaActual->format('H:i:s')]);

        if ($horaActual->gt($finJornada)) {
            return back()->with(
                'success',
                'Salida registrada correctamente. A partir de las 6:00 p.m. el tiempo se contabiliza como horas extra.'
            );
        }

        return back()->with('success', 'Salida registrada correctamente.');
    }

    public function iniciarPausa(Request $request)
    {
        $asistencia = Asistencia::where('user_id', Auth::id())
            ->whereNull('hora_salida')
            ->latest('fecha')
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
            'inicio_pausa'  => now()->format('H:i:s'),
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
            'fin_pausa' => now()->format('H:i:s'),
        ]);

        return back()->with('success', 'Pausa finalizada.');
    }

    public function store(Request $request)
    {
        \App\Models\Asistencia::create([
            'user_id'      => Auth::id(),
            'hora_entrada' => now()->format('H:i:s'),
            'fecha'        => now()->format('Y-m-d'),
        ]);

        return back()->with('success', 'Checado');
    }
}