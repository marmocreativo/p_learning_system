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
        Schema::create('intentos_login', function (Blueprint $table) {
            $table->id();
            $table->integer('id_cuenta')->unsigned()->nullable()->default(null);
            $table->integer('id_temporada')->unsigned()->nullable()->default(null);
            $table->string('usuario', 255)->nullable()->default(null);
            $table->string('try', 255)->nullable()->default(null);
            $table->bigInteger('id_usuario')->unsigned()->nullable()->default(null);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('intentos_login');
    }
};
