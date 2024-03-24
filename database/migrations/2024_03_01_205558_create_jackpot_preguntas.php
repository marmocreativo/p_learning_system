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
        Schema::create('jackpot_preguntas', function (Blueprint $table) {
            $table->id();
            $table->integer('id_jackpot');
            $table->string('pregunta')->nullable()->default('');
            $table->string('respuesta_a')->nullable()->default('');
            $table->string('respuesta_b')->nullable()->default('');
            $table->string('respuesta_c')->nullable()->default('');
            $table->string('respuesta_d')->nullable()->default('');
            $table->string('respuesta_correcta')->nullable()->default('');
            $table->string('orden')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jackpot_preguntas');
    }
};
