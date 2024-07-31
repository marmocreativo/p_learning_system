<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CanjeoCortesUsuarios extends Model
{
    protected $table = "canjeo_cortes_usuarios";
    public $timestamps = false;
    use HasFactory;
}
