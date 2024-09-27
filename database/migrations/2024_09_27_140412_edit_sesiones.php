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
            $table->string('puntaje_video_1_estreno')->nullable()->default(null);
            $table->string('puntaje_video_2_estreno')->nullable()->default(null);
            $table->string('puntaje_video_3_estreno')->nullable()->default(null);
            $table->string('puntaje_video_4_estreno')->nullable()->default(null);
            $table->string('puntaje_video_5_estreno')->nullable()->default(null);
            $table->string('puntaje_video_1_normal')->nullable()->default(null);
            $table->string('puntaje_video_2_normal')->nullable()->default(null);
            $table->string('puntaje_video_3_normal')->nullable()->default(null);
            $table->string('puntaje_video_4_normal')->nullable()->default(null);
            $table->string('puntaje_video_5_normal')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('sesiones', function (Blueprint $table) {
            $table->dropColumn('puntaje_video_1_estreno');
            $table->dropColumn('puntaje_video_2_estreno');
            $table->dropColumn('puntaje_video_3_estreno');
            $table->dropColumn('puntaje_video_4_estreno');
            $table->dropColumn('puntaje_video_5_estreno');
            $table->dropColumn('puntaje_video_1_normal');
            $table->dropColumn('puntaje_video_2_normal');
            $table->dropColumn('puntaje_video_3_normal');
            $table->dropColumn('puntaje_video_4_normal');
            $table->dropColumn('puntaje_video_5_normal');
        });
    }
};
