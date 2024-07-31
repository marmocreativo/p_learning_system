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
        Schema::create('canjeo_cortes', function (Blueprint $table) {
            $table->id();
            $table->integer('id_temporada');
            $table->string('titulo')->nullable()->default('');
            $table->date('fecha_inicio')->nullable()->default(null);
            $table->date('fecha_final')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('canjeo_cortes');
    }
};
