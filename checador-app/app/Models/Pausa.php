<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Pausa extends Model
{
    protected $fillable = [
        'user_id',
        'asistencia_id',
        'inicio_pausa',
        'fin_pausa',
        'motivo',
        'fecha'
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }


    // Calcula duración individual de una pausa
    public function duracion()
    {
        if (!$this->inicio_pausa) {
            return 0;
        }

        $inicio = Carbon::parse($this->inicio_pausa);


        // Si sigue en pausa toma la hora actual
        $fin = $this->fin_pausa
            ? Carbon::parse($this->fin_pausa)
            : now();


        return $inicio->diffInSeconds($fin);
    }
  public function asistencia()
{
    return $this->belongsTo(
        Asistencia::class,
        'asistencia_id'
    );
}
}