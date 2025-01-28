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
        Schema::table('cuentas', function (Blueprint $table) {
            $table->string('badge')->nullable()->default(null);
            $table->string('titulo')->nullable()->default(null);
            $table->string('titulo_resaltado')->nullable()->default(null);
            $table->string('boton_texto')->nullable()->default(null);
            $table->string('boton_enlace')->nullable()->default(null);
            $table->string('fondo')->nullable()->default(null);
            $table->string('imagen_video')->nullable()->default(null);
            $table->string('link_video')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('cuentas', function (Blueprint $table) {
            $table->dropColumn('badge');
            $table->dropColumn('titulo');
            $table->dropColumn('titulo_resaltado');
            $table->dropColumn('boton_texto');
            $table->dropColumn('boton_enlace');
            $table->dropColumn('fondo');
            $table->dropColumn('imagen_video');
            $table->dropColumn('link_video');
        });
    }
};
