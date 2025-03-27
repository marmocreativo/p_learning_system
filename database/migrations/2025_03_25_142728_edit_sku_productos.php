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
        Schema::table('sku_productos', function (Blueprint $table) {
            $table->string('sku_clean', 255)->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('sku_productos', function (Blueprint $table) {
            $table->dropColumn('sku_clean');
        });
    }
};
