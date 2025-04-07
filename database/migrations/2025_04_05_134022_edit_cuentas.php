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
        Schema::table('cuentas', function (Blueprint $table) {
            $table->string('fondo_menu', 255)->nullable()->default('#213746');
            $table->string('texto_menu', 255)->nullable()->default('#fff');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('cuentas', function (Blueprint $table) {
            $table->dropColumn('fondo_menu');
            $table->dropColumn('texto_menu');
        });
    }
};
