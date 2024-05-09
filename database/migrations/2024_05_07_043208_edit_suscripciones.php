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

        Schema::table('usuarios_suscripciones', function (Blueprint $table) {
            $table->string('champions_a')->after('puntos_totales')->nullable()->default('no');
            $table->string('champions_b')->after('champions_a')->nullable()->default('no');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('usuarios_suscripciones', function (Blueprint $table) {
            $table->dropColumn('champions_a');
            $table->dropColumn('champions_b');
        });
    }
};
