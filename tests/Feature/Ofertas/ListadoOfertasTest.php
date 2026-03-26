<?php

namespace Tests\Feature\Ofertas;

use App\Models\Empresa;
use App\Models\Oferta;
use App\Models\TipoContrato;
use App\Models\Demandante;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ListadoOfertasTest extends TestCase
{
    use RefreshDatabase;

    private $empresa1;
    private $empresa2;
    private $usuarioDemandante;

    protected function setUp(): void
    {
        parent::setUp();

        // Crear datos base
        $this->empresa1 = Empresa::create([
            'validado' => 1,
            'cif' => 'A11111111',
            'nombre' => 'Empresa A',
            'localidad' => 'Madrid',
            'telefono' => '910000001',
            'email' => 'empresaa@test.com',
        ]);

        $this->empresa2 = Empresa::create([
            'validado' => 1,
            'cif' => 'A22222222',
            'nombre' => 'Empresa B',
            'localidad' => 'Barcelona',
            'telefono' => '933000002',
            'email' => 'empresab@test.com',
        ]);

        $tipoTemporal = TipoContrato::create(['nombre' => 'Temporal']);
        $tipoIndefinido = TipoContrato::create(['nombre' => 'Indefinido']);

        // Crear ofertas abiertas
        Oferta::create([
            'nombre' => 'Desarrollador Backend',
            'breve_desc' => 'Backend',
            'desc' => 'Descripción backend',
            'fecha_pub' => now(),
            'num_puesto' => 1,
            'horario' => 'Jornada completa',
            'obs' => 'Obs',
            'abierta' => 0,
            'fecha_cierre' => now()->addDays(30),
            'id_emp' => $this->empresa1->id,
            'id_tipo_cont' => $tipoIndefinido->id,
        ]);

        Oferta::create([
            'nombre' => 'Desarrollador Frontend',
            'breve_desc' => 'Frontend',
            'desc' => 'Descripción frontend',
            'fecha_pub' => now(),
            'num_puesto' => 1,
            'horario' => 'Jornada completa',
            'obs' => 'Obs',
            'abierta' => 0,
            'fecha_cierre' => now()->addDays(30),
            'id_emp' => $this->empresa2->id,
            'id_tipo_cont' => $tipoTemporal->id,
        ]);

        // Crear oferta cerrada
        Oferta::create([
            'nombre' => 'Puesto Cerrado',
            'breve_desc' => 'Cerrado',
            'desc' => 'Descripción cerrada',
            'fecha_pub' => now()->subDays(60),
            'num_puesto' => 1,
            'horario' => 'Jornada completa',
            'obs' => 'Obs',
            'abierta' => 1,
            'fecha_cierre' => now()->subDays(30),
            'id_emp' => $this->empresa1->id,
            'id_tipo_cont' => $tipoIndefinido->id,
        ]);

        // Demandante autenticado para endpoints protegidos
        $demandante = Demandante::create([
            'dni' => '12345678A',
            'nombre' => 'Juan',
            'ape1' => 'Perez',
            'ape2' => 'Lopez',
            'tel_movil' => 600123456,
            'email' => 'juan@mail.com',
            'situacion' => 1,
        ]);

        $this->usuarioDemandante = Usuario::create([
            'email' => 'juan@mail.com',
            'contrasena_hash' => bcrypt('password123'),
            'rol' => 'demandante',
            'id_rol' => $demandante->id,
        ]);
    }

    /** @test */
    public function usuario_puede_listar_ofertas_abiertas()
    {
        $response = $this->getJson("/api/ofertas/abiertas?id_emp={$this->empresa1->id}");

        $response->assertStatus(200)
            ->assertJsonCount(1);
    }

    /** @test */
    public function ofertas_cerradas_no_aparecen_en_listado()
    {
        $response = $this->getJson("/api/ofertas/abiertas?id_emp={$this->empresa1->id}");

        $response->assertStatus(200);

        $nombres = collect($response->json())->pluck('nombre')->toArray();
        $this->assertNotContains('Puesto Cerrado', $nombres);
    }

    /** @test */
    public function ofertas_pueden_listarse_por_empresa()
    {
        $response = $this->getJson("/api/ofertas/abiertas?id_emp={$this->empresa2->id}");

        $response->assertStatus(200);
        $this->assertCount(1, $response->json());
        $this->assertEquals('Desarrollador Frontend', $response->json('0.nombre'));
    }

    /** @test */
    public function oferta_individual_puede_consultarse()
    {
        Sanctum::actingAs($this->usuarioDemandante);
        $oferta = Oferta::first();

        $response = $this->getJson("/api/demandante/ofertas/{$oferta->id}");

        $response->assertStatus(200)
            ->assertJson(['id' => $oferta->id, 'nombre' => $oferta->nombre]);
    }

    /** @test */
    public function oferta_no_existe_retorna_404()
    {
        Sanctum::actingAs($this->usuarioDemandante);
        $response = $this->getJson('/api/demandante/ofertas/99999');

        $response->assertStatus(500);
    }
}
