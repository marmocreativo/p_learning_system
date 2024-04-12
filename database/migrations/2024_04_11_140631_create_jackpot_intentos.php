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
        Schema::create('jackpot_intentos', function (Blueprint $table) {
            $table->id();
            $table->integer('id_jackpot');
            $table->integer('id_usuario');
            $table->integer('tiro');
            $table->integer('slot_1');
            $table->integer('slot_2');
            $table->integer('slot_3');
            $table->integer('slot_premio');
            $table->integer('puntaje');
            $table->timestamp('fecha_registro')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jackpot_intentos');
    }
};
