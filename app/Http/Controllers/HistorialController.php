<?php

namespace App\Http\Controllers;

use App\Models\Asistencia;
use Illuminate\Http\Request;

class HistorialController extends Controller
{
   public function index(Request $request)
{
    $query = Asistencia::with([
        'user',
        'pausas'
    ]);

    /*
    |--------------------------------------------------------------------------
    | BUSCADOR
    |--------------------------------------------------------------------------
    */

    if ($request->filled('search')) {

        $buscar = $request->search;

        $query->whereHas('user', function ($q) use ($buscar) {

            $q->where('name', 'like', "%{$buscar}%")
              ->orWhere('email', 'like', "%{$buscar}%");

        });

    }

/*
|--------------------------------------------------------------------------
| FILTRO POR SEMANA DEL MES
|--------------------------------------------------------------------------
*/

if ($request->filled('semana')) {

    switch ($request->semana) {

        case 1:
            $query->whereDay('fecha', '>=', 1)
                  ->whereDay('fecha', '<=', 7);
            break;

        case 2:
            $query->whereDay('fecha', '>=', 8)
                  ->whereDay('fecha', '<=', 14);
            break;

        case 3:
            $query->whereDay('fecha', '>=', 15)
                  ->whereDay('fecha', '<=', 21);
            break;

        case 4:
            $query->whereDay('fecha', '>=', 22)
                  ->whereDay('fecha', '<=', 28);
            break;

        case 5:
            $query->whereDay('fecha', '>=', 29);
            break;

    }

}

/*
|--------------------------------------------------------------------------
| FILTRO MES
|--------------------------------------------------------------------------
*/

if ($request->filled('mes')) {

    $query->whereMonth(
        'fecha',
        $request->mes
    );

}

    /*
    |--------------------------------------------------------------------------
    | ORDENAMIENTO
    |--------------------------------------------------------------------------
    */

    switch ($request->get('order')) {

        case 'az':

            $query->join('users', 'users.id', '=', 'asistencias.user_id')
                  ->orderBy('users.name', 'asc')
                  ->select('asistencias.*');

            break;

        case 'za':

            $query->join('users', 'users.id', '=', 'asistencias.user_id')
                  ->orderBy('users.name', 'desc')
                  ->select('asistencias.*');

            break;

        case 'oldest':

            $query->orderBy('fecha', 'asc');

            break;

        default:

            $query->orderBy('fecha', 'desc');

            break;
    }

    /*
|--------------------------------------------------------------------------
| DATOS PARA FILTROS
|--------------------------------------------------------------------------
*/



$meses = Asistencia::selectRaw('MONTH(fecha) as numero_mes')
    ->distinct()
    ->orderBy('numero_mes')
    ->get();

    $asistencias = $query
        ->paginate(15)
        ->withQueryString();

    return view(
    'admin.historial.index',
    compact(
        'asistencias',
        'meses'
    )
);
}
}