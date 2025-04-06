<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TituloOferta extends Model
{
    use HasFactory;

    protected $table = 'titulos_oferta';

    public $timestamps = false;

    protected $fillable = [
        'id_oferta',
        'id_titulo',
    ];

    // Relación con oferta
    public function oferta()
    {
        return $this->belongsTo(Oferta::class, 'id_oferta');
    }

    // Relación con titulo
    public function titulo()
    {
        return $this->belongsTo(Titulo::class, 'id_titulo');
    }
}
