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
        Schema::create('logros', function (Blueprint $table) {
            $table->id();
            $table->integer('id_temporada');
            $table->string('nombre')->nullable()->default('');
            $table->longText('instrucciones')->nullable();
            $table->timestamp('fecha_inicio')->nullable();
            $table->timestamp('fecha_vigente')->nullable();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logros');
    }
};
