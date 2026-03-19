<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // Trait para usar factorías en pruebas.
use Illuminate\Database\Eloquent\Model; // Clase base para los modelos de Eloquent.

class ApuntadosOferta extends Model
{
    use HasFactory;

    // Especifica el nombre de la tabla asociada en la base de datos.
    protected $table = 'apuntados_oferta';

    // Indica que este modelo no utiliza las columnas de timestamps (`created_at`, `updated_at`).
    public $timestamps = false;

    // Define los campos que se pueden asignar de forma masiva.
    protected $fillable = [
        'id_demandante', // ID del demandante inscrito en la oferta.
        'id_oferta', // ID de la oferta a la que está inscrito.
        'adjudicada', // Campo legacy (string).
        'adjudicada_estado', // 0 = no adjudicada, 1 = adjudicada.
        'fecha' // Fecha en la que el demandante se inscribió en la oferta.
    ];

    /**
     * Relación: cada inscripción pertenece a un demandante.
     **/
    public function demandante()
    {
        // Define la relación "pertenece a" con el modelo "Demandante".
        return $this->belongsTo(Demandante::class, 'id_demandante');
    }

    /**
     * Relación: cada inscripción pertenece a una oferta.
     **/
    public function oferta()
    {
        // Define la relación "pertenece a" con el modelo "Oferta".
        return $this->belongsTo(Oferta::class, 'id_oferta');
    }

    /**
     * Scope para filtrar inscripciones adjudicadas.
     **/
    public function scopeAdjudicadas($query)
    {
        // Filtra las inscripciones que tienen adjudicada_estado = 1.
        return $query->where('adjudicada_estado', 1);
    }

    /**
     * Scope para filtrar inscripciones por una oferta específica.
     **/
    public function scopePorOferta($query, $idOferta)
    {
        // Filtra las inscripciones que coinciden con un ID de oferta.
        return $query->where('id_oferta', $idOferta);
    }

    /**
     * Scope para filtrar inscripciones por un demandante específico.
     **/
    public function scopePorDemandante($query, $idDemandante)
    {
        // Filtra las inscripciones que coinciden con un ID de demandante.
        return $query->where('id_demandante', $idDemandante);
    }
}
