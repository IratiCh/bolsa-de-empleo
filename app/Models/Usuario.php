<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'id', 'email', 'contrasena_hash', 'rol', 'id_rol'
    ];

    protected $hidden = [
        'contrasena_hash',
    ];
    
    public function demandante()
    {
        return $this->belongsTo(Demandante::class, 'id_rol', 'id');
    }

    public $timestamps = false;
}
