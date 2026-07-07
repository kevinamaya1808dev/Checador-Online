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

        if(!$this->hora_entrada){
            return 0;
        }


        $entrada = Carbon::parse($this->hora_entrada);


        $salida = $this->hora_salida
            ? Carbon::parse($this->hora_salida)
            : now();



        $tiempoTotal = $entrada->diffInSeconds($salida);



        $pausas = Pausa::where('user_id',$this->user_id)
            ->where('fecha',$this->fecha)
            ->get();



        $tiempoPausas = 0;


        foreach($pausas as $pausa){

            $tiempoPausas += $pausa->duracion();

        }



        $trabajado = $tiempoTotal - $tiempoPausas;



        return max($trabajado,0);

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

        $pausas = Pausa::where('user_id',$this->user_id)
        ->where('fecha',$this->fecha)
        ->get();


        $total = 0;


        foreach($pausas as $pausa){

            $total += $pausa->duracion();

        }


        return $this->formatoTiempo($total);

    }

}