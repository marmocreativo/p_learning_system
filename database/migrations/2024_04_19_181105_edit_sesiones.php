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
            $table->string('imagen_instructor')->after('imagen_fondo')->nullable()->default('default_instructor.jpg');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('sesiones', function (Blueprint $table) {
            
            $table->dropColumn('imagen_instructor');
        });
    }
};
