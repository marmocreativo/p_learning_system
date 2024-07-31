<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CanjeoProductos extends Model
{
    protected $table = "canjeo_productos";
    public $timestamps = false;
    use HasFactory;
}
