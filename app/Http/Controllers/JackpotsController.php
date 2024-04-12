<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\Jackpot;
use App\Models\JackpotPreg;
use App\Models\JackpotRes;
use App\Models\JackpotIntentos;
use App\Models\Clase;
use App\Models\Temporada;

class JackpotsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $id_temporada = $request->input('id_temporada');
        $jackpots = Jackpot::where('id_temporada', $id_temporada)->paginate();
        return view('admin/jackpot_lista', compact('jackpots'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        //
        $temporada = Temporada::find($request->input('id_temporada'));
        return view('admin/jackpot_form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         //
         $jackpot = new Jackpot();

         $jackpot->id_cuenta = $request->IdCuenta;
         $jackpot->id_temporada = $request->IdTemporada;
         $jackpot->titulo = $request->Titulo;
         
         $jackpot->fecha_publicacion = date('Y-m-d H:i:s', strtotime($request->FechaPublicacion.' '.$request->HoraPublicacion));
         $jackpot->fecha_vigencia = date('Y-m-d H:i:s', strtotime($request->FechaVigencia.' '.$request->HoraVigencia));
         $jackpot->intentos = $request->Intentos;
         $jackpot->trivia = $request->Trivia;
 
         $jackpot->save();
 
         return redirect()->route('jackpots.show', $jackpot->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        //
        $jackpot = Jackpot::find($id);
        return view('admin/jackpot_detalles', compact('jackpot'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
         //
         $jackpot = Jackpot::findOrFail($id);
         $id_temporada = $jackpot->id_temporada;
         // Buscar y eliminar registros relacionados en otras tablas
         JackpotPreg::where('id_jackpot', $id)->delete();
         JackpotRes::where('id_jackpot', $id)->delete();
         JackpotIntentos::where('id_jackpot', $id)->delete();
 
 
         $jackpot->delete();
         return redirect()->route('jackpots', ['id_temporada'=>$id_temporada]);
    }

    /**
     * Funciones API
     */

     public function datos_jackpot_api(Request $request)
     {
         //Variables
         $fecha_actual = Carbon::now();
         // consulta
         $trivia = Jackpot::where('fecha_publicacion', '<=', $fecha_actual)
                       ->where('fecha_vigencia', '>', $fecha_actual)
                       ->first();
         return response()->json($trivia);
     }
}
