<?php

namespace Tests\Unit;

use App\Models\Demandante;
use App\Models\Titulo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TituloTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function titulo_can_be_created_and_attached_to_demandante()
    {
        $demandante = Demandante::create([
            'dni' => '23456789B',
            'nombre' => 'Lucía',
            'ape1' => 'Martínez',
            'ape2' => 'Lopez',
            'tel_movil' => 612345678,
            'email' => 'lucia@mail.com',
            'situacion' => 1,
        ]);

        $titulo = Titulo::create(['nombre' => 'Grado Superior Informática']);

        $demandante->titulos()->attach($titulo->id, [
            'centro' => 'Politécnico de Estella',
            'año' => '2023-01-01',
            'cursando' => "No",
        ]);

        $this->assertDatabaseHas('titulos_demandante', [
            'id_dem' => $demandante->id,
            'id_titulo' => $titulo->id,
        ]);

        $this->assertEquals('Grado Superior Informática', $demandante->titulos->first()->nombre);
    }
}
