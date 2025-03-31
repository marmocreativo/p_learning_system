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
            $table->bigInteger('id_logro')->nullable()->unsigned(); // El nullable ya es suficiente para permitir nulos
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('sku_productos', function (Blueprint $table) {
            $table->dropColumn('id_logro');
        });
    }
};
