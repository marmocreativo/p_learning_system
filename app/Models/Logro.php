<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Logro extends Model
{
    use HasFactory;
    protected $table = "logros";
    public $timestamps = false;

    protected $fillable = [
        'id_temporada', 'nombre', 'instrucciones', 'contenido', 'premio', 
        'nivel_a', 'nivel_b', 'nivel_c', 'nivel_especial', 
        'premio_a', 'premio_b', 'premio_c', 'premio_especial', 
        'cantidad_evidencias', 'nivel_usuario', 'imagen', 'imagen_fondo', 
        'fecha_inicio', 'fecha_vigente', 'region'
    ];

    public function participaciones()
    {
        return $this->hasMany(LogroParticipacion::class, 'id_logro');
    }

    public function anexos()
    {
        return $this->hasMany(LogroAnexo::class, 'id_logro');
    }
}
