<?php

namespace Tests\Feature\Database;

use Illuminate\Support\Facades\Schema;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MigrationsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function usuarios_tabla_existe()
    {
        $this->assertTrue(Schema::hasTable('usuarios'));
    }

    /** @test */
    public function usuarios_tabla_tiene_columnas_requeridas()
    {
        $this->assertTrue(Schema::hasColumns('usuarios', [
            'id', 'email', 'contrasena_hash', 'rol', 'id_rol'
        ]));
    }

    /** @test */
    public function demandante_tabla_existe()
    {
        $this->assertTrue(Schema::hasTable('demandante'));
    }

    /** @test */
    public function demandante_tabla_tiene_columnas_requeridas()
    {
        $this->assertTrue(Schema::hasColumns('demandante', [
            'id', 'dni', 'nombre', 'ape1', 'ape2', 'tel_movil', 'email', 'situacion',
            'cv_form', 'cv_pdf_path'
        ]));
    }

    /** @test */
    public function empresa_tabla_existe()
    {
        $this->assertTrue(Schema::hasTable('empresa'));
    }

    /** @test */
    public function empresa_tabla_tiene_columnas_requeridas()
    {
        $this->assertTrue(Schema::hasColumns('empresa', [
            'id', 'validado', 'cif', 'nombre', 'localidad', 'telefono', 'email'
        ]));
    }

    /** @test */
    public function oferta_tabla_existe()
    {
        $this->assertTrue(Schema::hasTable('oferta'));
    }

    /** @test */
    public function oferta_tabla_tiene_columnas_requeridas()
    {
        $this->assertTrue(Schema::hasColumns('oferta', [
            'id', 'nombre', 'breve_desc', 'desc', 'fecha_pub', 'num_puesto', 
            'horario', 'obs', 'abierta', 'fecha_cierre', 'motivo_cierre', 'id_emp', 'id_tipo_cont'
        ]));
    }

    /** @test */
    public function titulos_tabla_existe()
    {
        $this->assertTrue(Schema::hasTable('titulos'));
    }

    /** @test */
    public function tipos_contrato_tabla_existe()
    {
        $this->assertTrue(Schema::hasTable('tipos_contrato'));
    }

    /** @test */
    public function titulos_demandante_tabla_existe()
    {
        $this->assertTrue(Schema::hasTable('titulos_demandante'));
    }

    /** @test */
    public function titulos_demandante_tiene_columnas_requeridas()
    {
        $this->assertTrue(Schema::hasColumns('titulos_demandante', [
            'id_dem', 'id_titulo', 'centro', 'año', 'cursando'
        ]));
    }

    /** @test */
    public function apuntados_oferta_tabla_existe()
    {
        $this->assertTrue(Schema::hasTable('apuntados_oferta'));
    }

    /** @test */
    public function apuntados_oferta_tiene_columnas_requeridas()
    {
        $this->assertTrue(Schema::hasColumns('apuntados_oferta', [
            'id_demandante', 'id_oferta', 'adjudicada', 'fecha', 'adjudicada_estado'
        ]));
    }

    /** @test */
    public function notificaciones_centro_tabla_existe()
    {
        $this->assertTrue(Schema::hasTable('notificaciones_centro'));
    }

    /** @test */
    public function notificaciones_centro_tiene_columnas_requeridas()
    {
        $this->assertTrue(Schema::hasColumns('notificaciones_centro', [
            'id', 'tipo', 'mensaje', 'id_oferta', 'id_empresa', 'id_demandante',
            'externo_nombre', 'fecha', 'leida'
        ]));
    }
}
