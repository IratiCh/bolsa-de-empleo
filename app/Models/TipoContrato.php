<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoContrato extends Model
{
    use HasFactory;

    protected $table = 'tipos_contrato';

    public $timestamps = false;

    protected $fillable = [
        'nombre',
    ];

    // Relación con las ofertas
    public function ofertas()
    {
        return $this->hasMany(Oferta::class, 'id_tipo_cont');
    }
}

