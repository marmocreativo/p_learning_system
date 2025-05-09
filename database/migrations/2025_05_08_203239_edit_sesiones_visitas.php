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
        Schema::table('sesiones_visitas', function (Blueprint $table) {
            $table->integer('id_cuenta')->unsigned()->nullable()->default(null);
            $table->integer('id_temporada')->unsigned()->nullable()->default(null);
            $table->bigInteger('id_usuario')->unsigned()->nullable()->default(null);
            $table->integer('id_sesion')->unsigned()->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('sesiones_visitas', function (Blueprint $table) {
            $table->dropColumn('id_cuenta');
            $table->dropColumn('id_temporada');
            $table->dropColumn('id_usuario');
            $table->dropColumn('id_sesion');
        });
    }
};
