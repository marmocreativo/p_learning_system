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
        Schema::table('distribuidores', function (Blueprint $table) {
            $table->string('default_pass')->after('region')->nullable()->default('');
        });

        Schema::table('usuarios_suscripciones', function (Blueprint $table) {
            $table->string('region')->after('puntos_totales')->nullable()->default('');
            $table->string('pais')->after('region')->nullable()->default('');
            $table->string('nivel_usuario')->after('nivel')->nullable()->default('ventas');
        });

        Schema::table('logros', function (Blueprint $table) {
            $table->longText('contenido')->after('instrucciones')->nullable();
            $table->longText('nivel_a')->after('contenido')->nullable();
            $table->longText('nivel_b')->after('nivel_a')->nullable();
            $table->longText('nivel_c')->after('nivel_b')->nullable();
            $table->longText('nivel_especial')->after('nivel_c')->nullable();
            $table->longText('nivel_usuario')->after('nivel_especial')->nullable();
        });

        Schema::table('logros_participantes', function (Blueprint $table) {
            $table->integer('id_usuario_b')->after('id_usuario')->nullable();
            $table->string('confirmacion_nivel_a')->after('id_usuario_b')->nullable()->default('no');
            $table->string('confirmacion_nivel_b')->after('confirmacion_nivel_a')->nullable()->default('no');
            $table->string('confirmacion_nivel_c')->after('confirmacion_nivel_b')->nullable()->default('no');
            $table->string('confirmacion_nivel_especial')->after('confirmacion_nivel_c')->nullable()->default('no');
        });

        Schema::table('logros_anexos', function (Blueprint $table) {
            $table->integer('id_usuario_b')->after('id_usuario')->nullable();
            $table->string('nivel')->after('id_usuario_b')->nullable()->default('a');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('distribuidores', function (Blueprint $table) {
            
            $table->dropColumn('default_pass');
        });
        Schema::table('usuarios_suscripciones', function (Blueprint $table) {
            $table->dropColumn('region');
            $table->dropColumn('pais');
            $table->dropColumn('nivel_usuario');
        });
        Schema::table('logros', function (Blueprint $table) {
            $table->dropColumn('contenido');
            $table->dropColumn('nivel_a');
            $table->dropColumn('nivel_b');
            $table->dropColumn('nivel_c');
            $table->dropColumn('nivel_especial');
            $table->dropColumn('nivel_usuario');
        });
        Schema::table('logros_participantes', function (Blueprint $table) {
            $table->dropColumn('id_usuario_b');
            $table->dropColumn('confirmacion_nivel_a');
            $table->dropColumn('confirmacion_nivel_b');
            $table->dropColumn('confirmacion_nivel_c');
            $table->dropColumn('confirmacion_nivel_especial');
        });
        Schema::table('logros_anexos', function (Blueprint $table) {
            $table->dropColumn('id_usuario_b');
            $table->dropColumn('nivel');
        });
    }
};
