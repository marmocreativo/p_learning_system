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
        Schema::create('puntos_extra', function (Blueprint $table) {
            $table->id();
            $table->integer('id_cuenta');
            $table->integer('id_temporada');
            $table->integer('id_usuario');
            $table->string('concepto')->nullable()->default(0);
            $table->integer('puntos')->nullable()->default(0);
            $table->timestamp('fecha_registro')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('puntos_extra');
    }
};
