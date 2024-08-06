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
        Schema::table('canjeo_productos', function (Blueprint $table) {
            $table->longText('variaciones_cantidad')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('canjeo_productos', function (Blueprint $table) {
            $table->dropColumn('variaciones_cantidad');
        });
    }
};
