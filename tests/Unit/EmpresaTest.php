<?php

namespace Tests\Unit;

use App\Models\Empresa;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmpresaTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function empresa_can_be_created_and_relations_work()
    {
        $empresa = Empresa::create([
            'validado' => 1,
            'cif' => 'A12345678',
            'nombre' => 'Acme S.L.',
            'localidad' => 'Madrid',
            'telefono' => '912345678',
            'email' => 'info@mail.com',
        ]);

        $this->assertDatabaseHas('empresa', [
            'email' => 'info@mail.com',
        ]);

        $this->assertEquals('Acme S.L.', $empresa->nombre);
    }
}
