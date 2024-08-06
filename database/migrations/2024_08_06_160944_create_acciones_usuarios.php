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
        Schema::create('acciones_usuarios', function (Blueprint $table) {
            $table->id();
            $table->integer('id_usuario')->nullable()->default(null);
            $table->string('nombre')->nullable()->default(null);
            $table->string('correo')->nullable()->default(null);
            $table->string('accion')->nullable()->default(null);
            $table->longText('descripcion')->nullable()->default(null);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('acciones_usuarios');
    }
};
