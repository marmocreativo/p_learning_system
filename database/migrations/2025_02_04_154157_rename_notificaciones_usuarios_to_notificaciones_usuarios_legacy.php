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
        Schema::rename('notificaciones_usuarios', 'notificaciones_usuarios_legacy');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('notificaciones_usuarios_legacy', 'notificaciones_usuarios');
    }
};
