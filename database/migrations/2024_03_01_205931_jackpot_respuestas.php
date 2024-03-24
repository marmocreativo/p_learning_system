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
        //
        Schema::create('jackpot_respuestas', function (Blueprint $table) {
            $table->id();
            $table->integer('id_jackpot');
            $table->integer('id_pregunta');
            $table->integer('id_usuario');
            $table->string('respuesta_usuario')->nullable()->default('');
            $table->string('respuesta_correcta')->nullable()->default('');
            $table->timestamp('fecha_registro')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('jackpot_respuestas');
    }
};
