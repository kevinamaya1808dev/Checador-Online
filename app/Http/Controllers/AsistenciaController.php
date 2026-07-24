<?php

namespace App\Http\Controllers;

use App\Models\Asistencia;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AsistenciaController extends Controller
{
    // Límites de jornada laboral
    private const HORA_APERTURA_REGISTRO = '11:30:00'; // Antes de esto no se puede registrar entrada
    private const HORA_FIN_JORNADA       = '18:00:00'; // A partir de aquí, el tiempo cuenta como hora extra
    private const HORA_CORTE_EXTRAS      = '23:59:59'; // Corte de horas extra (medianoche)

    public function registrarEntrada()
    {
        if (!Auth::check()) return redirect('/login');

        $horaActual     = now();
        $aperturaHoy    = Carbon::today()->setTimeFromTimeString(self::HORA_APERTURA_REGISTRO);


dd([
    'hora_actual' => $horaActual->format('H:i:s'),
    'apertura_hoy' => $aperturaHoy->format('H:i:s'),
    'comparacion_lt' => $horaActual->lt($aperturaHoy), // true = debería bloquear
]);

        // Restricción: no se puede registrar entrada antes de las 8:30 a.m.
        if ($horaActual->lt($aperturaHoy)) {
            return back()->with(
                'error',
                'Aún no puedes registrar tu entrada. El registro de asistencia se habilita a partir de las 8:30 a.m.'
            );
        }

        // Evitar doble registro el mismo día
        $existente = Asistencia::where('user_id', Auth::id())
            ->where('fecha', now()->toDateString())
            ->first();

        if ($existente) {
            return back()->with(
                'error',
                'Ya cuentas con un registro de entrada correspondiente al día de hoy.'
            );
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
        if (!Auth::check()) return redirect('/login');

        // Buscamos el registro abierto más reciente del usuario,
        // sin limitarnos a "hoy": si la jornada cruzó la medianoche,
        // el registro sigue teniendo la fecha de ayer.
        $asistencia = Asistencia::where('user_id', Auth::id())
            ->whereNull('hora_salida')
            ->orderByDesc('fecha')
            ->first();

        if (!$asistencia) {
            return back()->with(
                'error',
                'No se encontró un registro de entrada activo para poder registrar tu salida.'
            );
        }

        $hoy = now()->toDateString();

        // Corte de horas extra: si el registro pertenece a un día anterior,
        // la jornada ya fue cerrada automáticamente a las 00:00 y no se
        // permite marcar la salida "en vivo" (rompería el cálculo de tiempos).
        if ($asistencia->fecha !== $hoy) {
            $asistencia->update([
                'hora_salida' => self::HORA_CORTE_EXTRAS,
            ]);

            return back()->with(
                'warning',
                'Término de labores: tu jornada del ' .
                    Carbon::parse($asistencia->fecha)->format('d/m/Y') .
                    ' fue cerrada automáticamente por el corte de horas extra (00:00 hrs). ' .
                    'Si detectas alguna inconsistencia, repórtalo con tu administrador.'
            );
        }

        $horaActual  = now();
        $finJornada  = Carbon::today()->setTimeFromTimeString(self::HORA_FIN_JORNADA);

        $asistencia->update(['hora_salida' => $horaActual->format('H:i:s')]);

        // Mensaje distinto si la salida ocurre después del horario laboral (6:00 p.m.)
        if ($horaActual->gt($finJornada)) {
            return back()->with(
                'success',
                'Salida registrada correctamente. A partir de las 6:00 p.m. el tiempo se contabiliza como horas extra.'
            );
        }

        return back()->with('success', 'Salida registrada correctamente.');
    }
}