<?php

namespace App\Http\Controllers;

use App\Models\Asistencia;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
   public function index() 
{
    $hoy = now()->toDateString();
    
    // Cambiamos '->where' por '->latest()' para traer todo y que se vea bien
    $asistencias = Asistencia::with(['user.pausas' => function($query) use ($hoy) {
        $query->where('fecha', $hoy)->whereNull('fin_pausa');
    }])->latest()->get(); // <-- 'latest()' trae todo ordenado por fecha

    return view('admin.dashboard', compact('asistencias'));
}

    public function storeBecario(Request $request) 
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:8|confirmed',
    ]);

    \App\Models\User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => 'becario',
    ]);

    return back()->with('success', 'Becario registrado correctamente.');
}

public function exportarReporte()
{
    $asistencias = \App\Models\Asistencia::with('user')->get();
    $filename = "reporte_asistencias_" . date('Y-m-d') . ".csv";

    $handle = fopen('php://output', 'w');
    fputcsv($handle, ['Becario', 'Fecha', 'Entrada', 'Salida']);

    foreach ($asistencias as $a) {
        fputcsv($handle, [$a->user->name, $a->fecha, $a->hora_entrada, $a->hora_salida]);
    }

    fclose($handle);
    return response()->stream(function() use ($handle) {
        // La lógica de stream de Laravel
    }, 200, [
        "Content-type" => "text/csv",
        "Content-Disposition" => "attachment; filename=$filename",
    ]);
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