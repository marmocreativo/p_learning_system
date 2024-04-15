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
        Schema::create('sliders', function (Blueprint $table) {
            $table->id();
            $table->integer('id_cuenta');
            $table->integer('id_temporada');
            $table->string('titulo')->default('');
            $table->string('subtitulo')->default('');
            $table->string('boton')->default('');
            $table->string('link_boton')->default('');
            $table->string('imagen')->default('default.jpg');
            $table->string('imagen_fondo')->default('fondo_default.jpg');
            $table->string('estado')->default('activo');
            $table->integer('orden')->nullable()->default(null);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sliders');
    }
};
