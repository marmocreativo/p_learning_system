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
        Schema::table('publicaciones', function (Blueprint $table) {
            $table->string('btn_carrusel_txt', 255)->nullable()->default(null);
            $table->string('btn_carrusel_link', 255)->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('publicaciones', function (Blueprint $table) {
            $table->dropColumn('btn_carrusel_txt');
            $table->dropColumn('btn_carrusel_link');
        });
    }
};
