<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogroAnexo extends Model
{
    use HasFactory;
    protected $table = "logros_anexos";
    public $timestamps = false;
}
