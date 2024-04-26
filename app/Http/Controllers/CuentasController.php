<?php

namespace App\Http\Controllers;
use App\Models\Cuenta;
use App\Models\Temporada;

use Illuminate\Http\Request;

class CuentasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $cuentas = Cuenta::paginate();
        return view('admin/cuenta_lista', compact('cuentas'));
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
        $cuenta = new Cuenta();

        $cuenta->nombre = $request->Nombre;
        $cuenta->sesiones = $request->Sesiones;
        $cuenta->trivias = $request->Trivias;
        $cuenta->jackpots = $request->Jackpots;
        $cuenta->canjeo_puntos = $request->CanjeoPuntos;
        $cuenta->temporada_actual = null;
        $cuenta->estado = $request->Estado;


        $cuenta->save();

        return redirect()->route('cuentas.show', $cuenta->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $cuenta = Cuenta::find($id);
        return view('admin/cuenta_detalles', compact('cuenta'));

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $cuenta = Cuenta::find($id);
        $temporadas = Temporada::where('id_cuenta', $id)->get();
        return view('admin/cuenta_form_actualizar', compact('cuenta','temporadas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $cuenta = Cuenta::find($id);

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
        $cuenta = Cuenta::find($id);
        $cuenta->delete();
        return redirect()->route('cuentas');
    }
}
