<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Titulo extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'nombre'
    ];

    public function demandantes()
    {
        return $this->belongsToMany(Demandante::class, 'titulos_demandante', 'id_titulo', 'id_dem')
                    ->withPivot('centro', 'año', 'cursando');
    }

    public $timestamps = false;
}
