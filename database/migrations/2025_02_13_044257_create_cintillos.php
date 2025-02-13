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
        Schema::create('cintillos', function (Blueprint $table) {
            $table->id();
            $table->integer('id_cuenta')->nullable()->default(null);
            $table->integer('id_temporada')->nullable()->default(null);
            $table->string('texto')->default('');
            $table->string('texto_boton')->default('');
            $table->string('enlace_boton')->default('');
            $table->string('imagen')->nullable()->default(null);
            $table->timestamp('fecha_inicio')->nullable()->default(null);
            $table->timestamp('fecha_final')->nullable()->default(null);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cintillos');
    }
};
