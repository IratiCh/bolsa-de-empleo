<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // Trait para usar factorías en pruebas.
use Illuminate\Database\Eloquent\Model; // Clase base para todos los modelos de Eloquent.

class TipoContrato extends Model
{
    // Especifica el nombre de la tabla asociada en la base de datos.
    protected $table = 'tipos_contrato';

    // Define los campos que pueden ser asignados de forma masiva.
    protected $fillable = [
        'nombre',
    ];

    // Indica que este modelo no utiliza las columnas de timestamps (`created_at`, `updated_at`).
    public $timestamps = false;
}
