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
        Schema::create('trivias_visitas', function (Blueprint $table) {
            $table->id();
             $table->integer('id_cuenta')->unsigned()->nullable()->default(null);
            $table->integer('id_temporada')->unsigned()->nullable()->default(null);
            $table->bigInteger('id_usuario')->unsigned()->nullable()->default(null);
            $table->integer('id_trivia')->unsigned()->nullable()->default(null);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trivias_visitas');
    }
};
