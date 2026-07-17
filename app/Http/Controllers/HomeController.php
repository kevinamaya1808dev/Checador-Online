<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    private function authorizeAdmin()
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Acceso denegado.');
        }
    }

    public function index()
    {
        // Accesible para todos los usuarios logueados
        $usuarios = User::all();
        return view('home', compact('usuarios'));
    }

    public function storeUser(Request $request)
    {
        $this->authorizeAdmin();

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
        $this->authorizeAdmin();

        if ($id == 1) {
            return back()->with('error', 'No puedes eliminar al administrador principal.');
        }

        User::findOrFail($id)->delete();
        return back()->with('success', 'Usuario eliminado.');
    }

    public function toggleAdmin($id)
    {
        $this->authorizeAdmin();

        $user = User::findOrFail($id);
        $user->role = ($user->role === 'admin') ? 'becario' : 'admin';
        $user->save();
        return back()->with('success', 'Permisos actualizados.');
    }

    public function update(Request $request, $id)
    {
        $this->authorizeAdmin();

        return DB::transaction(function () use ($request, $id) {
            $user = User::findOrFail($id);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->role = $request->role;

            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }

            if ($user->save()) {
                return back()->with('success', 'Usuario actualizado correctamente.');
            }

            return back()->with('error', 'No se pudo guardar el usuario.');
        });
    }
}