<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfertasEmpresa extends Model
{
    use HasFactory;

    protected $table = 'ofertas_empresa';

    public $timestamps = false;

    protected $fillable = [
        'id_empresa',
        'id_oferta',
    ];

    // Relación con empresa
    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'id_empresa');
    }

    // Relación con oferta
    public function oferta()
    {
        return $this->belongsTo(Oferta::class, 'id_oferta');
    }
}
