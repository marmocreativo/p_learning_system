<?php

namespace App\Http\Controllers;

use App\Models\Configuracion;
use App\Models\Clase;
use Illuminate\Http\Request;

class ConfiguracionesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $configuraciones = Configuracion::all();
        return view('admin/configuracion_lista', compact('configuraciones'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $clases = Clase::where('elementos','configuraciones')->get();
        return view('admin/configuracion_form', compact('clases'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //

        $config = new Configuracion();

        $config->nombre = $request->Nombre;
        $config->valor = $request->Valor;
        $config->input = $request->Input;
        $config->clase = $request->Clase;
        $config->orden = $request->Orden;

        $config->save();

        return redirect()->route('configuraciones.show', $config->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $configuracion = Configuracion::find($id);
        return view('admin/configuracion_detalles', compact('configuracion'));
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
    }
}
