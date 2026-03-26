<?php

namespace Tests\Feature\Admin;

use App\Models\Empresa;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ValidacionEmpresasTest extends TestCase
{
    use RefreshDatabase;

    private $usuarioAdmin;

    protected function setUp(): void
    {
        parent::setUp();

        // Crear usuario centro (admin)
        $this->usuarioAdmin = Usuario::create([
            'email' => 'centro@mail.com',
            'contrasena_hash' => bcrypt('centro123'),
            'rol' => 'centro',
            'id_rol' => 1,
        ]);
    }

    /** @test */
    public function admin_puede_listar_empresas_pendientes()
    {
        // Crear empresa no validada
        Empresa::create([
            'validado' => 0,
            'cif' => 'F99999999',
            'nombre' => 'Empresa Pendiente',
            'localidad' => 'Navarra',
            'telefono' => '954123456',
            'email' => 'empresa1@mail.com',
        ]);

        // Crear empresa validada
        Empresa::create([
            'validado' => 1,
            'cif' => 'G88888888',
            'nombre' => 'Empresa Validada',
            'localidad' => 'Madrid',
            'telefono' => '952654321',
            'email' => 'empresa2@mail.com',
        ]);

        $response = $this->actingAs($this->usuarioAdmin)
            ->getJson('/api/centro/empresas-pendientes');

        $response->assertStatus(200);
        // La ruta devuelve un array JSON con las empresas pendientes
        $this->assertCount(1, $response->json());
        $response->assertJsonFragment([
            'cif' => 'F99999999',
        ]);
    }

    /** @test */
    public function admin_puede_validar_empresa()
    {
        $empresa = Empresa::create([
            'validado' => 0,
            'cif' => 'H77777777',
            'nombre' => 'Empresa a Validar',
            'localidad' => 'Alicante',
            'telefono' => '965123456',
            'email' => 'empresa3@mail.com',
        ]);

        $response = $this->actingAs($this->usuarioAdmin)
            ->putJson("/api/centro/validar-empresa/{$empresa->id}", [
                'accion' => 'aceptar',
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('empresa', [
            'id' => $empresa->id,
            'validado' => 1,
        ]);
    }

    /** @test */
    public function validar_empresa_requiere_accion()
    {
        $empresa = Empresa::create([
            'validado' => 0,
            'cif' => 'I66666666',
            'nombre' => 'Empresa Protegida',
            'localidad' => 'Córdoba',
            'telefono' => '957987654',
            'email' => 'empresa@mail.com',
        ]);

        $response = $this->actingAs($this->usuarioAdmin)
            ->putJson("/api/centro/validar-empresa/{$empresa->id}");

        $response->assertStatus(422);
    }

    /** @test */
    public function admin_puede_rechazar_empresa()
    {
        $empresa = Empresa::create([
            'validado' => 0,
            'cif' => 'J55555555',
            'nombre' => 'Empresa a Rechazar',
            'localidad' => 'Granada',
            'telefono' => '958654321',
            'email' => 'empresa@mail.com',
        ]);

        $response = $this->actingAs($this->usuarioAdmin)
            ->putJson("/api/centro/validar-empresa/{$empresa->id}", [
                'accion' => 'rechazar',
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('empresa', [
            'id' => $empresa->id,
            'validado' => -1,
        ]);
    }
}
