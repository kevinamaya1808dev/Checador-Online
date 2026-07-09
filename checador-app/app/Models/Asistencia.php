<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Asistencia extends Model
{

    protected $fillable = [
        'user_id',
        'hora_entrada',
        'hora_salida',
        'fecha'
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }



    // Tiempo trabajado quitando pausas
    public function tiempoTrabajado()
{
    if (!$this->hora_entrada) {
        return 0;
    }

    $entrada = \Carbon\Carbon::parse($this->hora_entrada);

    $salida = $this->hora_salida
        ? \Carbon\Carbon::parse($this->hora_salida)
        : now();

    $trabajado = $entrada->diffInSeconds($salida);

    $pausas = 0;

    foreach ($this->pausas as $pausa) {

        $inicio = \Carbon\Carbon::parse($pausa->inicio_pausa);

        $fin = $pausa->fin_pausa
            ? \Carbon\Carbon::parse($pausa->fin_pausa)
            : now();

        $pausas += $inicio->diffInSeconds($fin);

    }

    return max(0, $trabajado - $pausas);
}



    public function formatoTiempo($segundos)
    {

        return gmdate(
            "H:i:s",
            $segundos
        );

    }


    public function tiempoPausas()
{
    $segundos = 0;

    foreach ($this->pausas as $pausa) {

        $inicio = \Carbon\Carbon::parse($pausa->inicio_pausa);

        if ($pausa->fin_pausa) {

            $fin = \Carbon\Carbon::parse($pausa->fin_pausa);

        } else {

            $fin = now();

        }

        $segundos += $inicio->diffInSeconds($fin);
    }

    return $this->formatoTiempo($segundos);
}
public function pausas()
{
    return $this->hasMany(Pausa::class);
}

public function tiempoHorasExtras()
{
    if (!$this->hora_entrada || !$this->hora_salida) {
        return 0;
    }

    $inicioJornada = \Carbon\Carbon::parse($this->fecha . ' 09:00:00');
    $finJornada    = \Carbon\Carbon::parse($this->fecha . ' 18:00:00');

    $entrada = \Carbon\Carbon::parse($this->fecha . ' ' . $this->hora_entrada);
    $salida  = \Carbon\Carbon::parse($this->fecha . ' ' . $this->hora_salida);

    $extras = 0;

    // Entrada antes de las 09:00
    if ($entrada->lt($inicioJornada)) {
        $extras += $entrada->diffInSeconds($inicioJornada);
    }

    // Salida después de las 18:00
    if ($salida->gt($finJornada)) {
        $extras += $finJornada->diffInSeconds($salida);
    }

    return $extras;
}
}