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
        Schema::create('titulos_demandante', function (Blueprint $table) {
            $table->foreignId('id_dem')->constrained('demandante')->onDelete('cascade');
            $table->foreignId('id_titulo')->constrained('titulos')->onDelete('cascade');
            $table->string('centro', 45)->nullable();
            $table->date('año')->nullable();
            $table->string('cursando', 45)->nullable();
            $table->primary(['id_dem', 'id_titulo']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('titulos_demandante');
    }
};
