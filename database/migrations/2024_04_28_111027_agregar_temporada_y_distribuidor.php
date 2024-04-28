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
        Schema::table('evaluaciones_respuestas', function (Blueprint $table) {
            $table->integer('id_temporada')->after('id_sesion')->nullable();
            $table->integer('id_distribuidor')->after('id_temporada')->nullable();
            
        });

        Schema::table('jackpot_intentos', function (Blueprint $table) {
            $table->integer('id_temporada')->after('id_jackpot')->nullable();
            $table->integer('id_distribuidor')->after('id_temporada')->nullable();
            
        });
        Schema::table('jackpot_respuestas', function (Blueprint $table) {
            $table->integer('id_temporada')->after('id_jackpot')->nullable();
            $table->integer('id_distribuidor')->after('id_temporada')->nullable();
            
        });
        Schema::table('sesiones_visualizaciones', function (Blueprint $table) {
            $table->integer('id_temporada')->after('id_sesion')->nullable();
            $table->integer('id_distribuidor')->after('id_temporada')->nullable();
            
        });
        Schema::table('trivias_ganadores', function (Blueprint $table) {
            $table->integer('id_temporada')->after('id_trivia')->nullable();
            
        });

        Schema::table('trivias_respuestas', function (Blueprint $table) {
            $table->integer('id_temporada')->after('id_trivia')->nullable();
            $table->integer('id_distribuidor')->after('id_temporada')->nullable();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('evaluaciones_respuestas', function (Blueprint $table) {
            $table->dropColumn('id_temporada');
            $table->dropColumn('id_distribuidor');
        });
        Schema::table('jackpot_intentos', function (Blueprint $table) {
            $table->dropColumn('id_temporada');
            $table->dropColumn('id_distribuidor');
        });
        Schema::table('jackpot_respuestas', function (Blueprint $table) {
            $table->dropColumn('id_temporada');
            $table->dropColumn('id_distribuidor');
        });
        Schema::table('sesiones_visualizaciones', function (Blueprint $table) {
            $table->dropColumn('id_temporada');
            $table->dropColumn('id_distribuidor');
        });
        Schema::table('trivias_ganadores', function (Blueprint $table) {
            $table->dropColumn('id_temporada');
        });
        Schema::table('trivias_respuestas', function (Blueprint $table) {
            $table->dropColumn('id_temporada');
            $table->dropColumn('id_distribuidor');
        });
    }
};
