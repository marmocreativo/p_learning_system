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
        Schema::table('cuentas', function (Blueprint $table) {
            $table->string('color_realse', 255)->nullable()->default('#F0B323');
            $table->string('logotipo', 255)->nullable()->default('default.png');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('cuentas', function (Blueprint $table) {
           $table->dropColumn('color_realse');
           $table->dropColumn('logotipo');
        });
    }
};