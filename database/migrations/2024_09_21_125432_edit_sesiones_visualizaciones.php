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
        Schema::table('sesiones_visualizaciones', function (Blueprint $table) {
            $table->string('fecha_video_1')->nullable()->default(null);
            $table->string('fecha_video_2')->nullable()->default(null);
            $table->string('fecha_video_3')->nullable()->default(null);
            $table->string('fecha_video_4')->nullable()->default(null);
            $table->string('fecha_video_5')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('sesiones_visualizaciones', function (Blueprint $table) {
            $table->dropColumn('fecha_video_1');
            $table->dropColumn('fecha_video_2');
            $table->dropColumn('fecha_video_3');
            $table->dropColumn('fecha_video_4');
            $table->dropColumn('fecha_video_5');
        });
    }
};
