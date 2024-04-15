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
        Schema::create('usuarios_distribuidores_suscripciones', function (Blueprint $table) {
            $table->id();
            $table->integer('id_distribuidor');
            $table->integer('id_cuenta');
            $table->integer('id_temporada');
            $table->integer('cantidad_usuarios');
            $table->string('nivel')->nullable()->default('completo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios_distribuidores_suscripciones');
    }
};
