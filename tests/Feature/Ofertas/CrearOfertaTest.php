<?php

namespace Tests\Feature\Ofertas;

use App\Models\Empresa;
use App\Models\Oferta;
use App\Models\TipoContrato;
use App\Models\Usuario;
use App\Models\Titulo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CrearOfertaTest extends TestCase
{
    use RefreshDatabase;

    private $empresa;
    private $usuarioEmpresa;
    private $tipoContrato;

    protected function setUp(): void
    {
        parent::setUp();

        $this->empresa = Empresa::create([
            'validado' => 1,
            'cif' => 'A98765432',
            'nombre' => 'Tech Solutions',
            'localidad' => 'Madrid',
            'telefono' => '914567890',
            'email' => 'tech@mail.com',
        ]);

        $this->usuarioEmpresa = Usuario::create([
            'email' => 'tech@mail.com',
            'contrasena_hash' => bcrypt('password123'),
            'rol' => 'empresa',
            'id_rol' => $this->empresa->id,
        ]);

        $this->tipoContrato = TipoContrato::create(['nombre' => 'Indefinido']);
    }

    /** @test */
    public function empresa_validada_puede_crear_oferta()
    {
        $titulo = Titulo::create(['nombre' => 'Grado en Informática']);

        $response = $this->actingAs($this->usuarioEmpresa)
            ->postJson('/api/ofertas/crear', [
                'nombre' => 'Desarrollador PHP',
                'breve_desc' => 'Senior PHP Developer',
                'desc' => 'Buscamos desarrollador con 5 años de experiencia en Laravel',
                'num_puesto' => 2,
                'horario' => 'Jornada completa',
                'obs' => 'Incorporación inmediata',
                'id_emp' => $this->empresa->id,
                'id_tipo_cont' => $this->tipoContrato->id,
                'titulos' => [$titulo->id],
            ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('oferta', [
            'nombre' => 'Desarrollador PHP',
            'id_emp' => $this->empresa->id,
            'abierta' => 0,
        ]);
    }

    /** @test */
    public function empresa_no_validada_no_puede_crear_oferta()
    {
        $empresaNoValidada = Empresa::create([
            'validado' => 0,
            'cif' => 'B87654321',
            'nombre' => 'Startup Inc',
            'localidad' => 'Barcelona',
            'telefono' => '933456789',
            'email' => 'startup@mail.com',
        ]);

        $usuarioNoValidado = Usuario::create([
            'email' => 'startup@mail.com',
            'contrasena_hash' => bcrypt('password123'),
            'rol' => 'empresa',
            'id_rol' => $empresaNoValidada->id,
        ]);

        $titulo = Titulo::create(['nombre' => 'Grado en Informática']);

        $response = $this->actingAs($usuarioNoValidado)
            ->postJson('/api/ofertas/crear', [
                'nombre' => 'Desarrollador',
                'breve_desc' => 'Senior',
                'desc' => 'Descripción',
                'num_puesto' => 1,
                'horario' => 'Jornada completa',
                'id_emp' => $empresaNoValidada->id,
                'id_tipo_cont' => $this->tipoContrato->id,
                'titulos' => [$titulo->id],
            ]);

        $response->assertStatus(403);
    }

    /** @test */
    public function oferta_requiere_campos_obligatorios()
    {
        $response = $this->actingAs($this->usuarioEmpresa)
            ->postJson('/api/ofertas/crear', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['nombre', 'desc', 'breve_desc', 'num_puesto', 'horario', 'id_emp', 'id_tipo_cont', 'titulos']);
    }
}
