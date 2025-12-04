<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('citas', function (Blueprint $table) {
            $table->tinyInteger('calificacion')->nullable()->comment('Puntaje de 1 a 5');
            $table->text('comentario')->nullable();
            $table->timestamp('calificado_en')->nullable();
        });
    }

    public function down()
    {
        Schema::table('citas', function (Blueprint $table) {
            $table->dropColumn(['calificacion', 'comentario', 'calificado_en']);
        });
    }
};