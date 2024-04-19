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
        Schema::table('sesiones', function (Blueprint $table) {
            $table->string('puesto_instructor')->after('nombre_instructor')->nullable()->default('');
            $table->longText('bio_instructor')->after('puesto_instructor')->nullable()->default('');
            $table->string('correo_instructor')->after('bio_instructor')->nullable()->default('');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('sesiones', function (Blueprint $table) {
            
            $table->dropColumn('puesto_instructor');
            $table->dropColumn('bio_instructor');
            $table->dropColumn('correo_instructor');
        });
    }
};
