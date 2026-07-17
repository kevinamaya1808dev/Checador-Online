<?php

namespace App\Http\Controllers;

use App\Models\Asistencia;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * CAPA DE SEGURIDAD: Validación interna para evitar acceso de no administradores.
     */
    private function authorizeAdmin()
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Acceso no autorizado.');
        }
    }

    public function index()
    {
        $this->authorizeAdmin();

        $hoy = now()->toDateString();

        $totalBecarios = User::where('role', 'becario')->count();

        $datosReporte = [
            ['Total Becarios', $totalBecarios . ' registrados'],
            [
                'Fecha del Reporte',
                Carbon::parse($hoy)->translatedFormat('d \d\e F \d\e Y'),
            ],
        ];

        return view('admin.dashboard', compact('datosReporte'));
    }

    public function show(Asistencia $asistencia)
    {
        $this->authorizeAdmin();
        
        $asistencia->load(['user', 'pausas']);

        return response()->json($asistencia);
    }

    public function storeBecario(Request $request)
    {
        $this->authorizeAdmin();

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
        $this->authorizeAdmin();

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
        $this->authorizeAdmin();

        $hoy = today()->toDateString();
        $finJornada = Carbon::parse($hoy . ' 18:00:00');

        $becarios = User::where('role', 'becario')
            ->with(['asistencias' => function ($q) use ($hoy) {
                $q->where('fecha', $hoy)
                  ->with('pausas')
                  ->latest();
            }])
            ->orderBy('name')
            ->get();

        $data = $becarios->map(function ($user) use ($finJornada) {

            /** @var Asistencia|null $a */
            $a = $user->asistencias->first();

            // Becario sin marcar entrada hoy todavía
            if (!$a) {
                return [
                    'id'                      => null,
                    'user_id'                 => $user->id,
                    'user_name'               => $user->name,
                    'user_inicial'            => strtoupper(substr($user->name, 0, 1)),
                    'fecha'                   => $finJornada->format('d/m/Y'),
                    'hora_entrada'            => '--:--',
                    'hora_salida'             => '---',
                    'pausas_segundos'         => 0,
                    'trabajado_segundos'      => 0,
                    'extras_salida_segundos'  => 0,
                    'extras_segundos'         => 0,
                    'extras_entrada_segundos' => 0,
                    'en_pausa'                => false,
                    'turno_terminado'         => false,
                    'extras_creciendo'        => false,
                    'sin_registro'            => true,
                    // Conversión a Tailwind: text-bg-dark -> bg-gray-800 text-white
                    'estado' => ['texto' => 'Sin registrar', 'clase' => 'bg-gray-800 text-white px-2 py-1 rounded-md'],
                ];
            }

            $enPausa = $a->tienePausaActiva();
            $turnoTerminado = (bool) $a->hora_salida;

            if ($turnoTerminado) {
                // Conversión a Tailwind: text-bg-secondary -> bg-gray-500 text-white
                $estado = ['texto' => 'Turno terminado', 'clase' => 'bg-gray-500 text-white px-2 py-1 rounded-md'];
            } elseif ($enPausa) {
                // Conversión a Tailwind: text-bg-info text-dark -> bg-sky-500 text-white
                $estado = ['texto' => 'En descanso', 'clase' => 'bg-sky-500 text-white px-2 py-1 rounded-md'];
            } else {
                // Conversión a Tailwind: text-bg-success -> bg-green-600 text-white
                $estado = ['texto' => 'Activo', 'clase' => 'bg-green-600 text-white px-2 py-1 rounded-md'];
            }

            return [
                'id'                      => $a->id,
                'user_id'                 => $user->id,
                'user_name'               => $user->name,
                'user_inicial'            => strtoupper(substr($user->name, 0, 1)),
                'fecha'                   => Carbon::parse($a->fecha)->format('d/m/Y'),
                'hora_entrada'            => $a->hora_entrada ? Carbon::parse($a->hora_entrada)->format('h:i A') : '--:--',
                'hora_salida'             => $a->hora_salida ? Carbon::parse($a->hora_salida)->format('h:i A') : '---',
                'pausas_segundos'         => $a->tiempoPausasSegundos(),
                'trabajado_segundos'      => $a->tiempoTrabajado(),
                'extras_salida_segundos'  => $a->tiempoExtraSalida(),
                'extras_segundos'         => $a->tiempoHorasExtras(),
                'extras_entrada_segundos' => $a->tiempoExtraEntrada(),
                'en_pausa'                => $enPausa,
                'turno_terminado'         => $turnoTerminado,
                'extras_creciendo'        => !$turnoTerminado && now()->gt($finJornada),
                'sin_registro'            => false,
                'estado'                  => $estado,
            ];
        });

        return response()->json($data->values());
    }
}