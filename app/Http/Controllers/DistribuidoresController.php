<?php

namespace App\Http\Controllers;
use App\Models\Distribuidor;
use App\Models\DistribuidoresSuscripciones;
use App\Models\Temporada;
use App\Models\Clase;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DistribuidoresController extends Controller
{
    //
     /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $distribuidores = Distribuidor::paginate();
        return view('admin/distribuidor_lista', compact('distribuidores'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('admin/distribuidor_form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $distribuidor = new Distribuidor();

        $distribuidor->nombre = $request->Nombre;
        $distribuidor->pais = $request->Pais;
        $distribuidor->region = $request->Region;
        $distribuidor->nivel = $request->Nivel;
        $distribuidor->estado = $request->Estado;


        $distribuidor->save();

        return redirect()->route('distribuidores.show', $distribuidor->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $distribuidor = Distribuidor::find($id);
        return view('admin/distribuidor_detalles', compact('distribuidor'));

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $distribuidor = Distribuidor::find($id);
        return view('admin/distribuidor_form_actualizar', compact('distribuidor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $distribuidor = Distribuidor::find($id);

        $distribuidor->nombre = $request->Nombre;
        $distribuidor->pais = $request->Pais;
        $distribuidor->region = $request->Region;
        $distribuidor->nivel = $request->Nivel;
        $distribuidor->estado = $request->Estado;

        $distribuidor->save();

        return redirect()->route('distribuidores.show', $distribuidor->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $distribuidor = Distribuidor::find($id);
        $distribuidor->delete();
        return redirect()->route('distribuidores');
    }

    /**
     * Funciones por temporada
     */

    public function distribuidores_suscritos(Request $request)
    {
        //
        $id_temporada = $request->input('id_temporada');
        $suscripciones = DB::table('distribuidores')
            ->join('distribuidores_suscripciones', 'distribuidores.id', '=', 'distribuidores_suscripciones.id_distribuidor')
            ->where('distribuidores_suscripciones.id_temporada', '=', $id_temporada)
            ->select('distribuidores.*', 'distribuidores_suscripciones.*')
            ->get();
        //$usuarios = UsuariosSuscripciones::where('id_temporada', $id_temporada)->paginate();
        return view('admin/distribuidor_lista_suscripciones', compact('suscripciones'));
    }

    public function suscripcion(Request $request,)
    {
        //
        $id_temporada = $request->input('id_temporada');
        $temporada = Temporada::find($id_temporada);
        $clases = Clase::where('elementos','usuarios')->get();
        return view('admin/distribuidor_form_suscripcion', compact('clases', 'temporada'));
    }

    public function suscribir(Request $request)
    {
            // Verificar si el usuario ya existe
        $distribuidor = Distribuidor::where('nombre', $request->Nombre)->first();
        

        if (!$distribuidor) {
            $distribuidor = new Distribuidor();
            
            $distribuidor->nombre = $request->Nombre;
            $distribuidor->pais = $request->Pais;
            $distribuidor->region = $request->Region;
            $distribuidor->nivel = $request->Nivel;
            $distribuidor->estado = $request->Estado;

            $distribuidor->save();
        }


        $suscripcion = DistribuidoresSuscripciones::where('id_distribuidor', $distribuidor->id)->where('id_temporada', $request->IdTemporada)->first();
        if (!$suscripcion) {
            $suscripcion = new DistribuidoresSuscripciones();
            $suscripcion->id_distribuidor = $distribuidor->id;
            $suscripcion->id_cuenta = $request->IdCuenta;
            $suscripcion->id_temporada = $request->IdTemporada;
            $suscripcion->cantidad_usuarios = 0;
            $suscripcion->nivel = 'completo';
            $suscripcion->save();
        }
        
        return redirect()->route('distribuidores.suscritos', ['id_temporada'=>$request->IdTemporada]);
        
    }

    public function desuscribir(Request $request, string $id)
    {
        //
        $id_temporada = $request->input('id_temporada');
        $suscripcion = UsuariosSuscripciones::find($id);
        $suscripcion->delete();
        return redirect()->route('admin_usuarios_suscritos', ['id_temporada'=>$request->IdTemporada]);
        
    }
}
