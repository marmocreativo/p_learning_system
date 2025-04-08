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
        Schema::table('jackpot', function (Blueprint $table) {
            $table->string('tipo', 255)->nullable()->default('jackpot');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('jackpot', function (Blueprint $table) {
            $table->dropColumn('tipo');
        });
    }
};
