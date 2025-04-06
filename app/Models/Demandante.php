<?php
 
 namespace App\Models;
 
 use Illuminate\Database\Eloquent\Factories\HasFactory;
 use Illuminate\Database\Eloquent\Model;
 
 class Demandante extends Model
 {
     use HasFactory;
 
     protected $table = 'demandante';
 
     public $timestamps = false;
 
     protected $fillable = [
         'dni',
         'nombre',
         'ape1',
         'ape2',
         'tel_movil',
         'email',
         'situacion',
     ];
 
     // Relación con los títulos del demandante
     public function titulos()
     {
         return $this->belongsToMany(Titulo::class, 'titulos_demandante', 'id_dem', 'id_titulo')
                     ->withPivot('centro', 'año', 'cursando');
     }
 
     // Relación con las inscripciones en ofertas
     public function inscripciones()
     {
         return $this->belongsToMany(Oferta::class, 'apuntados_oferta', 'id_demandante', 'id_oferta')
                     ->withPivot('adjudicada', 'fecha');
     }
 }

