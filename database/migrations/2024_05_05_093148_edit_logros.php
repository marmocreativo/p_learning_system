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
            $table->dropColumn('premio_a');
            $table->dropColumn('premio_b');
            $table->dropColumn('premio_c');
            $table->dropColumn('premio_especial');
        });
        Schema::table('logros', function (Blueprint $table) {
            $table->decimal('premio_a', 10, 2)->after('nivel_especial')->nullable()->default(0.00);
            $table->decimal('premio_b', 10, 2)->after('premio_a')->nullable()->default(0.00);
            $table->decimal('premio_c', 10, 2)->after('premio_b')->nullable()->default(0.00);
            $table->decimal('premio_especial', 10, 2)->after('premio_c')->nullable()->default(0.00);
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
        });
        Schema::table('logros', function (Blueprint $table) {
            $table->integer('premio_a')->after('nivel_especial')->nullable()->default(0);
            $table->integer('premio_b')->after('premio_a')->nullable()->default(0);
            $table->integer('premio_c')->after('premio_b')->nullable()->default(0);
            $table->integer('premio_especial')->after('premio_c')->nullable()->default(0);
        });
    }
};
