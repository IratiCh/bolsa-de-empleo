<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TiposContratoSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tipos_contrato')->insert([
            'nombre' => 'Mañana',
        ]);

        DB::table('tipos_contrato')->insert([
            'nombre' => 'Tarde',
        ]);
    }
}
