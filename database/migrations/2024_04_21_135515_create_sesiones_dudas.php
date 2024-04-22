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
        Schema::create('sesiones_dudas', function (Blueprint $table) {
            $table->id();
            $table->integer('id_sesion');
            $table->integer('id_usuario');
            $table->longText('duda')->default('');
            $table->longText('respuesta')->default('');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       Schema::dropIfExists('sesiones_dudas');
    }
};
