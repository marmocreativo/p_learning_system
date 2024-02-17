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
        Schema::create('evaluaciones_preguntas', function (Blueprint $table) {
            $table->id();
            $table->integer('id_sesion');
            $table->string('pregunta')->nullable()->default('');
            $table->string('respuesta_a')->nullable()->default('');
            $table->string('respuesta_b')->nullable()->default('');
            $table->string('respuesta_c')->nullable()->default('');
            $table->string('respuesta_d')->nullable()->default('');
            $table->string('resultado_a')->nullable()->default('');
            $table->string('resultado_b')->nullable()->default('');
            $table->string('resultado_c')->nullable()->default('');
            $table->string('resultado_d')->nullable()->default('');
            $table->string('orden')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluaciones_preguntas');
    }
};
