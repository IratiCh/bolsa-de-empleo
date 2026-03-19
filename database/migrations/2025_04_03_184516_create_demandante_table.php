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
        Schema::create('demandante', function (Blueprint $table) {
            $table->id();
            $table->string('dni', 9);
            $table->string('nombre', 45);
            $table->string('ape1', 45);
            $table->string('ape2', 45);
            $table->integer('tel_movil');
            $table->string('email', 45)->unique();
            $table->tinyInteger('situacion');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demandante');
    }
};
