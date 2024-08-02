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
        //
         Schema::table('canjeo_cortes', function (Blueprint $table) {
            $table->date('fecha_publicacion_inicio')->nullable()->default(null);
            $table->date('fecha_publicacion_final')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
         Schema::table('canjeo_cortes', function (Blueprint $table) {
            $table->dropColumn('fecha_publicacion_inicio');
            $table->dropColumn('fecha_publicacion_final');
        });
    }
};
