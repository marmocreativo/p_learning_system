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
        Schema::create('logros_anexos_productos', function (Blueprint $table) {
            $table->id();
            $table->integer('id_logro')->unsigned()->nullable()->default(null);
            $table->integer('id_participacion')->unsigned()->nullable()->default(null);
            $table->integer('id_temporada')->unsigned()->nullable()->default(null);
            $table->integer('id_usuario')->unsigned()->nullable()->default(null);
            $table->integer('id_usuario_b')->unsigned()->nullable()->default(null);
            $table->integer('id_anexo')->unsigned()->nullable()->default(null);
            $table->string('sku', 255)->nullable()->default(null);
            $table->string('cantidad', 255)->nullable()->default(null);
            $table->string('importe_total', 255)->nullable()->default(null);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logros_anexos_productos');
    }
};
