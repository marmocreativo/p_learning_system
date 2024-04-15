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
        Schema::rename('usuarios_distribuidores_suscripciones', 'distribuidores_suscripciones');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::rename('distribuidores_suscripciones', 'usuarios_distribuidores_suscripciones');
    }
};
