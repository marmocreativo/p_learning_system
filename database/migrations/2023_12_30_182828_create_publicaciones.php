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
        Schema::create('publicaciones', function (Blueprint $table) {
            $table->id();
            $table->string('titulo')->default('');
            $table->string('url')->unique();
            $table->text('descripcion')->default('');
            $table->text('contenido')->default('');
            $table->text('keywords')->default('');
            $table->string('imagen')->default('default.jpg');
            $table->string('imagen_fondo')->default('fondo_default.jpg');
            $table->timestamp('fecha_creacion')->nullable();
            $table->timestamp('fecha_actualizacion')->nullable();
            $table->timestamp('fecha_publicacion')->nullable();
            $table->timestamp('fecha_vigencia')->nullable();
            $table->string('clase')->default('pagina');
            $table->string('destacar')->default('no');
            $table->string('estado')->default('activo');
            $table->integer('orden')->nullable()->default(null);
            $table->timestamp('fecha_borrado')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('publicaciones');
    }
};
