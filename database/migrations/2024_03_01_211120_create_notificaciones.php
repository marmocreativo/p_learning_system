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
        Schema::create('notificaciones', function (Blueprint $table) {
            $table->id();
            $table->integer('id_cuenta');
            $table->integer('id_temporada');
            $table->string('titulo')->nullable()->default('');
            $table->longText('contenido')->nullable()->default('');
            $table->string('mostrar_en')->nullable()->default('inicio');
            //inicio
            //sitio_completo
            //sesion
            //trivia
            //jackpot
            $table->string('mostrar_en_id')->nullable()->default('');
            $table->string('tipo_mensaje')->nullable()->default('popup');
            //popup
            //pantalla_bloqueo
            //notificacion
            $table->string('permanencia')->nullable()->default('siempre');
            //siempre
            //por_sesion
            
            $table->string('condicion')->nullable()->default('ver_publicacion');
            //ver_publicacion
            //aceptar_publicacion
            //completar_informacion
            //ver_mensaje
            $table->string('id_publicacion_mostrar')->nullable()->default('');
            $table->string('tabla_revisar')->nullable()->default('');
            $table->string('columna_revisar')->nullable()->default('');
            $table->timestamp('fecha_publicacion')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notificaciones');
    }
};
