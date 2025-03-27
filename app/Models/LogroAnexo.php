<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogroAnexo extends Model
{
    use HasFactory;
    protected $table = "logros_anexos";
    public $timestamps = false;

    protected $fillable = [
        'id_logro', 'id_participacion', 'id_temporada', 'id_usuario', 'id_usuario_b',
        'nivel', 'documento', 'fecha_registro', 'validado', 'comentario'
    ];

    public function logro()
    {
        return $this->belongsTo(Logro::class, 'id_logro');
    }

    public function participacion()
    {
        return $this->belongsTo(LogroParticipacion::class, 'id_participacion');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }

    public function productos()
    {
        return $this->hasMany(LogroAnexoProducto::class, 'id_anexo');
    }
}
