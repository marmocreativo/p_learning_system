<?php

namespace App\Http\Controllers;
use App\Models\Temporada;
use App\Models\Cuenta;
use App\Models\SesionEv;

use Illuminate\Http\Request;

class TemporadasController extends Controller
{
    //
    //
     /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $id_cuenta = $request->input('id_cuenta');
        $temporadas = Temporada::where('id_cuenta', $id_cuenta)->paginate();
        return view('admin/temporada_lista', compact('temporadas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('admin/temporada_form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $temporada = new Temporada();

        $temporada->id_cuenta = $request->IdCuenta;
        $temporada->nombre = $request->Nombre;
        $temporada->descripcion = $request->Descripcion;
        $temporada->titulo_landing = $request->TituloLanding;
        $temporada->mensaje_landing = $request->MensajeLanding;
        $temporada->fecha_inicio = $request->FechaInicio;
        $temporada->fecha_final = $request->FechaFinal;


        $temporada->save();

        return redirect()->route('temporadas.show', $temporada->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $temporada = Temporada::find($id);
        return view('admin/temporada_detalles', compact('temporada'));

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $temporada = Temporada::find($id);
        return view('admin/temporada_form_actualizar', compact('temporada'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $temporada = Temporada::find($id);

        $temporada->id_cuenta = $request->IdCuenta;
        $temporada->nombre = $request->Nombre;
        $temporada->descripcion = $request->Descripcion;
        $temporada->titulo_landing = $request->TituloLanding;
        $temporada->mensaje_landing = $request->MensajeLanding;
        $temporada->fecha_inicio = $request->FechaInicio;
        $temporada->fecha_final = $request->FechaFinal;

        $temporada->save();

        return redirect()->route('temporadas.show', $temporada->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $temporada = Temporada::find($id);
        $temporada->delete();
        return redirect()->route('temporadas');
    }

    /* FUNCIONES API */
    public function show_api(Request $request)
    {
        //
        $cuenta = Cuenta::find($request->id);
        $temporada = Temporada::find($cuenta->temporada_actual);
        return response()->json($temporada);
        //return 'Hola';  
    }

    public function temporada_y_sesiones(Request $request)
    {

        $temporada = Temporada::find($request->input('id'));
        $sesiones = SesionEv::where('id_temporada', $temporada->id)->get();

        $completo = [
            'temporada' => $temporada,
            'sesiones' => $sesiones,
        ];
        return response()->json($completo);
    }
    public function lista_api(Request $request)
    {
        //
        $temporadas = Temporada::where('id_cuenta', $request->id_cuenta)->get();
        return response()->json($temporadas);
    }
}
