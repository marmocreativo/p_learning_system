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
        Schema::create('canjeo_productos_galeria', function (Blueprint $table) {
            $table->id();
            $table->integer('id_producto');
            $table->string('imagen')->nullable()->default('producto_default.jpg');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('canjeo_productos_galeria');
    }
};
