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
        Schema::create('top_10_cortes', function (Blueprint $table) {
            $table->id();
            $table->integer('cuenta');
            $table->integer('temporada');
            $table->string('nombre_corte');  // Campo para el nombre del corte
            $table->string('region');        // Campo para la región
            $table->json('lista');           // Campo para la lista en formato JSON
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('top_10_cortes');
    }
};
