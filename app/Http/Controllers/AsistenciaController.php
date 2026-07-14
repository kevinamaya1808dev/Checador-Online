<?php

namespace App\Http\Controllers;

use App\Models\Asistencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AsistenciaController extends Controller
{
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
            ->first();
            
        if ($asistencia) {
            $asistencia->update(['hora_salida' => now()]);
        }
        return back()->with('success', 'Salida registrada.');
    }
}