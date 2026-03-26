<?php

namespace Tests\Unit;

use App\Models\TipoContrato;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TipoContratoTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function tipo_contrato_can_be_created()
    {
        $tipo = TipoContrato::create(['nombre' => 'Temporal']);

        $this->assertDatabaseHas('tipos_contrato', [
            'nombre' => 'Temporal',
        ]);

        $this->assertEquals('Temporal', $tipo->nombre);
    }
}
