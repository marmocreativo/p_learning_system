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
        Schema::create('sesiones', function (Blueprint $table) {
            $table->id();
            $table->integer('id_cliente');
            $table->integer('id_temporada');
            $table->string('titulo')->nullable()->default('');
            $table->longText('descripcion')->nullable()->default('');
            $table->longText('contenido')->nullable()->default('');
            $table->string('nombre_instructor')->nullable()->default('');
            $table->string('duracion_aproximada')->nullable()->default('');
            $table->string('video_1')->nullable()->default('');
            $table->string('video_2')->nullable()->default('');
            $table->string('video_3')->nullable()->default('');
            $table->string('video_4')->nullable()->default('');
            $table->string('video_5')->nullable()->default('');
            $table->string('titulo_video_1')->nullable()->default('');
            $table->string('titulo_video_2')->nullable()->default('');
            $table->string('titulo_video_3')->nullable()->default('');
            $table->string('titulo_video_4')->nullable()->default('');
            $table->string('titulo_video_5')->nullable()->default('');
            $table->timestamp('fecha_publicacion')->nullable();
            $table->string('cantidad_preguntas_evaluacion')->nullable()->default(1);
            $table->string('ordenar_preguntas_evaluacion')->nullable()->default('aleatorio');
            $table->string('visualizar_puntaje_normal')->nullable()->default('');
            $table->string('visualizar_puntaje_estreno')->nullable()->default('');
            $table->string('preguntas_puntaje_estreno')->nullable()->default('');
            $table->string('preguntas_puntaje_normal')->nullable()->default('');
            $table->integer('horas_estreno')->nullable()->default(24);
            $table->string('evaluacion_obligatoria')->nullable()->default('si');
            $table->string('imagen')->nullable()->default('default.jpg');
            $table->string('imagen_fondo')->nullable()->default('fondo_default.jpg');
            $table->string('estado')->nullable()->default('activo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sesiones');
    }
};
