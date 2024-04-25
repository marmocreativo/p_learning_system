<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TriviaGanador extends Model
{
    use HasFactory;
    protected $table = "trivias_ganadores";
    public $timestamps = false;
}
