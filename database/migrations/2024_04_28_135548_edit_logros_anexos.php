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
        Schema::table('logros_anexos', function (Blueprint $table) {
            $table->integer('id_participacion')->after('id_logro')->nullable();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('seslogros_anexosiones', function (Blueprint $table) {
            
            $table->dropColumn('id_participacion');
        });
    }
};
