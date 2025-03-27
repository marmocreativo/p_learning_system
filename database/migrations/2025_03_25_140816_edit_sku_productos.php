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
            $table->string('desafio', 255)->nullable()->default(null);
            $table->string('sku', 255)->nullable()->default(null);
            $table->text('detalles')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('sku_productos', function (Blueprint $table) {
            $table->dropColumn('desafio');
            $table->dropColumn('sku');
            $table->dropColumn('detalles');
        });
    }
};
