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

}