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
        Schema::create('popup_lideres', function (Blueprint $table) {
            $table->id(); // Primary key auto-incremental
            $table->text('titulo');
            $table->longText('resumen');
            $table->string('imagen'); // URL de la imagen
            $table->string('texto_boton');
            $table->string('enlace_boton'); // URL del botón
            $table->json('distribuidores'); // Array/JSON de IDs de distribuidores
            $table->enum('estado', ['publicado', 'borrador'])->default('borrador'); // Estado del popup
            $table->timestamps(); // created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('popup_lideres');
    }
};