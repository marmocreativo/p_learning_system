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
            $table->string('top10', 100)->nullable()->default('si');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('cuentas', function (Blueprint $table) {
            $table->dropColumn('top10');
        });
    }
};
