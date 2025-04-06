<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TituloDemandante extends Model
{
    use HasFactory;

    protected $table = 'titulos_demandante';

    public $timestamps = false;

    protected $fillable = [
        'id_dem',
        'id_titulo',
        'centro',
        'año',
        'cursando',
    ];

    // Relación con demandante
    public function demandante()
    {
        return $this->belongsTo(Demandante::class, 'id_dem');
    }

    // Relación con titulo
    public function titulo()
    {
        return $this->belongsTo(Titulo::class, 'id_titulo');
    }
}
