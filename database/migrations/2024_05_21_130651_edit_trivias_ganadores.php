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
        Schema::table('trivias_ganadores', function (Blueprint $table) {
            $table->longText('direccion_nombre')->nullable();
            $table->longText('direccion_calle')->nullable();
            $table->string('direccion_numero')->nullable()->default('');
            $table->longText('direccion_colonia')->nullable();
            $table->string('direccion_ciudad')->nullable()->default('');
            $table->string('direccion_codigo_postal')->nullable()->default('');
            $table->string('direccion_horario')->nullable()->default('');
            $table->longText('direccion_referencia')->nullable();
            $table->longText('direccion_notas')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('trivias_ganadores', function (Blueprint $table) {
            $table->dropColumn('direccion_nombre');
            $table->dropColumn('direccion_calle');
            $table->dropColumn('direccion_numero');
            $table->dropColumn('direccion_colonia');
            $table->dropColumn('direccion_ciudad');
            $table->dropColumn('direccion_codigo_postal');
            $table->dropColumn('direccion_horario');
            $table->dropColumn('direccion_referencia');
            $table->dropColumn('direccion_notas');
        });
    }
};
