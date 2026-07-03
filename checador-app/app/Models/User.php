<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

   protected $fillable = ['name', 'email', 'password', 'role'];

    protected $hidden = [
        'password', 'remember_token',
    ];

    // Método para verificar si es admin
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function asistencias() { return $this->hasMany(Asistencia::class); }
}