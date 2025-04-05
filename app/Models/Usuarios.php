<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuarios extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'id', 'email', 'contrasena_hash', 'rol', 'id_rol'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];
}
