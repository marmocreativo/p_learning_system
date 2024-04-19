<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JackpotPreg extends Model
{
    use HasFactory;
    protected $table = "jackpot_preguntas";
    public $timestamps = false;
}
