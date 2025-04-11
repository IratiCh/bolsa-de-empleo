<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model; // Clase base para los modelos de Eloquent.

class Demandante extends Model
{
    // Especifica el nombre de la tabla asociada en la base de datos.
    protected $table = 'demandante';

    // Define los campos que se pueden asignar de forma masiva.
    protected $fillable = [
        'dni', 'nombre', 'ape1', 'ape2', 'tel_movil', 'email', 'situacion'
    ];

    /**
     * Relación: cada demandante tiene un usuario asociado en la tabla "usuarios".
     **/
    public function usuario()
    {
        // Define la relación con el modelo "Usuario".
        // Se conecta el campo "id_rol" en la tabla "usuarios" con el "id" del demandante.
        // Además, filtra por el rol "demandante" para obtener únicamente usuarios con este rol.
        return $this->hasOne(Usuario::class, 'id_rol', 'id')->where('rol', 'demandante');
    }
    
    /**
     * Relación: cada demandante puede tener múltiples títulos asociados.
     **/
    public function titulos()
    {
        // Define la relación con el modelo "Titulo".
        // Usa la tabla pivot "titulos_demandante" para conectar demandantes y títulos.
        // Incluye columnas adicionales en la tabla pivot: "centro", "año" y "cursando".
        return $this->belongsToMany(Titulo::class, 'titulos_demandante', 'id_dem', 'id_titulo')
                    ->withPivot(['centro', 'año', 'cursando']);
    }

    /**
     * Relación: cada demandante puede estar inscrito en múltiples ofertas.
     **/
    public function ofertasInscritas()
    {
        // Define la relación con el modelo "Oferta".
        // Usa la tabla pivot "apuntados_oferta" para conectar demandantes y ofertas.
        // Aplica el modelo "ApuntadosOferta" para manejar esta relación.
        // Incluye columnas adicionales en la tabla pivot: "adjudicada" y "fecha".
        return $this->belongsToMany(Oferta::class, 'apuntados_oferta', 'id_demandante', 'id_oferta')
                    ->using(ApuntadosOferta::class)
                    ->withPivot(['adjudicada', 'fecha']);
    }

    public $timestamps = false;
}