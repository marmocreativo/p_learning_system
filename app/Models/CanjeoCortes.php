<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CanjeoCortes extends Model
{
    use HasFactory;
    
    protected $table = "canjeo_cortes";
    public $timestamps = false;
    
    protected $fillable = [
        'id_temporada',
        'titulo',
        'fecha_inicio',
        'fecha_final',
        'prueba',
        'fecha_publicacion_inicio',
        'fecha_publicacion_final'
    ];
    
    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_final' => 'date',
        'fecha_publicacion_inicio' => 'date',
        'fecha_publicacion_final' => 'date',
    ];
}