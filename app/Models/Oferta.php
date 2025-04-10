<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Oferta extends Model
{
    protected $table = 'oferta';

    protected $fillable = [
        'nombre',
        'breve_desc',
        'desc',
        'fecha_pub',
        'num_puesto',
        'horario',
        'obs',
        'abierta',
        'fecha_cierre',
        'id_emp',
        'id_tipo_cont',
    ];

    public function empresa()
{
    return $this->belongsTo(Empresa::class, 'id_emp');
}

    public function tipoContrato()
    {
        return $this->belongsTo(TipoContrato::class, 'id_tipo_cont');
    }

    public function titulos()
    {
        return $this->belongsToMany(Titulo::class, 'titulos_oferta', 'id_oferta', 'id_titulo');
    }

    public function demandantesInscritos()
    {
        return $this->belongsToMany(Demandante::class, 'apuntados_oferta', 'id_oferta', 'id_demandante')
                    ->using(ApuntadosOferta::class)            
                    ->withPivot(['adjudicada', 'fecha']);
    }

    public $timestamps = false;
}

