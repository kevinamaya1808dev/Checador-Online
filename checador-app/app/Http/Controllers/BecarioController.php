<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BecarioController extends Controller
{
    //
    public function index() {
    return view('becario.dashboard');
    
}
public function store(Request $request) {
    \App\Models\Asistencia::create([
        'user_id' => Auth::id(),
        'entrada' => now(),
        'fecha' => now()->format('Y-m-d'),
    ]);
    return back()->with('success', 'Checado con éxito');
}
}
