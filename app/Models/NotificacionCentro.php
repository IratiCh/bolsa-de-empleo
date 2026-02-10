<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificacionCentro extends Model
{
    protected $table = 'notificaciones_centro';

    protected $fillable = [
        'tipo',
        'mensaje',
        'id_oferta',
        'id_empresa',
        'id_demandante',
        'externo_nombre',
        'fecha',
        'leida'
    ];

    public $timestamps = false;
}
