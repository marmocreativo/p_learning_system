<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CanjeoTransaccionesProductos extends Model
{
    protected $table = "canjeo_transacciones_productos";
    public $timestamps = false;
    use HasFactory;
}
