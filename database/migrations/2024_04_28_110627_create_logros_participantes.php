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
        Schema::create('logros_participantes', function (Blueprint $table) {
            $table->id();
            $table->integer('id_logro');
            $table->integer('id_temporada');
            $table->integer('id_distribuidor');
            $table->integer('id_usuario');
            $table->string('estado')->nullable()->default('participante');
            $table->timestamp('fecha_registro')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logros_participantes');
    }
};
