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
        Schema::table('canjeo_transacciones', function (Blueprint $table) {
            $table->dropColumn('fecha_registro');
            $table->dropColumn('fecha_confirmado');
            $table->dropColumn('fecha_envio');
        });

        Schema::table('canjeo_transacciones', function (Blueprint $table) {
            $table->date('fecha_registro')->nullable()->default(null);
            $table->date('fecha_confirmado')->nullable()->default(null);
            $table->date('fecha_envio')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('canjeo_transacciones', function (Blueprint $table) {
            $table->dropColumn('fecha_registro');
            $table->dropColumn('fecha_confirmado');
            $table->dropColumn('fecha_envio');
        });

        Schema::table('canjeo_transacciones', function (Blueprint $table) {
            $table->string('fecha_registro')->nullable()->default(null);
            $table->string('fecha_confirmado')->nullable()->default(null);
            $table->string('fecha_envio')->nullable()->default(null);
        });
    }
};
