<?php

namespace App\Http\Controllers;

use App\Models\Sku;
use Illuminate\Http\Request;

class SkuController extends Controller
{
    //
    public function getSkus(Request $request)
    {
        $skus = Sku::select('id', 'sku_clean')
            ->where('desafio', $request->input('desafio'))
            ->whereNotNull('sku_clean') // Evita valores nulos
            ->distinct('sku_clean') // Asegura que cada sku_clean sea único
            //->limit(1000) // 🔥 Limita el resultado a 10 registros
            ->get(); // Devuelve una colección de objetos

        return response()->json($skus);
    }
}
