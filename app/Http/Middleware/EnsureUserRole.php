<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle(Request $request, Closure $next, string $role)
    {
        // [BLOQUEO DE SEGURIDAD]: Validación estricta de autenticación
        // Si no hay usuario, abortar el acceso inmediatamente.
        if (!$request->user()) {
            return redirect('/login');
        }

        // [BLOQUEO DE SEGURIDAD]: Validación estricta de rol
        // Si el usuario existe pero su rol no coincide, abortar con error 403 (Prohibido).
        // Usar abort(403) es más seguro que redirigir, ya que detiene el ciclo 
        // de la petición por completo, evitando cualquier posible bug de redirección.
        if ($request->user()->role !== $role) {
            abort(403, 'Acceso denegado: No cuenta con los permisos necesarios.');
        }

        return $next($request);
    }
}