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
        Schema::rename('users', 'usuarios');
        Schema::table('usuarios', function (Blueprint $table) {
            $table->renameColumn('name', 'nombre');
            $table->renameColumn('email_verified_at', 'email_verificado_el');
            
        });

        Schema::table('usuarios', function (Blueprint $table) {
            $table->string('legacy_id')->after('id')->unique()->default(uniqid('',true));
            $table->string('apellidos')->after('nombre')->default('');
            $table->string('telefono')->after('email_verificado_el')->default('');
            $table->string('whatsapp')->after('telefono')->default('');
            $table->date('fecha_nacimiento')->nullable()->after('whatsapp')->default(null);
            $table->string('lista_correo')->after('password')->default('no');
            $table->string('clase')->after('lista_correo')->default('usuario');
            $table->string('estado')->after('clase')->default('activo');
            $table->timestamp('fecha_borrado')->after('estado')->nullable()->default(null);
            $table->string('imagen')->after('lista_correo')->default('default.jpg');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
