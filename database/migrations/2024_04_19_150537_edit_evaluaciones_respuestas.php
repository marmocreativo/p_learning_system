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
        Schema::table('evaluaciones_respuestas', function (Blueprint $table) {
            $table->renameColumn('id_evaluacion', 'id_pregunta');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('evaluaciones_respuestas', function (Blueprint $table) {
            $table->renameColumn('id_pregunta', 'id_evaluacion');
            
        });
    }
};
