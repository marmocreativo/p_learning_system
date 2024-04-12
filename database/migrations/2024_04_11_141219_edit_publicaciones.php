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
            $table->string('id_cuenta')->after('id');
            $table->string('id_temporada')->after('id_cuenta');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('publicaciones', function (Blueprint $table) {
            $table->dropColumn('id_cuenta');
            $table->dropColumn('id_temporada');
        });
    }
};
