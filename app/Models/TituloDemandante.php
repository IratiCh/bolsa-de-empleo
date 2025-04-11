<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot; // Clase base para modelos de tabla pivot en Eloquent.

class TituloDemandante extends Pivot
{
    // Especifica el nombre de la tabla pivot asociada en la base de datos.
    protected $table = 'titulos_demandante';

    // Define los campos que pueden ser asignados de forma masiva.
    protected $fillable = [
        'id_dem',
        'id_titulo',
        'centro',
        'año',
        'cursando'
    ];

    // Indica que este modelo no utiliza las columnas de timestamps (`created_at`, `updated_at`).
    public $timestamps = false;

    /**
     * Relación: este registro pertenece a un demandante.
     **/
    public function demandante()
    {
        // Define la relación con el modelo "Demandante".
        // La clave foránea en esta tabla pivot es "id_dem".
        return $this->belongsTo(Demandante::class, 'id_dem');
    }

    /**
     * Relación: este registro pertenece a un título.
     **/
    public function titulo()
    {
        // Define la relación con el modelo "Titulo".
        // La clave foránea en esta tabla pivot es "id_titulo".
        return $this->belongsTo(Titulo::class, 'id_titulo');
    }
}