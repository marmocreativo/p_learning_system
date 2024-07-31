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
        Schema::create('canjeo_cortes_usuarios', function (Blueprint $table) {
            $table->id();
            $table->integer('id_corte');
            $table->integer('id_temporada');
            $table->integer('id_usuario');
            $table->integer('puntaje');
            $table->integer('creditos');
            $table->date('fecha_corte');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('canjeo_cortes_usuarios');
    }
};
