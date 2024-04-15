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
        Schema::create('usuarios_suscripciones', function (Blueprint $table) {
            $table->id();
            $table->integer('id_usuario');
            $table->integer('id_cuenta');
            $table->integer('id_temporada');
            $table->integer('id_distribuidor');
            $table->integer('puntos_sesiones')->nullable()->default(0);
            $table->integer('puntos_evaluaciones')->nullable()->default(0);
            $table->integer('puntos_trivias')->nullable()->default(0);
            $table->integer('puntos_jackpot')->nullable()->default(0);
            $table->integer('puntos_extra')->nullable()->default(0);
            $table->integer('puntos_totales')->nullable()->default(0);
            $table->string('confirmacion_puntos')->nullable()->default('pendiente');
            $table->string('nivel')->nullable()->default('completo');
            $table->string('funcion')->nullable()->default('usuario');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios_suscripciones');
    }
};
