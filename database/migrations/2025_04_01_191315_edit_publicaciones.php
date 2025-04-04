<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('publicaciones', function (Blueprint $table) {
            $table->string('titulo')->nullable()->default(null)->change();
            $table->dropUnique(['url']);
            $table->string('url')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('publicaciones', function (Blueprint $table) {
            $table->string('titulo')->nullable(false)->default('')->change();
            $table->string('url')->nullable(false)->unique()->change();
        });
    }
};
