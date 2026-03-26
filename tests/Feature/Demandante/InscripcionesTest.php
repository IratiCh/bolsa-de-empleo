<?php

namespace Tests\Feature\Demandante;

use App\Models\Demandante;
use App\Models\Empresa;
use App\Models\Oferta;
use App\Models\TipoContrato;
use App\Models\Usuario;
use App\Models\ApuntadosOferta;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class InscripcionesTest extends TestCase
{
    use RefreshDatabase;

    private $demandante;
    private $usuario;

    protected function setUp(): void
    {
        parent::setUp();

        // Crear demandante
        $this->demandante = Demandante::create([
            'dni' => '12345678A',
            'nombre' => 'Juan',
            'ape1' => 'Perez',
            'ape2' => 'Garcia',
            'tel_movil' => 600123456,
            'email' => 'juan@mail.com',
            'situacion' => 1,
        ]);

        // Crear usuario asociado
        $this->usuario = Usuario::create([
            'email' => 'juan@mail.com',
            'contrasena_hash' => bcrypt('password123'),
            'rol' => 'demandante',
            'id_rol' => $this->demandante->id,
        ]);

        Sanctum::actingAs($this->usuario);
    }

    /** @test */
    public function demandante_puede_inscribirse_en_oferta_abierta()
    {
        $empresa = Empresa::create([
            'validado' => 1,
            'cif' => 'A12345678',
            'nombre' => 'Empresa Test',
            'localidad' => 'Madrid',
            'telefono' => '912345678',
            'email' => 'empresa@mail.com',
        ]);

        $tipo = TipoContrato::create(['nombre' => 'Indefinido']);

        $oferta = Oferta::create([
            'nombre' => 'Desarrollador',
            'breve_desc' => 'Desarrollo',
            'desc' => 'Descripción completa',
            'fecha_pub' => now(),
            'num_puesto' => 1,
            'horario' => 'Jornada completa',
            'obs' => 'Obs',
            'abierta' => 0,
            'fecha_cierre' => now()->addDays(30),
            'id_emp' => $empresa->id,
            'id_tipo_cont' => $tipo->id,
        ]);

        $response = $this->postJson("/api/demandante/ofertas/{$oferta->id}/inscribirse", [
            'id_demandante' => $this->demandante->id,
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('apuntados_oferta', [
            'id_demandante' => $this->demandante->id,
            'id_oferta' => $oferta->id,
        ]);
    }

    /** @test */
    public function demandante_no_puede_inscribirse_en_oferta_cerrada()
    {
        $empresa = Empresa::create([
            'validado' => 1,
            'cif' => 'B12345678',
            'nombre' => 'Empresa Test 2',
            'localidad' => 'Valencia',
            'telefono' => '961234567',
            'email' => 'empresa2@mail.com',
        ]);

        $tipo = TipoContrato::create(['nombre' => 'Temporal']);

        $oferta = Oferta::create([
            'nombre' => 'Puesto Cerrado',
            'breve_desc' => 'Cerrado',
            'desc' => 'Descripción',
            'fecha_pub' => now(),
            'num_puesto' => 1,
            'horario' => 'Jornada completa',
            'obs' => 'Obs',
            'abierta' => 1,
            'fecha_cierre' => now(),
            'id_emp' => $empresa->id,
            'id_tipo_cont' => $tipo->id,
        ]);

        $response = $this->postJson("/api/demandante/ofertas/{$oferta->id}/inscribirse", [
            'id_demandante' => $this->demandante->id,
        ]);

        $response->assertStatus(400);
    }

    /** @test */
    public function demandante_puede_cancelar_inscripcion()
    {
        $empresa = Empresa::create([
            'validado' => 1,
            'cif' => 'C12345678',
            'nombre' => 'Empresa Test 3',
            'localidad' => 'Barcelona',
            'telefono' => '933123456',
            'email' => 'empresa3@mail.com',
        ]);

        $tipo = TipoContrato::create(['nombre' => 'Prácticas']);

        $oferta = Oferta::create([
            'nombre' => 'Prácticas',
            'breve_desc' => 'Prácticas',
            'desc' => 'Descripción',
            'fecha_pub' => now(),
            'num_puesto' => 1,
            'horario' => 'Jornada completa',
            'obs' => 'Obs',
            'abierta' => 1,
            'fecha_cierre' => now()->addDays(30),
            'id_emp' => $empresa->id,
            'id_tipo_cont' => $tipo->id,
        ]);

        // Inscribir primero
        ApuntadosOferta::create([
            'id_oferta' => $oferta->id,
            'id_demandante' => $this->demandante->id,
            'adjudicada_estado' => 0,
            'fecha' => now()->toDateString(),
        ]);

        $response = $this->deleteJson("/api/demandante/ofertas/{$oferta->id}/cancelar-inscripcion", [
            'id_demandante' => $this->demandante->id,
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseMissing('apuntados_oferta', [
            'id_demandante' => $this->demandante->id,
            'id_oferta' => $oferta->id,
        ]);
    }
}
