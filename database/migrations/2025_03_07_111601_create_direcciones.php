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
        Schema::create('direcciones', function (Blueprint $table) {
            $table->id();
            $table->integer('id_usuario')->unsigned()->nullable()->default(null);
            $table->longText('direccion_nombre')->nullable();
            $table->longText('direccion_calle')->nullable();
            $table->string('direccion_numero')->nullable()->default('');
            $table->longText('direccion_colonia')->nullable();
            $table->string('direccion_ciudad')->nullable()->default('');
            $table->string('direccion_codigo_postal')->nullable()->default('');
            $table->string('direccion_horario')->nullable()->default('');
            $table->longText('direccion_referencia')->nullable();
            $table->longText('direccion_notas')->nullable();
            $table->longText('direccion_delegacion')->nullable();
            $table->string('direccion_numeroint')->nullable();
            $table->string('direccion_telefono')->nullable()->default('');
            $table->string('direccion_favorita')->nullable()->default('no');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('direcciones');
    }
};
