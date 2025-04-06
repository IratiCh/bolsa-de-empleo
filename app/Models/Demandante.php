<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Demandante extends Model
{
    protected $table = 'demandante';
    protected $fillable = [
        'dni', 'nombre', 'ape1', 'ape2', 'tel_movil', 'email', 'situacion'
    ];

    public $timestamps = false;
}
