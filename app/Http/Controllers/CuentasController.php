<?php

namespace App\Http\Controllers;
use App\Models\Cuentas;

use Illuminate\Http\Request;

class CuentasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $cuentas = Cuentas::paginate();
        return view('admin/cuenta_lista', compact('clases'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('admin/cuenta_form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $cuenta = new Cuentas();

        $cuenta->nombre = $request->Nombre;
        $cuenta->sesiones = $request->Sesiones;
        $cuenta->trivias = $request->Trivias;
        $cuenta->jackpots = $request->Jackpots;
        $cuenta->canjeo_puntos = $request->CanjeoPuntos;
        $cuenta->temporada_actual = $request->TemporadaActual;
        $cuenta->estado = $request->Estado;


        $cuenta->save();

        return redirect()->route('cuenta.show', $cuenta->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $cuenta = Cuentas::find($id);
        return view('admin/cuenta_detalles', compact('cuenta'));

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $cuenta = Cuenta::find($id);
        return view('admin/cuenta_form_actualizar', compact('cuenta'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $cuenta = Cuentas::find($id);

        $cuenta->nombre = $request->Nombre;
        $cuenta->sesiones = $request->Sesiones;
        $cuenta->trivias = $request->Trivias;
        $cuenta->jackpots = $request->Jackpots;
        $cuenta->canjeo_puntos = $request->CanjeoPuntos;
        $cuenta->temporada_actual = $request->TemporadaActual;
        $cuenta->estado = $request->Estado;

        $cuenta->save();

        return redirect()->route('cuentas.show', $cuenta->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $cuenta = Cuentas::find($id);
        $cuenta->delete();
        return redirect()->route('cuentas');
    }
}
