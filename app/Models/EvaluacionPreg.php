<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluacionPreg extends Model
{
    use HasFactory;
    protected $table = "evaluaciones_preguntas";
    public $timestamps = false;
}
