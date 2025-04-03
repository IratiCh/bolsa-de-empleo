<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('oferta', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 45)->nullable();
            $table->string('breve_desc', 45)->nullable();
            $table->string('desc', 500)->nullable();
            $table->date('fecha_pub')->nullable();
            $table->tinyInteger('num_puesto')->nullable();
            $table->string('horario', 45)->nullable();
            $table->string('obs', 500)->nullable();
            $table->tinyInteger('abierta')->nullable();
            $table->date('fecha_cierre')->nullable();
            $table->foreignId('id_emp')->constrained('empresa');
            $table->foreignId('id_tipo_cont')->constrained('tipos_contrato');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('oferta');
    }
};
