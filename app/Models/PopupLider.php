<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PopupLider extends Model
{
    use HasFactory;

    protected $table = 'popup_lideres';

    protected $fillable = [
        'id_temporada',
        'titulo',
        'resumen',
        'imagen',
        'texto_boton',
        'enlace_boton',
        'distribuidores',
        'estado'
    ];

    protected $casts = [
        'distribuidores' => 'array'
    ];
}