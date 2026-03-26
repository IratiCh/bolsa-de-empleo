<?php

namespace Tests\Unit;

use App\Models\Demandante;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DemandanteTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function demandaute_can_be_created()
    {
        $demandante = Demandante::create([
            'dni' => '12345678A',
            'nombre' => 'Andrés',
            'ape1' => 'Pérez',
            'ape2' => 'García',
            'tel_movil' => 600123456,
            'email' => 'andres@mail.com',
            'situacion' => 1,
        ]);

        $this->assertDatabaseHas('demandante', [
            'email' => 'andres@mail.com',
        ]);

        $this->assertEquals('Andrés', $demandante->nombre);
    }
}
