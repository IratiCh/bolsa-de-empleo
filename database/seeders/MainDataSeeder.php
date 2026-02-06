<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MainDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        // 1. Crear Empresas
        $empresa1 = DB::table('empresa')->insertGetId([
            'validado' => 1,
            'cif' => 'B12345678',
            'nombre' => 'Tech Solutions S.L.',
            'localidad' => 'Pamplona',
            'telefono' => '912345678',
            'email' => 'empresa1@mail.com'
        ]);

        DB::table('usuarios')->insert([
            'email' => 'empresa1@mail.com',
            'contrasena_hash' => Hash::make('empresa123'),
            'rol' => 'empresa',
            'id_rol' => $empresa1
        ]);

        $empresa2 = DB::table('empresa')->insertGetId([
            'validado' => 0,
            'cif' => 'B87654321',
            'nombre' => 'Informatica Global S.A.',
            'localidad' => 'Tudela',
            'telefono' => '987654321',
            'email' => 'empresa2@mail.com'
        ]);

        DB::table('usuarios')->insert([
            'email' => 'empresa2@mail.com',
            'contrasena_hash' => Hash::make('empresa123'),
            'rol' => 'empresa',
            'id_rol' => $empresa2
        ]);

        // 2. Crear Demandantes
        $demandante1 = DB::table('demandante')->insertGetId([
            'dni' => '12345678Z',
            'nombre' => 'Lucia',
            'ape1' => 'Gutierrez',
            'ape2' => 'Vazquez',
            'tel_movil' => 600112233,
            'email' => 'demandante1@mail.com',
            'situacion' => 1
        ]);

        DB::table('usuarios')->insert([
            'email' => 'demandante1@mail.com',
            'contrasena_hash' => Hash::make('demandante123'),
            'rol' => 'demandante',
            'id_rol' => $demandante1
        ]);

        $demandante2 = DB::table('demandante')->insertGetId([
            'dni' => '87654321Z',
            'nombre' => 'Maria',
            'ape1' => 'García',
            'ape2' => 'Pérez',
            'tel_movil' => 600332211,
            'email' => 'demandante2@mail.com',
            'situacion' => 0
        ]);

        DB::table('usuarios')->insert([
            'email' => 'demandante2@mail.com',
            'contrasena_hash' => Hash::make('demandante123'),
            'rol' => 'demandante',
            'id_rol' => $demandante2
        ]);

        // 3. Crear Ofertas (id_tipo_cont 1 es 'Mañana')
        $ofertaId = DB::table('oferta')->insertGetId([
            'nombre' => 'Programador Junior React',
            'breve_desc' => 'Busqueda de talento joven',
            'desc' => 'Buscamos alguien con ganas de aprender...',
            'fecha_pub' => now(),
            'num_puesto' => 2,
            'horario' => '08:00 - 15:00',
            'obs' => null,
            'abierta' => 0,
            'id_emp' => $empresa1,
            'id_tipo_cont' => 1
        ]);

        DB::table('ofertas_empresa')->insert([
            'id_empresa' => $empresa1,
            'id_oferta' => $ofertaId
        ]);

        // Añadir titulaciones requeridas a la oferta
        DB::table('titulos_oferta')->insert([
            'id_oferta' => $ofertaId,
            'id_titulo' => 1
        ]);

        $ofertaId2 = DB::table('oferta')->insertGetId([
            'nombre' => 'Técnico Soporte',
            'breve_desc' => 'Soporte TI',
            'desc' => 'Atención y soporte a usuarios.',
            'fecha_pub' => now(),
            'num_puesto' => 1,
            'horario' => '09:00 - 14:00',
            'obs' => null,
            'abierta' => 0,
            'id_emp' => $empresa1,
            'id_tipo_cont' => 2
        ]);

        DB::table('ofertas_empresa')->insert([
            'id_empresa' => $empresa1,
            'id_oferta' => $ofertaId2
        ]);

        DB::table('titulos_oferta')->insert([
            'id_oferta' => $ofertaId2,
            'id_titulo' => 3
        ]);

        // 4. Inscribir al demandante en la oferta
        DB::table('apuntados_oferta')->insert([
            'id_demandante' => $demandante1,
            'id_oferta' => $ofertaId,
            'adjudicada' => null,
            'adjudicada_estado' => 0,
            'fecha' => now()
        ]);

        // 5. Asignar  Título a demandantes (id 1 es el primer título)
        DB::table('titulos_demandante')->insert([
            'id_dem' => $demandante1,
            'id_titulo' => 1,
            'centro' => 'CIP Estella',
            'año' => '2024-06-15',
            'cursando' => 'Finalizado'
        ]);

        DB::table('titulos_demandante')->insert([
            'id_dem' => $demandante2,
            'id_titulo' => 3,
            'centro' => 'CIP Tafalla',
            'año' => '2020-05-20',
            'cursando' => 'Finalizado'
        ]);
    }
}
