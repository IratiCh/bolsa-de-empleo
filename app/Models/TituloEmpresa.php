<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TituloEmpresa extends Model
{
    use HasFactory;

    protected $table = 'titulos_empresa';

    public $timestamps = false;

    protected $fillable = [
        'id_emp',
        'id_titulo',
    ];

    // Relación con empresa
    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'id_emp');
    }

    // Relación con titulo
    public function titulo()
    {
        return $this->belongsTo(Titulo::class, 'id_titulo');
    }
}
