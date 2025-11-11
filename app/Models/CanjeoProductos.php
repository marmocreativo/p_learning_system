<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class CanjeoProductos extends Model
{
    use HasFactory;

    protected $table = "canjeo_productos";
    public $timestamps = false;

    protected $fillable = [
        'id_temporada',
        'nombre',
        'descripcion',
        'variaciones',
        'imagen',
        'creditos',
        'limite_total',
        'limite_usuario',
        'contenido',
        'variaciones_cantidad',
        'region',
        'orden',
    ];

    protected $casts = [
        'variaciones' => 'array',
        'variaciones_cantidad' => 'array',
    ];

    public function transacciones(): HasMany
    {
        return $this->hasMany(CanjeoTransaccionesProductos::class, 'id_producto', 'id');
    }
}