<?php

namespace App\Models;
use App\Models\User;
use App\Models\LogroAnexo;
use App\Models\LogroParticipacion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogroAnexoProducto extends Model
{
    use HasFactory;

    protected $table = "logros_anexos_productos";

    // Relación con usuario (id_usuario)
    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }

    // Relación con participación (id_anexo)
    public function anexo()
    {
        return $this->belongsTo(LogroAnexo::class, 'id_anexo');
    }

    // Relación con participación (id_participacion)
    public function participacion()
    {
        return $this->belongsTo(LogroParticipacion::class, 'id_participacion');
    }
}