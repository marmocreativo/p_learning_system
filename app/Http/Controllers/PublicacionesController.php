<?php

namespace App\Http\Controllers;
use App\Models\Publicacion;
use App\Models\Clase;

use Illuminate\Http\Request;

class PublicacionesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $id_temporada = $request->input('id_temporada');
        $clase = $request->input('clase');
        $publicaciones = Publicacion::where(['id_temporada' => $id_temporada, 'clase' => $clase])->paginate();
        return view('admin/publicacion_lista', compact('publicaciones'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $clases = Clase::where('elementos','publicaciones')->get();
        return view('admin/publicacion_form', compact('clases'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $publicacion = new Publicacion();

        $publicacion->id_cuenta = $request->IdCuenta;
        $publicacion->id_temporada = $request->IdTemporada;
        $publicacion->titulo = $request->Titulo;
        $publicacion->url = $request->Url;
        $publicacion->descripcion = $request->Descripcion;
        $publicacion->contenido = $request->Contenido;
        $publicacion->keywords = $request->Keywords;
        $publicacion->imagen = 'default.jpg';
        $publicacion->imagen_fondo = 'fondo_default.jpg';
        $publicacion->fecha_publicacion = date('Y-m-d H:i:s', strtotime($request->FechaPublicacion.' '.$request->HoraPublicacion));
        $publicacion->fecha_vigencia = date('Y-m-d H:i:s', strtotime($request->FechaPublicacion.' '.$request->HoraPublicacion));
        $publicacion->clase = $request->Clase;
        $publicacion->destacar = $request->Destacar;
        $publicacion->estado = $request->Estado;
        $publicacion->orden = 0;

        $publicacion->save();

        return redirect()->route('publicaciones.show', $publicacion->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $publicacion = Publicacion::find($id);
        return view('admin/publicacion_detalles', compact('publicacion'));

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $publicacion = Publicacion::find($id);
        return view('admin/publicacion_form_actualizar', compact('publicacion'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $publicacion = Publicacion::find($id);

        $publicacion->titulo = $request->Titulo;
        $publicacion->url = $request->Url;
        $publicacion->descripcion = $request->Descripcion;
        $publicacion->contenido = $request->Contenido;
        $publicacion->keywords = $request->Keywords;
        $publicacion->imagen = 'default.jpg';
        $publicacion->imagen_fondo = 'fondo_default.jpg';
        $publicacion->fecha_publicacion = date('Y-m-d H:i:s', strtotime($request->FechaPublicacion.' '.$request->HoraPublicacion));
        $publicacion->fecha_vigencia = date('Y-m-d H:i:s', strtotime($request->FechaPublicacion.' '.$request->HoraPublicacion));
        $publicacion->clase = $request->Clase;
        $publicacion->destacar = $request->Destacar;
        $publicacion->estado = $request->Estado;
        $publicacion->orden = 0;

        $publicacion->save();

        return redirect()->route('publicaciones.show', $publicacion->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $publicacion = Publicacion::find($id);
        $publicacion->delete();
        return redirect()->route('publicaciones');
    }

    /**
     * Funciones API
     */
    public function lista_api (Request $request)
    {
        //
        $id_temporada = $request->input('id_temporada');
        $clase = $request->input('clase');
        $publicaciones = Publicacion::where('id_temporada', $id_temporada)->where('clase', $id_temporada)->get();
        return response()->json($publicaciones);
    }
    public function datos_publicacion_api(Request $request)
    {
        //
        $publicacion = Publicacion::find($request->input('id'));
        return response()->json($publicacion);
    }
}
