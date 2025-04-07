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
            $table->string('bono_login', 100)->nullable()->default('no');
            $table->integer('bono_login_cantidad')->unsigned()->nullable()->default(1500);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('cuentas', function (Blueprint $table) {
            $table->dropColumn('bono_login');
            $table->dropColumn('bono_login_cantidad');
        });
    }
};
