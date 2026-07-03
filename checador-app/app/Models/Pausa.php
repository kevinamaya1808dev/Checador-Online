<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pausa extends Model
{
 protected $fillable = ['user_id', 'inicio_pausa', 'fin_pausa', 'motivo', 'fecha'];   //
}
