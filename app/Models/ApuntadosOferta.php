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
        'fecha'
    ];

    public function demandante()
    {
        return $this->belongsTo(Demandante::class, 'id_demandante');
    }

    public function oferta()
    {
        return $this->belongsTo(Oferta::class, 'id_oferta');
    }

    public function scopeAdjudicadas($query)
    {
        return $query->where('adjudicada', 1);
    }

    public function scopePorOferta($query, $idOferta)
    {
        return $query->where('id_oferta', $idOferta);
    }

    public function scopePorDemandante($query, $idDemandante)
    {
        return $query->where('id_demandante', $idDemandante);
    }
}