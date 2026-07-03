<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureUserRole
{
    public function handle(Request $request, Closure $next, string $role)
    {
        if ($request->user() && $request->user()->role !== $role) {
            // Si no es el rol correcto, lo enviamos al home o login
            return redirect('/home');
        }

        return $next($request);
    }
}