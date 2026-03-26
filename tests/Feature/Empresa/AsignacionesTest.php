<?php

namespace Tests\Feature\Empresa;

use App\Models\Demandante;
use App\Models\Empresa;
use App\Models\Oferta;
use App\Models\TipoContrato;
use App\Models\Usuario;
use App\Models\ApuntadosOferta;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AsignacionesTest extends TestCase
{
    use RefreshDatabase;

    private $empresa;
    private $usuarioEmpresa;
    private $demandante;
    private $oferta;

    protected function setUp(): void
    {
        parent::setUp();

        // Crear empresa
        $this->empresa = Empresa::create([
            'validado' => 1,
            'cif' => 'D12121212',
            'nombre' => 'Empresa Asignaciones',
            'localidad' => 'Valencia',
            'telefono' => '963456789',
            'email' => 'asignaciones@mail.com',
        ]);

        $this->usuarioEmpresa = Usuario::create([
            'email' => 'asignaciones@mail.com',
            'contrasena_hash' => bcrypt('password123'),
            'rol' => 'empresa',
            'id_rol' => $this->empresa->id,
        ]);

        // Crear demandante
        $this->demandante = Demandante::create([
            'dni' => '87654321Z',
            'nombre' => 'María',
            'ape1' => 'López',
            'ape2' => 'Sánchez',
            'tel_movil' => 650654321,
            'email' => 'maria@mail.com',
            'situacion' => 1,
        ]);

        // Crear oferta
        $tipo = TipoContrato::create(['nombre' => 'Práctica']);

        $this->oferta = Oferta::create([
            'nombre' => 'Asistente Administrativo',
            'breve_desc' => 'Admin',
            'desc' => 'Puesto administrativo',
            'fecha_pub' => now(),
            'num_puesto' => 1,
            'horario' => 'Jornada completa',
            'obs' => 'Obs',
            'abierta' => 1,
            'fecha_cierre' => now()->addDays(30),
            'id_emp' => $this->empresa->id,
            'id_tipo_cont' => $tipo->id,
        ]);

        // Inscribir demandante en oferta
        ApuntadosOferta::create([
            'id_oferta' => $this->oferta->id,
            'id_demandante' => $this->demandante->id,
            'adjudicada_estado' => 0,
            'fecha' => now()->toDateString(),
        ]);
    }

    /** @test */
    public function empresa_puede_ver_candidatos_de_oferta()
    {
        $response = $this->actingAs($this->usuarioEmpresa)
            ->getJson("/api/empresa/asignar_oferta/{$this->oferta->id}");

        $response->assertStatus(200);
        $this->assertCount(1, $response->json('inscritos'));
    }

    /** @test */
    public function empresa_puede_asignar_demandante()
    {
        $response = $this->actingAs($this->usuarioEmpresa)
            ->postJson("/api/empresa/asignar_oferta/{$this->oferta->id}/asignar", [
                'id_demandante' => $this->demandante->id,
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('apuntados_oferta', [
            'id_demandante' => $this->demandante->id,
            'id_oferta' => $this->oferta->id,
            'adjudicada_estado' => 1,
        ]);
    }

    /** @test */
    public function otra_empresa_puede_ver_candidatos_por_ahora()
    {
        $otraEmpresa = Empresa::create([
            'validado' => 1,
            'cif' => 'E45454545',
            'nombre' => 'Otra Empresa',
            'localidad' => 'Bilbao',
            'telefono' => '944123456',
            'email' => 'otra@mail.com',
        ]);

        $usuarioOtra = Usuario::create([
            'email' => 'otra@mail.com',
            'contrasena_hash' => bcrypt('password123'),
            'rol' => 'empresa',
            'id_rol' => $otraEmpresa->id,
        ]);

        $response = $this->actingAs($usuarioOtra)
            ->getJson("/api/empresa/asignar_oferta/{$this->oferta->id}");

        $response->assertStatus(200);
    }

    /** @test */
    public function demandante_puede_asignar_si_no_hay_autorizacion()
    {
        $demandanteUser = Usuario::create([
            'email' => 'maria@mail.com',
            'contrasena_hash' => bcrypt('password123'),
            'rol' => 'demandante',
            'id_rol' => $this->demandante->id,
        ]);

        $response = $this->actingAs($demandanteUser)
            ->postJson("/api/empresa/asignar_oferta/{$this->oferta->id}/asignar", [
                'id_demandante' => $this->demandante->id,
            ]);

        $response->assertStatus(200);
    }
}
