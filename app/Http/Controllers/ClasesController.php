<?php

namespace App\Http\Controllers;

use App\Models\Clase;
use Illuminate\Http\Request;

class ClasesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $clases = Clase::paginate();
        return view('admin/clase_lista', compact('clases'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('admin/clase_form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $clase = new Clase();

        $clase->nombre_sistema = $request->NombreSistema;
        $clase->nombre_singular = $request->NombreSingular;
        $clase->nombre_plural = $request->NombrePlural;
        $clase->elementos = $request->Elementos;

        $clase->save();

        return redirect()->route('clases.show', $clase->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $clase = Clase::find($id);
        return view('admin/clase_detalles', compact('clase'));

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $clase = Clase::find($id);
        return view('admin/clase_form_actualizar', compact('clase'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $clase = Clase::find($id);

        $clase->nombre_sistema = $request->NombreSistema;
        $clase->nombre_singular = $request->NombreSingular;
        $clase->nombre_plural = $request->NombrePlural;
        $clase->elementos = $request->Elementos;

        $clase->save();

        return redirect()->route('clases.show', $clase->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $clase = Clase::find($id);
        $clase->delete();
        return redirect()->route('clases');
    }
}
