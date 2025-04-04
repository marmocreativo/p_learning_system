<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('publicaciones', function (Blueprint $table) {
            $table->string('titulo')->nullable()->change();
            $table->string('url')->nullable()->change();
            $table->text('descripcion')->nullable()->change();
            $table->text('contenido')->nullable()->change();
            $table->text('keywords')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('publicaciones', function (Blueprint $table) {
            $table->string('titulo')->nullable(false)->change();
            $table->string('url')->nullable(false)->change();
            $table->text('descripcion')->nullable(false)->change();
            $table->text('contenido')->nullable(false)->change();
            $table->text('keywords')->nullable(false)->change();
        });
    }
};
