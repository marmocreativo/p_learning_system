<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notificacion;
use App\Models\NotificacionUsuario;
use App\Models\Temporada;
use App\Models\Publicacion;
use App\Models\Clase;

class NotificacionesController extends Controller
{
 //
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $id_temporada = $request->input('id_temporada');
        $notificaciones = Notificacion::where(['id_temporada' => $id_temporada])->paginate();
        return view('admin/notificacion_lista', compact('notificaciones'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        //
        $id_temporada = $request->input('id_temporada');
        $publicaciones = Publicacion::where(['id_temporada' => $id_temporada, 'clase' => 'pagina'])->get();
        return view('admin/notificacion_form', compact('publicaciones'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $notificacion = new Notificacion();

        $notificacion->id_cuenta = $request->IdCuenta;
        $notificacion->id_temporada = $request->IdTemporada;
        $notificacion->titulo = $request->Titulo;
        $notificacion->contenido = $request->Contenido;
        $notificacion->mostrar_en = $request->MostrarEn;
        $notificacion->mostrar_en_id = $request->MostrarEnId;
        $notificacion->tipo_mensaje = $request->TipoMensaje;
        $notificacion->permanencia = $request->Permanencia;
        $notificacion->condicion = $request->Condicion;
        $notificacion->id_publicacion_mostrar = $request->IdPublicacionMostrar;
        $notificacion->tabla_revisar = $request->TablaRevisar;
        $notificacion->columna_revisar = $request->ColumnaRevisar;
        $notificacion->fecha_publicacion = $request->FechaPublicacion;

        $notificacion->save();

        return redirect()->route('notificaciones', ['id_temporada'=>$request->IdTemporada]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $notificacion = Notificacion::find($id);
        return view('admin/notificacion_detalles', compact('notificacion'));

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $notificacion = Notificacion::find($id);
        return view('admin/notificacion_form_actualizar', compact('notificacion'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $notificacion = Notificacion::find($id);

        $notificacion->id_cuenta = $request->IdCuenta;
        $notificacion->id_temporada = $request->IdTemporada;
        $notificacion->titulo = $request->Titulo;
        $notificacion->contenido = $request->Contenido;
        $notificacion->mostrar_en = $request->MostrarEn;
        $notificacion->mostrar_en_id = $request->MostrarEnId;
        $notificacion->tipo_mensaje = $request->TipoMensaje;
        $notificacion->permanencia = $request->Permanencia;
        $notificacion->condicion = $request->Condicion;
        $notificacion->id_publicacion_mostrar = $request->IdPublicacionMostrar;
        $notificacion->tabla_revisar = $request->TablaRevisar;
        $notificacion->columna_revisar = $request->ColumnaRevisar;
        $notificacion->fecha_publicacion = $request->FechaPublicacion;

        $notificacion->save();

        return redirect()->route('notificaciones.show', $notificacion->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $notificacion = Notificacion::find($id);
        $notificacion->delete();
        return redirect()->route('notificaciones', ['id_temporada' => $notificacion->id_temporada]);
    }
}
