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
            $table->longText('mensaje_antes')->after('titulo')->nullable()->default('');
            $table->longText('mensaje_despues')->after('mensaje_antes')->nullable()->default('');
            $table->string('estado')->after('trivia')->default('inactivo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('jackpot', function (Blueprint $table) {
            
            $table->dropColumn('mensaje_antes');
            $table->dropColumn('mensaje_despues');
            $table->dropColumn('estado');
        });
    }
};
