<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CanjeoTransacciones extends Model
{
    protected $table = "canjeo_transacciones";
    public $timestamps = false;
    use HasFactory;

    public function productos()
    {
        return $this->hasMany(CanjeoTransaccionesProductos::class, 'id_transacciones');
    }
}
