<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $table = 'empresa';
    protected $fillable = [
        'cif', 'validado', 'nombre', 'localidad', 'telefono', 'email'
    ];

    public function titulos()
    {
        return $this->belongsToMany(Titulo::class, 'titulos_empresa', 'id_emp', 'id_titulo');
    }

    public function ofertas()
    {
        return $this->hasMany(Oferta::class, 'id_emp');
    }

    public $timestamps = false;
}
