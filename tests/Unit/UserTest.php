<?php

namespace Tests\Unit;

use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function usuario_can_be_created()
    {
        $usuario = Usuario::create([
            'email' => 'test@mail.com',
            'contrasena_hash' => bcrypt('prueba123'),
            'rol' => 'demandante',
            'id_rol' => 1,
        ]);

        $this->assertDatabaseHas('usuarios', [
            'email' => 'test@mail.com',
            'rol' => 'demandante',
        ]);

        $this->assertEquals('test@mail.com', $usuario->email);
    }
}
