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
            $table->string('direccion_telefono')->nullable()->default('');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('canjeo_transacciones', function (Blueprint $table) {
            $table->dropColumn('direccion_telefono');
        });
    }
};
