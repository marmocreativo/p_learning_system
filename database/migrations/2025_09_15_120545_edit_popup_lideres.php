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
        Schema::table('popup_lideres', function (Blueprint $table) {
            $table->unsignedBigInteger('id_temporada')->after('id');
            $table->index('id_temporada'); // Agregar índice para mejorar consultas
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('popup_lideres', function (Blueprint $table) {
            $table->dropIndex(['id_temporada']);
            $table->dropColumn('id_temporada');
        });
    }
};