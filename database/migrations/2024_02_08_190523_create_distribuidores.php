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
        Schema::create('distribuidores', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('pais')->default('MX');
            $table->string('region')->default('interna');
            $table->string('nivel')->default('completo');
            $table->string('estado')->default('activo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('distribuidores');
    }
};
