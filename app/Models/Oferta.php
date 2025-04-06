<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Oferta extends Model
{
    use HasFactory;

    protected $table = 'oferta';

    public $timestamps = false;

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

    // Relación con empresa
    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'id_emp');
    }

    // Relación con tipo de contrato
    public function tipoContrato()
    {
        return $this->belongsTo(TipoContrato::class, 'id_tipo_cont');
    }

    // Relación con candidatos inscritos
    public function candidatos()
    {
        return $this->belongsToMany(Demandante::class, 'apuntados_oferta', 'id_oferta', 'id_demandante')
                    ->withPivot('adjudicada', 'fecha');
    }

    // Relación con los títulos requeridos por la oferta
    public function titulos()
    {
        return $this->belongsToMany(Titulo::class, 'titulos_oferta', 'id_oferta', 'id_titulo');
    }
}

