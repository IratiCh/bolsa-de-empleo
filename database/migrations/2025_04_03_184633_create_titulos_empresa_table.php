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
        Schema::create('titulos_empresa', function (Blueprint $table) {
            $table->foreignId('id_emp')->constrained('empresa');
            $table->foreignId('id_titulo')->constrained('titulos');
            $table->primary(['id_emp', 'id_titulo']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('titulos_empresa');
    }
};
