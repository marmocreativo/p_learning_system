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
            $table->string('folio', 255)->nullable()->default(null);
            $table->string('moneda', 255)->nullable()->default(null);
            $table->dateTime('emision')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('logros_anexos', function (Blueprint $table) {
            $table->dropColumn('folio');
            $table->dropColumn('moneda');
            $table->dropColumn('emision');
        });
    }
};
