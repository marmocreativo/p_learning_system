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
            $table->renameColumn('id_cliente', 'id_cuenta');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('sesiones', function (Blueprint $table) {
            $table->renameColumn('id_cuenta', 'id_cliente');
            
        });
    }
};
