<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogroParticipacion extends Model
{
    use HasFactory;
    protected $table = "logros_participaciones";
    public $timestamps = false;
}
