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
        Schema::create('evaluaciones_respuestas', function (Blueprint $table) {
            $table->id();
            $table->integer('id_sesion');
            $table->integer('id_evaluacion');
            $table->integer('id_usuario');
            $table->string('respuesta_usuario')->nullable()->default('');
            $table->string('respuesta_correcta')->nullable()->default('');
            $table->string('puntaje')->nullable()->default(0);
            $table->timestamp('fecha_registro')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluaciones_respuestas');
    }
};
