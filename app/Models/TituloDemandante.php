<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class TituloDemandante extends Pivot
{
    protected $table = 'titulos_demandante';
    
    protected $fillable = [
        'id_dem',
        'id_titulo',
        'centro',
        'año',
        'cursando'
    ];

    public $timestamps = false;

    /**
     * Relación con el modelo Demandante
     */
    public function demandante()
    {
        return $this->belongsTo(Demandante::class, 'id_dem');
    }

    /**
     * Relación con el modelo Titulo
     */
    public function titulo()
    {
        return $this->belongsTo(Titulo::class, 'id_titulo');
    }
}