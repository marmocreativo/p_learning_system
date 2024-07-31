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
        
        Schema::create('canjeo_productos', function (Blueprint $table) {
            $table->id();
            $table->integer('id_temporada');
            $table->string('nombre')->nullable()->default('');
            $table->longText('descripcion')->nullable()->default(null);
            $table->longText('variaciones')->nullable()->default(null);
            $table->string('imagen')->nullable()->default('producto_default.jpg');
            $table->integer('creditos')->default(0);
            $table->integer('limite_total')->default(0);
            $table->integer('limite_usuario')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('canjeo_productos');
    }
};
