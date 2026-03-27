<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable; // Extiende la funcionalidad de "Authenticatable" para manejar autenticación de usuarios.
use Illuminate\Notifications\Notifiable; // Trait para gestionar notificaciones relacionadas con el usuario.

class Usuario extends Authenticatable
{
    // Permite que el modelo Usuario envíe notificaciones (por ejemplo, correos electrónicos, alertas).
    use Notifiable;

    // Define los campos que pueden ser asignados de forma masiva.
    protected $fillable = [
        'id', 'email', 'contrasena_hash', 'rol', 'id_rol'
    ];

    // Define los campos que deben permanecer ocultos cuando el modelo se convierte a JSON.
    protected $hidden = [
        'contrasena_hash', // Oculta la contraseña para garantizar la seguridad en las respuestas JSON.
    ];

    /**
     * Relación: el usuario pertenece a un demandante.
     **/
    public function demandante()
    {
        // Define la relación con el modelo "Demandante".
        // Conecta el campo "id_rol" en la tabla "usuarios" con el "id" en la tabla "demandante".
        return $this->belongsTo(Demandante::class, 'id_rol', 'id');
    }

    // Indica que este modelo no utiliza las columnas de timestamps (`created_at`, `updated_at`).
    public $timestamps = false;
}