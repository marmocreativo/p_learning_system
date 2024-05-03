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
        Schema::table('logros', function (Blueprint $table) {
            $table->string('imagen')->after('nivel_usuario')->nullable()->default('default_logro.jpg');
            $table->string('imagen_fondo')->after('imagen')->nullable()->default('default_logro_fondo.jpg');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('logros', function (Blueprint $table) {
            
            $table->dropColumn('imagen');
            $table->dropColumn('imagen_fondo');
        });
    }
};
