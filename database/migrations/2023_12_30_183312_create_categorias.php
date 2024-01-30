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
        Schema::create('categorias', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->default('');
            $table->string('url')->unique();
            $table->text('descripcion')->default('');
            $table->text('contenido')->default('');
            $table->string('imagen')->default('default.jpg');
            $table->string('imagen_fondo')->default('fondo_default.jpg');
            $table->integer('id_padre')->nullable()->default(null);;
            $table->string('elementos')->default('publicaciones');;
            $table->string('clase')->default('pagina');;
            $table->string('estado')->default('activo');;
            $table->integer('orden')->default(0);;
            $table->timestamp('fecha_creacion')->nullable();
            $table->timestamp('fecha_borrado')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categorias');
    }
};
