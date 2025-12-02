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
        Schema::create('empleados', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_depto')->constrained('departamentos')->onDelete('cascade');
            $table->foreignId('id_jornada')->constrained('jornadas')->onDelete('cascade');
            $table->string('nombre');
            $table->string('telefono');
            $table->string('email')->unique();
            $table->string('contraseña');
            $table->string('puesto');
            $table->timestamps();

            $table->index('id_depto');
            $table->index('id_jornada');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empleados');
    }
};