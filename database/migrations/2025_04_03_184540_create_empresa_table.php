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
        Schema::create('empresa', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('validado');
            $table->string('cif', 11)->unique();
            $table->string('nombre', 45);
            $table->string('localidad', 45);
            $table->string('telefono', 9);
            $table->string('email', 50)->unique();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empresa');
    }
};
