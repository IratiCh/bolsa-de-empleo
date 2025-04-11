<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // Trait para usar factorías en pruebas.
use Illuminate\Database\Eloquent\Model; // Clase base para todos los modelos de Eloquent.

class Empresa extends Model
{
    // Especifica el nombre de la tabla asociada en la base de datos.
    protected $table = 'empresa';
    
    // Define los campos que se pueden asignar de forma masiva.
    protected $fillable = [
        'cif', 'validado', 'nombre', 'localidad', 'telefono', 'email'
    ];

    /**
     * Relación: una empresa puede estar asociada a múltiples títulos.
     **/
    public function titulos()
    {
        // Define la relación con el modelo "Titulo".
        // Usa la tabla pivot "titulos_empresa" para conectar empresas y títulos.
        // 'id_emp' se refiere al campo de la empresa en la tabla pivot.
        // 'id_titulo' se refiere al campo del título en la tabla pivot.
        return $this->belongsToMany(Titulo::class, 'titulos_empresa', 'id_emp', 'id_titulo');
    }

    /**
     * Relación: una empresa puede tener múltiples ofertas asociadas.
     **/
    public function ofertas()
    {
        // Define la relación con el modelo "Oferta".
        // 'id_emp' es la clave foránea que conecta la empresa con sus ofertas.
        return $this->hasMany(Oferta::class, 'id_emp');
    }

    // Indica que este modelo no utiliza las columnas de timestamps (`created_at`, `updated_at`).
    public $timestamps = false;
}
