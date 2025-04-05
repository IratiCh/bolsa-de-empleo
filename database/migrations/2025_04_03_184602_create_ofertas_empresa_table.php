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
        Schema::create('ofertas_empresa', function (Blueprint $table) {
            $table->foreignId('id_empresa')->constrained('empresa');
            $table->foreignId('id_oferta')->constrained('oferta');
            $table->primary(['id_empresa', 'id_oferta']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ofertas_empresa');
    }
};
