<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TituloSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $titulos = [
        'Desarrollo de Aplicaciones Web',
        'Desarrollo de Aplicaciones Multiplataforma',
        'Sistemas Microinformáticos y Redes',
        'Cuidados Auxiliares de Enfermería',
        'Gestión Administrativa'
    ];

    foreach ($titulos as $titulo) {
        DB::table('titulos')->insert(['nombre' => $titulo]);
    }
    }
}
