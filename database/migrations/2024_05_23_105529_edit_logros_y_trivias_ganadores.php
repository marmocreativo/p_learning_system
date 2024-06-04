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

        Schema::table('trivias_ganadores', function (Blueprint $table) {
            $table->longText('direccion_delegacion')->nullable();
        });
        Schema::table('logros', function (Blueprint $table) {
            $table->string('region')->nullable()->default('México');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
