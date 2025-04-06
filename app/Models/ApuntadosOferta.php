<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApuntadosOferta extends Model
{
    use HasFactory;

    protected $table = 'apuntados_oferta';

    public $timestamps = false;

    protected $fillable = [
        'id_demandante',
        'id_oferta',
        'adjudicada',
        'fecha',
    ];

    // Relación con demandante
    public function demandante()
    {
        return $this->belongsTo(Demandante::class, 'id_demandante');
    }

    // Relación con oferta
    public function oferta()
    {
        return $this->belongsTo(Oferta::class, 'id_oferta');
    }
}

