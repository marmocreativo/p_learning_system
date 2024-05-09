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
        Schema::table('logros', function (Blueprint $table) {
            $table->integer('premio_a')->after('nivel_especial')->nullable()->default(0);
            $table->integer('premio_b')->after('premio_a')->nullable()->default(0);
            $table->integer('premio_c')->after('premio_b')->nullable()->default(0);
            $table->integer('premio_especial')->after('premio_c')->nullable()->default(0);
            $table->integer('cantidad_evidencias')->after('premio_especial')->nullable()->default(10);
        });

        Schema::table('logros_participantes', function (Blueprint $table) {
            $table->timestamp('fecha_finalizado')->nullable()->default(null);
            $table->longText('notas_arbitro')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('logros', function (Blueprint $table) {
            $table->dropColumn('premio_a');
            $table->dropColumn('premio_b');
            $table->dropColumn('premio_c');
            $table->dropColumn('premio_especial');
            $table->dropColumn('cantidad_evidencias');
        });

        Schema::table('logros_participantes', function (Blueprint $table) {
            $table->dropColumn('fecha_finalizado');
            $table->dropColumn('notas_arbitro');
        });
    }
};
