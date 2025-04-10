<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Demandante extends Model
{
    protected $table = 'demandante';

    protected $fillable = [
        'dni', 'nombre', 'ape1', 'ape2', 'tel_movil', 'email', 'situacion'
    ];

    public function usuario()
    {
        return $this->hasOne(Usuario::class, 'id_rol', 'id')->where('rol', 'demandante');
    }

    public function titulos()
    {
        return $this->belongsToMany(Titulo::class, 'titulos_demandante', 'id_dem', 'id_titulo')
                    ->withPivot(['centro', 'año', 'cursando']);
    }

    public function ofertasInscritas()
    {
        return $this->belongsToMany(Oferta::class, 'apuntados_oferta', 'id_demandante', 'id_oferta')
                    ->using(ApuntadosOferta::class)
                    ->withPivot(['adjudicada', 'fecha']);
    }

    public $timestamps = false;
}
