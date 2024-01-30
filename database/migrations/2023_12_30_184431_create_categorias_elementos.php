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
        Schema::create('categorias_elementos', function (Blueprint $table) {
            $table->id();
            $table->integer('id_categoria')->nullable()->default(null);
            $table->integer('id_elemento')->nullable()->default(null);
            $table->string('elementos')->default('publicaciones');
            $table->string('clase')->default('pagina');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categorias_elementos');
    }
};
