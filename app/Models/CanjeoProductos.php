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

    protected $casts = [
        'variaciones' => 'array', // importante para acceder como array
        'variaciones_cantidad' => 'array',
    ];

    public function transacciones(): HasMany
    {
        return $this->hasMany(CanjeoTransaccionesProductos::class, 'id_producto', 'id');
    }
}
