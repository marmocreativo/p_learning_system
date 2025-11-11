<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CanjeoTransaccionesProductos extends Model
{
    use HasFactory;
    
    protected $table = 'canjeo_transacciones_productos';
    public $timestamps = false;

    public function producto()
    {
        return $this->belongsTo(CanjeoProductos::class, 'id_producto', 'id');
    }
    
    // Relación con la transacción principal para obtener el id_corte
    public function transaccion()
    {
        return $this->belongsTo(CanjeoTransacciones::class, 'id_transacciones', 'id');
    }
}