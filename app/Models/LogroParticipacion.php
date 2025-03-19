<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogroParticipacion extends Model
{
    use HasFactory;
    protected $table = "logros_participantes";
    public $timestamps = false;

    protected $fillable = [
        'id_logro', 'id_temporada', 'id_distribuidor', 'id_usuario', 'id_usuario_b',
        'confirmacion_nivel_a', 'confirmacion_nivel_b', 'confirmacion_nivel_c', 
        'confirmacion_nivel_especial', 'estado', 'fecha_registro', 'fecha_finalizado', 'notas_arbitro'
    ];

    public function logro()
    {
        return $this->belongsTo(Logro::class, 'id_logro');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }

    public function distribuidor()
    {
        return $this->belongsTo(Distribuidor::class, 'id_distribuidor');
    }

    public function anexos()
    {
        return $this->hasMany(LogroAnexo::class, 'id_participacion');
    }

    public function anexosNoValidados()
    {
        return $this->hasMany(LogroAnexo::class, 'id_participacion')->where('validado', 'no');
    }
}
