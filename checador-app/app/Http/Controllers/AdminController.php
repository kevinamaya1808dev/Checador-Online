<?php

namespace App\Http\Controllers;

use App\Models\Asistencia;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\SimpleExcel\SimpleExcelWriter;
use Carbon\Carbon; // Importante para el cálculo del tiempo y semanas

class AdminController extends Controller
{
    public function index() 
    {
        $hoy = now()->toDateString();
        
        // Obtenemos las asistencias (sin tocar tu lógica original)
        $asistencias = Asistencia::with(['user.pausas' => function($query) use ($hoy) {
            $query->where('fecha', $hoy)->whereNull('fin_pausa');
        }])->latest()->get(); 

        // Creamos un arreglo con los datos básicos para que tu Modal de vista previa funcione sin dar error
        $datosReporte = [
            ['Total Registros', $asistencias->count() . ' asistencias'],
            ['Fecha del Reporte', Carbon::parse($hoy)->translatedFormat('d \d\e F \d\e Y')],
        ];

        return view('admin.dashboard',compact('asistencias','datosReporte')
);
    }
    public function storeBecario(Request $request) 
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'becario',
        ]);

        return back()->with('success', 'Becario registrado correctamente.');
    }

    public function exportarReporte()
    {
        // 1. Obtenemos todas las asistencias con su respectivo usuario
        $asistencias = Asistencia::with('user')->get();
        $filasExcel = [];

        // 2. Procesamos cada fila para calcular horas y agrupar por fechas
        foreach ($asistencias as $a) {
            $totalHorasStr = 'Pendiente';

            // Si hay entrada y salida, calculamos el total con formato "Xh Ym"
            if ($a->hora_entrada && $a->hora_salida) {
                $entrada = Carbon::parse($a->hora_entrada);
                $salida = Carbon::parse($a->hora_salida);
                
                // Obtenemos el total en minutos primero
                $totalMinutos = $entrada->diffInMinutes($salida);
                
                // Separamos en horas completas y los minutos restantes
                $horas = floor($totalMinutos / 60);
                $minutos = $totalMinutos % 60;
                
                // Formateamos la cadena (ej. "8h 30m")
                $totalHorasStr = "{$horas}h {$minutos}m";
            }

            // Utilizamos Carbon para extraer la semana y el mes de la fecha registrada
            $fechaObj = Carbon::parse($a->fecha);

            $filasExcel[] = [
                'Becario'     => $a->user ? $a->user->name : 'Usuario Eliminado',
                'Día / Fecha' => $a->fecha,
                'Semana'      => 'Semana ' . $fechaObj->weekOfYear,
                'Mes'         => ucfirst($fechaObj->translatedFormat('F Y')),
                'Entrada'     => $a->hora_entrada ?? 'Sin registro',
                'Salida'      => $a->hora_salida ?? 'Sin registro',
                'Total Horas' => $totalHorasStr,
            ];
        }

        $filename = "reporte_asistencias_" . date('Y-m-d') . ".xlsx";

        // 3. Spatie Simple Excel crea el archivo y lo descarga en el navegador al instante
        return SimpleExcelWriter::streamDownload($filename)
            ->addRows($filasExcel)
            ->toBrowser();
    }

    public function update(Request $request, $id)
    {
        $request->validate(['name' => 'required', 'role' => 'required']);
        
        $user = User::findOrFail($id);
        $user->update([
            'name' => $request->name,
            'role' => $request->role,
        ]);

        return back()->with('success', 'Usuario actualizado con éxito.');
    }

    public function tiempos()
{
    $hoy = today()->toDateString();

    $asistencias = Asistencia::with(['pausas', 'user'])
        ->whereDate('fecha', $hoy)
        ->latest()
        ->get();

    $finJornada = Carbon::parse($hoy . ' 18:00:00');

    return response()->json(
        $asistencias->map(function ($a) use ($finJornada) {
            $enPausa = $a->pausas->contains(fn ($p) => is_null($p->fin_pausa));
            $turnoTerminado = (bool) $a->hora_salida;

            if ($turnoTerminado) {
                $estado = ['texto' => 'Turno terminado', 'clase' => 'text-bg-secondary'];
            } elseif ($enPausa) {
                $estado = ['texto' => 'En descanso', 'clase' => 'text-bg-info text-dark'];
            } else {
                $estado = ['texto' => 'Activo', 'clase' => 'text-bg-success'];
            }

            return [
                'id'                 => $a->id,
                'user_name'          => $a->user->name,
                'user_inicial'       => strtoupper(substr($a->user->name, 0, 1)),
                'fecha'              => \Carbon\Carbon::parse($a->fecha)->format('d/m/Y'),
                'hora_entrada'       => $a->hora_entrada ? \Carbon\Carbon::parse($a->hora_entrada)->format('h:i A') : '--:--',
                'hora_salida'        => $a->hora_salida ? \Carbon\Carbon::parse($a->hora_salida)->format('h:i A') : '---',
                'pausas_segundos' => $a->tiempoPausasSegundos(),
                'trabajado_segundos' => $a->tiempoTrabajado(),
                'extras_salida_segundos' => $a->tiempoExtraSalida(),
                'extras_segundos' => $a->tiempoHorasExtras(),
                'extras_entrada_segundos' => $a->tiempoExtraEntrada(),
                'en_pausa'           => $enPausa,
                'turno_terminado'    => $turnoTerminado,
                'extras_creciendo'   => !$turnoTerminado && now()->gt($finJornada),
                'estado'             => $estado,
            ];
        })
    );
}
}