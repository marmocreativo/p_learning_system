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
            $table->string('tabla_mx', 255)->nullable()->default(null);
            $table->string('tabla_rola', 255)->nullable()->default(null);
            $table->decimal('premio_rola_a', 10, 2)->nullable()->default(0.0);
            $table->decimal('premio_rola_b', 10, 2)->nullable()->default(0.0);
            $table->decimal('premio_rola_c', 10, 2)->nullable()->default(0.0);
            $table->decimal('premio_rola_especial', 10, 2)->nullable()->default(0.0);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('logros', function (Blueprint $table) {
            $table->dropColumn('tabla_mx');
            $table->dropColumn('tabla_rola');
            $table->dropColumn('premio_rola_a');
            $table->dropColumn('premio_rola_b');
            $table->dropColumn('premio_rola_c');
            $table->dropColumn('premio_rola_especial');
        });
    }
};
