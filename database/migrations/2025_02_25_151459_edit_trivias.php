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
        Schema::table('trivias', function (Blueprint $table) {
            $table->integer('cantidad_preguntas')->unsigned()->nullable()->default(2);
            $table->string('orden', 100)->nullable()->default('ordenado');
    
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('trivias', function (Blueprint $table) {
            $table->dropColumn('cantidad_preguntas');
            $table->dropColumn('orden');
        });
    }
};
