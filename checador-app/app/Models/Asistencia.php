<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asistencia extends Model
{
    protected $fillable = ['user_id', 'hora_entrada', 'hora_salida', 'fecha'];

    public function user() { return $this->belongsTo(User::class); }
}