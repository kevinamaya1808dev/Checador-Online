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

        return view('admin.dashboard', compact('asistencias', 'datosReporte'));
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
}