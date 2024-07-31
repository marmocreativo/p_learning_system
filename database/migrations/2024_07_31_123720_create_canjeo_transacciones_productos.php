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
        Schema::create('canjeo_transacciones_productos', function (Blueprint $table) {
            $table->id();
            $table->integer('id_transacciones');
            $table->integer('id_temporada');
            $table->integer('id_producto');
            $table->string('nombre')->nullable()->default(null);
            $table->string('variacion')->nullable()->default(null);
            $table->integer('cantidad');
            $table->integer('creditos_unitario');
            $table->integer('creditos_totales');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('canjeo_transacciones_productos');
    }
};
