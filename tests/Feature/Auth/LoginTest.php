<?php

namespace Tests\Feature\Auth;

use App\Models\Usuario;
use App\Models\Demandante;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function usuario_puede_login_con_credenciales_validas()
    {
        Demandante::create([
            'dni' => '12345678A',
            'nombre' => 'Ana',
            'ape1' => 'Gomez',
            'ape2' => 'Lopez',
            'tel_movil' => 612345678,
            'email' => 'usuario@mail.com',
            'situacion' => 0,
        ]);

        Usuario::create([
            'email' => 'usuario@mail.com',
            'contrasena_hash' => bcrypt('password123'),
            'rol' => 'demandante',
            'id_rol' => 1,
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'usuario@mail.com',
            'contrasena_hash' => 'password123',
        ]);

        $response->assertStatus(200);
    }

    /** @test */
    public function login_falla_con_email_incorrecto()
    {
        Demandante::create([
            'dni' => '12345678A',
            'nombre' => 'Ana',
            'ape1' => 'Gomez',
            'ape2' => 'Lopez',
            'tel_movil' => 612345678,
            'email' => 'usuario@mail.com',
            'situacion' => 0,
        ]);

        Usuario::create([
            'email' => 'usuario@mail.com',
            'contrasena_hash' => bcrypt('password123'),
            'rol' => 'demandante',
            'id_rol' => 1,
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'noexiste@mail.com',
            'contrasena_hash' => 'password123',
        ]);

        $response->assertStatus(401);
    }

    /** @test */
    public function login_falla_con_contrasena_incorrecta()
    {
        Demandante::create([
            'dni' => '12345678A',
            'nombre' => 'Ana',
            'ape1' => 'Gomez',
            'ape2' => 'Lopez',
            'tel_movil' => 612345678,
            'email' => 'usuario@mail.com',
            'situacion' => 0,
        ]);

        Usuario::create([
            'email' => 'usuario@mail.com',
            'contrasena_hash' => bcrypt('password123'),
            'rol' => 'demandante',
            'id_rol' => 1,
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'usuario@mail.com',
            'contrasena_hash' => 'incorrecta',
        ]);

        $response->assertStatus(401);
    }

    /** @test */
    public function login_requiere_email_y_contrasena()
    {
        $response = $this->postJson('/api/auth/login', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email', 'contrasena_hash']);
    }

}
