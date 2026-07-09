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


    $entrada = \Carbon\Carbon::parse(
        $this->fecha.' '.$this->hora_entrada
    );


    $salida = $this->hora_salida
        ? \Carbon\Carbon::parse(
            $this->fecha.' '.$this->hora_salida
        )
        : now();


    $trabajado = $entrada->diffInSeconds($salida);


    $pausas = $this->tiempoPausasSegundos();


    return max(
        0,
        $trabajado - $pausas
    );
}



    public function formatoTiempo($segundos)
    {

        return gmdate(
            "H:i:s",
            $segundos
        );

    }


    public function tiempoPausasSegundos()
{
    $segundos = 0;


    foreach ($this->pausas as $pausa) {


        if (!$pausa->inicio_pausa) {
            continue;
        }


        $inicio = \Carbon\Carbon::parse(
            $this->fecha.' '.$pausa->inicio_pausa
        );


        $fin = $pausa->fin_pausa
            ? \Carbon\Carbon::parse(
                $this->fecha.' '.$pausa->fin_pausa
            )
            : now();


        $segundos += $inicio->diffInSeconds($fin);

    }


    return $segundos;
}

    public function tiempoPausas()
{
    return $this->formatoTiempo($this->tiempoPausasSegundos());
}
public function pausas()
{
    return $this->hasMany(Pausa::class);
}

public function tiempoExtraEntrada()
{
    if (!$this->hora_entrada) {
        return 0;
    }

    $inicioJornada = \Carbon\Carbon::parse($this->fecha . ' 09:00:00');
    $entrada = \Carbon\Carbon::parse($this->fecha . ' ' . $this->hora_entrada);

    // Si llegó después de las 09:00, no hay horas extra de entrada
    if (!$entrada->lt($inicioJornada)) {
        return 0;
    }

    // Horas extra brutas
    $segundos = $entrada->diffInSeconds($inicioJornada);

    // Restar pausas ocurridas antes de las 09:00
    $segundos -= $this->segundosPausaEnIntervalo(
        $entrada,
        $inicioJornada
    );

    return max(0, $segundos);
}

public function tiempoExtraSalida()
{
    if (!$this->hora_entrada) {
        return 0;
    }

    $finJornada = \Carbon\Carbon::parse($this->fecha . ' 18:00:00');

    if ($this->hora_salida) {

    $salida = \Carbon\Carbon::parse(
        $this->fecha.' '.$this->hora_salida
    );

} else {


    if ($this->tienePausaActiva()) {

        $ultimaPausa = $this->pausas()
            ->whereNull('fin_pausa')
            ->latest()
            ->first();


        $salida = \Carbon\Carbon::parse(
            $this->fecha.' '.$ultimaPausa->inicio_pausa
        );


    } else {

        $salida = now();

    }

}

    // Si aún no son las 18:00, no hay horas extra
    if (!$salida->gt($finJornada)) {
        return 0;
    }

    // Horas extra brutas
    $segundos = $finJornada->diffInSeconds($salida);

    // Restar pausas después de las 18:00
    $segundos -= $this->segundosPausaEnIntervalo(
        $finJornada,
        $salida
    );

    return max(0, $segundos);
}

public function tiempoHorasExtras()
{
    return $this->tiempoExtraEntrada() + $this->tiempoExtraSalida();
}

public function horasExtrasEntradaFormato()
{
    return $this->formatoTiempo(
        $this->tiempoExtraEntrada()
    );
}


public function horasExtrasSalidaFormato()
{
    return $this->formatoTiempo(
        $this->tiempoExtraSalida()
    );
}


public function horasExtrasTotalFormato()
{
    return $this->formatoTiempo(
        $this->tiempoHorasExtras()
    );
}

protected function segundosPausaEnIntervalo($inicioIntervalo, $finIntervalo)
{
    $segundos = 0;

    foreach ($this->pausas as $pausa) {

        if (!$pausa->inicio_pausa) {
            continue;
        }

        $inicioPausa = \Carbon\Carbon::parse(
            $this->fecha.' '.$pausa->inicio_pausa
        );

        $finPausa = $pausa->fin_pausa
            ? \Carbon\Carbon::parse($this->fecha.' '.$pausa->fin_pausa)
            : now();

        // No hay traslape entre la pausa y el intervalo
        if ($finPausa <= $inicioIntervalo || $inicioPausa >= $finIntervalo) {
            continue;
        }

        // Obtener el tramo de la pausa que sí cae dentro del intervalo
        $inicioReal = $inicioPausa->greaterThan($inicioIntervalo)
            ? $inicioPausa
            : $inicioIntervalo;

        $finReal = $finPausa->lessThan($finIntervalo)
            ? $finPausa
            : $finIntervalo;

        $segundos += $inicioReal->diffInSeconds($finReal);
    }

    return $segundos;
}

public function tienePausaActiva()
{
    return $this->pausas()
        ->whereNull('fin_pausa')
        ->exists();
}

}