<?php

namespace App\Http\Controllers;

use App\Models\Sku;
use App\Models\Logro;
use Illuminate\Http\Request;

class SkuController extends Controller
{
    //
    public function getSkus(Request $request)
    {
        $logro = Logro::find($request->input('desafio'));

        $skus = Sku::select('id', 'sku_clean')
            ->where('desafio', $logro->nombre)
            ->whereNotNull('sku_clean') // Evita valores nulos
            ->distinct('sku_clean') // Asegura que cada sku_clean sea único
            //->limit(1000) // 🔥 Limita el resultado a 10 registros
            ->get(); // Devuelve una colección de objetos

        return response()->json($skus);
    }
    public function getSkusBusqueda(Request $request)
    {

        $skus = Sku::where('sku_clean', 'like', '%' . $request->input('termino') . '%')->get();

        return response()->json($skus);
    }

    public function getSkusBusquedaFiltrada(Request $request)
    {

        $skus = Sku::where('id_logro', $request->input('desafio'))->where('sku_clean', 'like', '%' . $request->input('termino') . '%')->get();

        return response()->json($skus);
    }
}
