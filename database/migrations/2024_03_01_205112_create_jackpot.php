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
        Schema::create('jackpot', function (Blueprint $table) {
            $table->id();
            $table->integer('id_cuenta');
            $table->integer('id_temporada');
            $table->string('titulo')->nullable()->default('');
            $table->timestamp('fecha_publicacion')->nullable();
            $table->timestamp('fecha_vigencia')->nullable();
            $table->integer('intentos')->nullable()->default(1);
            $table->string('trivia')->nullable()->default('obligatoria');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jackpot');
    }
};
