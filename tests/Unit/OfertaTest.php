<?php

namespace Tests\Unit;

use App\Models\Empresa;
use App\Models\Oferta;
use App\Models\TipoContrato;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OfertaTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function oferta_can_be_created_with_relations()
    {
        $empresa = Empresa::create([
            'validado' => 1,
            'cif' => 'B12345678',
            'nombre' => 'Empresa Test',
            'localidad' => 'Navarra',
            'telefono' => '961234567',
            'email' => 'empresa@mail.com',
        ]);

        $tipoContrato = TipoContrato::create(['nombre' => 'Indefinido']);

        $oferta = Oferta::create([
            'nombre' => 'Desarrollador',
            'breve_desc' => 'Puesto de desarrollo',
            'desc' => 'Desarrollador fullstack Laravel',
            'fecha_pub' => now(),
            'num_puesto' => 1,
            'horario' => 'Jornada completa',
            'obs' => 'Incorporación inmediata',
            'abierta' => 1,
            'fecha_cierre' => now()->addDays(30),
            'id_emp' => $empresa->id,
            'id_tipo_cont' => $tipoContrato->id,
        ]);

        $this->assertDatabaseHas('oferta', [
            'nombre' => 'Desarrollador',
            'id_emp' => $empresa->id,
            'id_tipo_cont' => $tipoContrato->id,
        ]);

        $this->assertEquals('Empresa Test', $oferta->empresa->nombre);
        $this->assertEquals('Indefinido', $oferta->tipoContrato->nombre);
    }
}
