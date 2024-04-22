<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SesionVis extends Model
{
    use HasFactory;
    protected $table = "sesiones_visualizaciones";
    public $timestamps = false;
}
