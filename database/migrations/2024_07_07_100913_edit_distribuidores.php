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
         Schema::table('distribuidores', function (Blueprint $table) {
            $table->string('imagen')->nullable()->default('distribuidor_default.png');
            $table->string('imagen_fondo_a')->nullable()->default('fondo_distribuidor_default.png');
            $table->string('imagen_fondo_b')->nullable()->default('fondo_distribuidor_default.png');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('distribuidores', function (Blueprint $table) {
            $table->dropColumn('imagen');
            $table->dropColumn('imagen_fondo_a');
            $table->dropColumn('imagen_fondo_b');
        });
    }
};
