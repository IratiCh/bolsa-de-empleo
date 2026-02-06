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
        Schema::create('notificaciones_centro', function (Blueprint $table) {
            $table->id();
            $table->string('tipo', 45);
            $table->string('mensaje', 255);
            $table->foreignId('id_oferta')->nullable()->constrained('oferta');
            $table->foreignId('id_empresa')->nullable()->constrained('empresa');
            $table->foreignId('id_demandante')->nullable()->constrained('demandante');
            $table->string('externo_nombre', 120)->nullable();
            $table->dateTime('fecha')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notificaciones_centro');
    }
};
