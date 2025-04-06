<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    use HasFactory;

    protected $table = 'empresa';

    public $timestamps = false;

    protected $fillable = [
        'validado',
        'cif',
        'nombre',
        'localidad',
        'telefono',
        'email',
    ];

    // Relación uno a muchos: una empresa tiene muchas ofertas
    public function ofertas()
    {
        return $this->hasMany(Oferta::class, 'id_emp');
    }
}
