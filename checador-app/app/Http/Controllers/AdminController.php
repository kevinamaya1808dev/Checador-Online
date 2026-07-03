<?php

namespace App\Http\Controllers;

use App\Models\Asistencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index() 
    {
        // Traemos las asistencias con el usuario relacionado para evitar errores de carga
        $asistencias = Asistencia::with('user')->latest()->get();
        
        return view('admin.dashboard', compact('asistencias'));
    }

    public function storeBecario(Request $request) 
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:8',
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

}