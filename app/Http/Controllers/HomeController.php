<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        // Pasamos todos los usuarios a la vista
        $usuarios = User::all();
        return view('home', compact('usuarios'));
    }

    public function storeUser(Request $request)
    {
        $request->validate(['name' => 'required', 'email' => 'required|email|unique:users', 'password' => 'required|min:6']);
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);
        return back()->with('success', 'Usuario creado.');
    }

    public function deleteUser($id)
    {
        if ($id == 1) {
        return back()->with('error', 'No puedes eliminar al administrador principal.');
        }

        User::findOrFail($id)->delete();
        return back()->with('success', 'Usuario eliminado.');
    }

    public function toggleAdmin($id)
    {
        $user = User::findOrFail($id);
        $user->role = ($user->role === 'admin') ? 'becario' : 'admin';
        $user->save();
        return back()->with('success', 'Permisos actualizados.');
    }

   public function update(Request $request, $id)
{
    // 1. Usamos una transacción para asegurar la integridad
    return DB::transaction(function () use ($request, $id) {
        
        $user = \App\Models\User::findOrFail($id);

        // 2. Asignación explícita campo por campo (evita bloqueos de $fillable)
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;

        // 3. Contraseña opcional
        if ($request->filled('password')) {
            $user->password = \Hash::make($request->password);
        }

        // 4. Guardado forzado
        if ($user->save()) {
            return back()->with('success', 'Usuario actualizado correctamente.');
        }

        return back()->with('error', 'No se pudo guardar el usuario.');
    });
}
}