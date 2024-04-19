<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TriviaPreg extends Model
{
    protected $table = 'trivias_preguntas';
    use HasFactory;
    public $timestamps = false;
}
