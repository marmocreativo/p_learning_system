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
        Schema::table('popups', function (Blueprint $table) {
            $table->string('boton_texto', 255)->nullable()->default(null);
            $table->string('boton_link', 255)->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('popups', function (Blueprint $table) {
            $table->dropColumn('boton_texto');
            $table->dropColumn('boton_link');
        });
    }
};
