<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CanjeoTransacciones extends Model
{
    use HasFactory;
    
    protected $table = "canjeo_transacciones";
    public $timestamps = false;
    
    protected $fillable = [
        'id_temporada',
        'id_corte',
        'id_usuario',
        'creditos',
        'direccion_nombre',
        'direccion_calle',
        'direccion_numero',
        'direccion_numeroint',
        'direccion_colonia',
        'direccion_ciudad',
        'direccion_codigo_postal',
        'direccion_horario',
        'direccion_referencia',
        'direccion_notas',
        'confirmado',
        'enviado',
        'fecha_registro',
        'fecha_confirmado',
        'fecha_envio',
        'direccion_telefono',
        'direccion_municipio'
    ];

    public function productos()
    {
        return $this->hasMany(CanjeoTransaccionesProductos::class, 'id_transacciones');
    }
    
    // Relación con el corte
    public function corte()
    {
        return $this->belongsTo(CanjeoCortes::class, 'id_corte', 'id');
    }
}