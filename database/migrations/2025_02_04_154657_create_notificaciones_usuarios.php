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
        Schema::create('notificaciones_usuarios', function (Blueprint $table) {
            $table->id();
            $table->integer('id_cuenta')->nullable()->default(null);
            $table->integer('id_temporada')->nullable()->default(null);
            $table->integer('id_usuario')->nullable()->default(null);
            $table->string('tipo')->default('notificacion');
            $table->text('texto')->default('');
            $table->string('enlace')->default('');
            $table->timestamp('fecha_leido')->nullable()->default(null);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notificaciones_usuarios');
    }
};
