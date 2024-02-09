<?php

namespace App\Http\Controllers;
use App\Models\Distribuidor;

use Illuminate\Http\Request;

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
}
