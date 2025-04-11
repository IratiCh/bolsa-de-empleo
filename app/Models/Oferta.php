<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // Trait para utilizar factorías en pruebas.
use Illuminate\Database\Eloquent\Model; // Clase base para todos los modelos de Eloquent.

class Oferta extends Model
{
    // Especifica el nombre de la tabla asociada en la base de datos.
    protected $table = 'oferta';

    // Define los campos que pueden ser asignados de forma masiva.
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

    /**
     * Relación: la oferta pertenece a una empresa.
     **/
    public function empresa()
    {
        // Define la relación con el modelo "Empresa".
        // La clave foránea en la tabla "oferta" es "id_emp".
        return $this->belongsTo(Empresa::class, 'id_emp');
    }

    /**
     * Relación: la oferta está asociada a un tipo de contrato.
     **/
    public function tipoContrato()
    {
        // Define la relación con el modelo "TipoContrato".
        // La clave foránea en la tabla "oferta" es "id_tipo_cont".
        return $this->belongsTo(TipoContrato::class, 'id_tipo_cont');
    }

    /**
     * Relación: la oferta puede estar asociada a múltiples títulos.
     **/
    public function titulos()
    {
        // Define la relación con el modelo "Titulo".
        // Usa la tabla pivot "titulos_oferta" para conectar ofertas y títulos.
        // "id_oferta" es el campo de la oferta en la tabla pivot.
        // "id_titulo" es el campo del título en la tabla pivot.
        return $this->belongsToMany(Titulo::class, 'titulos_oferta', 'id_oferta', 'id_titulo');
    }

    /**
     * Relación: la oferta puede tener múltiples demandantes inscritos.
     **/
    public function demandantesInscritos()
    {
        // Define la relación con el modelo "Demandante".
        // Usa la tabla pivot "apuntados_oferta" para conectar ofertas y demandantes.
        // Aplica el modelo pivot "ApuntadosOferta" para manejar esta relación.
        // Incluye columnas adicionales en la tabla pivot: "adjudicada" y "fecha".
        return $this->belongsToMany(Demandante::class, 'apuntados_oferta', 'id_oferta', 'id_demandante')
                    ->using(ApuntadosOferta::class)            
                    ->withPivot(['adjudicada', 'fecha']);
    }
    // Indica que este modelo no utiliza las columnas de timestamps (`created_at`, `updated_at`).
    public $timestamps = false;
}