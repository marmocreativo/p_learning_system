<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Slider;
use App\Models\Clase;
use App\Models\Temporada;

class SlidersController extends Controller
{
    //
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $id_temporada = $request->input('id_temporada');
        $sliders = Slider::where(['id_temporada' => $id_temporada])->paginate();
        return view('admin/slider_lista', compact('sliders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        //
        $id_temporada = $request->input('id_temporada');
        return view('admin/slider_form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $slider = new Slider();

        $slider->id_cuenta = $request->IdCuenta;
        $slider->id_temporada = $request->IdTemporada;
        $slider->titulo = $request->Titulo;
        $slider->subtitulo = $request->Subtitulo;
        $slider->boton = $request->Boton;
        $slider->link_boton = $request->LinkBoton;
        $slider->imagen = 'default.jpg';
        $slider->imagen_fondo = 'fondo_default.jpg';
        $slider->estado = $request->Estado;
        $slider->orden = 0;

        $slider->save();

        return redirect()->route('sliders', ['id_temporada'=>$request->IdTemporada]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $slider = Slider::find($id);
        return view('admin/sliders_detalles', compact('slider'));

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $slider = Slider::find($id);
        return view('admin/slider_form_actualizar', compact('slider'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $slider = Publicacion::find($id);

        $slider->id_cuenta = $request->IdCuenta;
        $slider->id_temporada = $request->IdTemporada;
        $slider->titulo = $request->Titulo;
        $slider->subtitulo = $request->Subtitulo;
        $slider->boton = $request->Boton;
        $slider->link_boton = $request->LinkBoton;
        $slider->imagen = 'default.jpg';
        $slider->imagen_fondo = 'fondo_default.jpg';
        $slider->estado = $request->Estado;

        $slider->save();

        return redirect()->route('sliders.show', $slider->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $slider = Slider::find($id);
        $slider->delete();
        return redirect()->route('sliders', ['id_temporada' => $slider->id_temporada]);
    }

    /**
     * Funciones API
     */
    public function lista_api (Request $request)
    {
        //
        $id_temporada = $request->input('id_temporada');
        $sliders = Slider::where('id_temporada', $id_temporada)->get();
        return response()->json($sliders);
    }

}
