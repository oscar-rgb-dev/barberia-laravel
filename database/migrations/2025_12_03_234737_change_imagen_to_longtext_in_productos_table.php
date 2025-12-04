<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Cambiar el tipo de columna de imagen a LONGTEXT
        Schema::table('productos', function (Blueprint $table) {
            $table->longText('imagen')->change()->nullable();
        });
    }

    public function down()
    {
        // Revertir a string si es necesario
        Schema::table('productos', function (Blueprint $table) {
            $table->string('imagen')->change()->nullable();
        });
    }
};