<?php

namespace App\Http\Controllers;

use App\Models\Asistencia;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon; // Importante para el cálculo del tiempo y semanas

class AdminController extends Controller
{
    public function index() 
    {
        $hoy = now()->toDateString();
        
        // Obtenemos las asistencias (sin tocar tu lógica original)
        $ultimasAsistencias = Asistencia::selectRaw('MAX(id) as id')
    ->groupBy('user_id');

$asistencias = Asistencia::with([
        'user.pausas' => function ($query) use ($hoy) {
            $query->where('fecha', $hoy)
                  ->whereNull('fin_pausa');
        },
        'pausas'
    ])
    ->whereIn('id', $ultimasAsistencias)
    ->latest()
    ->get(); 

        // Creamos un arreglo con los datos básicos para que tu Modal de vista previa funcione sin dar error
        $datosReporte = [
            ['Total Registros', $asistencias->count() . ' asistencias'],
            ['Fecha del Reporte', Carbon::parse($hoy)->translatedFormat('d \d\e F \d\e Y')],
        ];

        return view('admin.dashboard',compact('asistencias','datosReporte')
);
    }

    public function show(Asistencia $asistencia)
{
    $asistencia->load([
        'user',
        'pausas'
    ]);

    return response()->json($asistencia);
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

    $ultimasAsistencias = Asistencia::selectRaw('MAX(id) as id')
    ->groupBy('user_id');

$asistencias = Asistencia::with([
        'pausas',
        'user'
    ])
    ->whereIn('id', $ultimasAsistencias)
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
                'user_id'            => $a->user_id,
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