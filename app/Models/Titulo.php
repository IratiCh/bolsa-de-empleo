<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // Trait para usar factorías en pruebas.
use Illuminate\Database\Eloquent\Model; // Clase base para los modelos de Eloquent.

class Titulo extends Model
{
    // Permite crear factorías para generar datos de prueba.
    use HasFactory;

    // Define los campos que pueden ser asignados de forma masiva.
    protected $fillable = [
        'nombre'
    ];

    /**
     * Relación: un título puede estar asociado a múltiples demandantes.
     **/
    public function demandantes()
    {
        // Define la relación con el modelo "Demandante".
        // Usa la tabla pivot "titulos_demandante" para conectar títulos y demandantes.
        // 'id_titulo' es el campo del título en la tabla pivot.
        // 'id_dem' es el campo del demandante en la tabla pivot.
        // Incluye columnas adicionales de la tabla pivot: "centro", "año" y "cursando".
        return $this->belongsToMany(Demandante::class, 'titulos_demandante', 'id_titulo', 'id_dem')
                    ->withPivot('centro', 'año', 'cursando');
    }

    // Indica que este modelo no utiliza las columnas de timestamps (`created_at`, `updated_at`).
    public $timestamps = false;
}