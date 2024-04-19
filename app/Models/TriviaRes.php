<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TriviaRes extends Model
{
    protected $table = 'trivias_respuestas';
    use HasFactory;
    public $timestamps = false;
}
