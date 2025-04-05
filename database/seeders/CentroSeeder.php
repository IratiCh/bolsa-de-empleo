<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CentroSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('usuarios')->insert([
            'email' => 'admin@gmail.com',
            'contrasena_hash' => hash::make('admin'),
            'rol' => 'centro',
            'id_rol' => 1,
        ]);
    }
}
