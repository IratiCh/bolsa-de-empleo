<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Titulo extends Model
{
    use HasFactory;

    protected $table = 'titulos';

    public $timestamps = false;

    protected $fillable = [
        'nombre',
    ];

    // Relación con demandantes
    public function demandantes()
    {
        return $this->belongsToMany(Demandante::class, 'titulos_demandante', 'id_titulo', 'id_dem')
            ->withPivot('centro', 'año', 'cursando');
    }

    // Relación con empresas
    public function empresas()
    {
        return $this->belongsToMany(Empresa::class, 'titulos_empresa', 'id_titulo', 'id_emp');
    }

    // Relación con ofertas
    public function ofertas()
    {
        return $this->belongsToMany(Oferta::class, 'titulos_oferta', 'id_titulo', 'id_oferta');
    }
}
