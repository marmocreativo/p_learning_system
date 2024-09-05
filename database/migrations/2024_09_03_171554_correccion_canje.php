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
        Schema::create('canjeo_transacciones', function (Blueprint $table) {
            $table->id();
            $table->integer('id_temporada');
            $table->integer('id_corte');
            $table->integer('id_usuario');
            $table->integer('creditos');
            $table->longText('direccion_nombre')->nullable();
            $table->longText('direccion_calle')->nullable();
            $table->string('direccion_numero')->nullable()->default('');
            $table->string('direccion_numeroint')->nullable();
            $table->longText('direccion_colonia')->nullable();
            $table->string('direccion_ciudad')->nullable()->default('');
            $table->string('direccion_codigo_postal')->nullable()->default('');
            $table->string('direccion_horario')->nullable()->default('');
            $table->longText('direccion_referencia')->nullable();
            $table->longText('direccion_notas')->nullable();
            $table->string('confirmado')->nullable()->default('no');
            $table->string('enviado')->nullable()->default('no');
            $table->string('fecha_registro')->nullable()->default(null);
            $table->string('fecha_confirmado')->nullable()->default(null);
            $table->string('fecha_envio')->nullable()->default(null);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('canjeo_transacciones');
    }
};
