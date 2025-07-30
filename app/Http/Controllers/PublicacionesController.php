<?php

namespace App\Http\Controllers;
use App\Models\Temporada;
use App\Models\Publicacion;
use App\Models\Clase;
use App\Models\Cuenta;

use App\Exports\NoticiasExport;
use Maatwebsite\Excel\Facades\Excel;

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
        $temporada = Temporada::find($id_temporada);
        $id_temporada = $request->input('id_temporada');
        $cuenta = Cuenta::where('id', $temporada->id_cuenta)->first();
        $cuentas = Cuenta::all();
        $color_barra_superior = $cuenta->fondo_menu;
        $logo_cuenta = 'https://system.panduitlatam.com/img/publicaciones/'.$cuenta->logotipo;

        $publicaciones = Publicacion::where(['id_temporada' => $id_temporada, 'clase' => $clase])->paginate();
        return view('admin/publicacion_lista', compact('publicaciones',
        'temporada',
        'cuenta',
        'cuentas',
        'color_barra_superior',
        'logo_cuenta',
    ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        //
        $temporada = Temporada::find($request->input('id_temporada'));
        $clases = Clase::where('elementos','publicaciones')->get();
        $cuenta = Cuenta::where('id', $temporada->id_cuenta)->first();
        $cuentas = Cuenta::all();
        $color_barra_superior = $cuenta->fondo_menu;
        $logo_cuenta = 'https://system.panduitlatam.com/img/publicaciones/'.$cuenta->logotipo;
        return view('admin/publicacion_form', compact('clases'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Si la función es "terminos" o "aviso", actualizar publicaciones existentes
        if (in_array($request->Funcion, ['terminos', 'aviso', 'aviso_champions'])) {
            Publicacion::where('id_temporada', $request->IdTemporada)
                ->where('funcion', $request->Funcion)
                ->update(['funcion' => 'normal']);
        }

        $request->validate([
            'Imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Ajusta las reglas de validación según tus necesidades
            'ImagenFondo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Ajusta las reglas de validación según tus necesidades
            ]);

        if ($request->hasFile('Imagen')) {
            $imagen = $request->file('Imagen');
            $nombreImagen = 'publicacion'.time().'.'.$imagen->extension();
            $imagen->move(base_path('../public_html/img/publicaciones'), $nombreImagen);
        }else{
            $nombreImagen = 'default.jpg';
        }
        if ($request->hasFile('ImagenFondo')) {
            $imagen_fondo = $request->file('ImagenFondo');
            $nombreImagenFondo = 'publicacion_fondo'.time().'.'.$imagen_fondo->extension();
            $imagen_fondo->move(base_path('../public_html/img/publicaciones'), $nombreImagenFondo);
        }else{
            $nombreImagenFondo = 'default_fondo.jpg';
        }

        // Crear nueva publicación
        $publicacion = new Publicacion();

        $publicacion->id_cuenta = $request->IdCuenta;
        $publicacion->id_temporada = $request->IdTemporada;
        $publicacion->titulo = $request->Titulo;
        $publicacion->url = $request->Url;
        $publicacion->descripcion = $request->Descripcion;
        $publicacion->contenido = $request->Contenido;
        $publicacion->keywords = $request->Keywords;
        $publicacion->imagen = $nombreImagen;
        $publicacion->imagen_fondo = $nombreImagenFondo;
        $publicacion->fecha_publicacion = date('Y-m-d H:i:s', strtotime($request->FechaPublicacion.' '.$request->HoraPublicacion));
        $publicacion->fecha_vigencia = date('Y-m-d H:i:s', strtotime($request->FechaPublicacion.' '.$request->HoraPublicacion));
        $publicacion->clase = $request->Clase;
        $publicacion->destacar = $request->Destacar;
        $publicacion->funcion = $request->Funcion; // Corregido, antes estaba sobrescrito con Destacar
        $publicacion->estado = $request->Estado;
        $publicacion->btn_carrusel_text = $request->BtnCarruselText;
        $publicacion->btn_carrusel_link = $request->BtnCarruselLink;
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
        $temporada = Temporada::find($publicacion->id_temporada);
        $cuenta = Cuenta::where('id', $temporada->id_cuenta)->first();
        $cuentas = Cuenta::all();
        $color_barra_superior = $cuenta->fondo_menu;
        $logo_cuenta = 'https://system.panduitlatam.com/img/publicaciones/'.$cuenta->logotipo;
        return view('admin/publicacion_detalles', compact('publicacion', 'temporada'));

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $publicacion = Publicacion::find($id);
        $clases = Clase::where('elementos','publicaciones')->get();
        $temporada = Temporada::find($publicacion->id_temporada);
        return view('admin/publicacion_form_actualizar', compact('publicacion', 'clases', 'temporada'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Buscar la publicación existente
        $publicacion = Publicacion::findOrFail($id);

        // Si la función es "terminos" o "aviso", actualizar las publicaciones existentes
        if (in_array($request->Funcion, ['terminos', 'aviso','aviso_champions'])) {
            Publicacion::where('id_temporada', $publicacion->id_temporada)
                ->where('funcion', $request->Funcion)
                ->where('id', '!=', $id) // Excluir la publicación actual
                ->update(['funcion' => 'normal']);
        }

        $request->validate([
            'Imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Ajusta las reglas de validación según tus necesidades
            'ImagenFondo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Ajusta las reglas de validación según tus necesidades
            ]);

        if ($request->hasFile('Imagen')) {
            $imagen = $request->file('Imagen');
            $nombreImagen = 'publicacion'.time().'.'.$imagen->extension();
            $imagen->move(base_path('../public_html/img/publicaciones'), $nombreImagen);
        }else{
            $nombreImagen = $publicacion->imagen;
        }
        if ($request->hasFile('ImagenFondo')) {
            $imagen_fondo = $request->file('ImagenFondo');
            $nombreImagenFondo = 'publicacion_fondo'.time().'.'.$imagen_fondo->extension();
            $imagen_fondo->move(base_path('../public_html/img/publicaciones'), $nombreImagenFondo);
        }else{
            $nombreImagenFondo = $publicacion->imagen_fondo;
        }

        // Actualizar la publicación
        $publicacion->titulo = $request->Titulo;
        $publicacion->url = $request->Url;
        $publicacion->descripcion = $request->Descripcion;
        $publicacion->contenido = $request->Contenido;
        $publicacion->keywords = $request->Keywords;
        $publicacion->imagen = $nombreImagen;
        $publicacion->imagen_fondo = $nombreImagenFondo;
        $publicacion->fecha_publicacion = date('Y-m-d H:i:s', strtotime($request->FechaPublicacion.' '.$request->HoraPublicacion));
        $publicacion->fecha_vigencia = date('Y-m-d H:i:s', strtotime($request->FechaPublicacion.' '.$request->HoraPublicacion));
        $publicacion->clase = $request->Clase;
        $publicacion->destacar = $request->Destacar;
        $publicacion->funcion = $request->Funcion; // Se asegura de que la función se actualice
        $publicacion->estado = $request->Estado;
        $publicacion->btn_carrusel_text = $request->BtnCarruselText;
        $publicacion->btn_carrusel_link = $request->BtnCarruselLink;
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
        $id_temporada =$publicacion->id_temporada; 
        $clase =$publicacion->clase; 
        $publicacion->delete();
        return redirect()->route('publicaciones',['id_temporada'=>$id_temporada, 'clase'=>$clase]);
    }

    public function reporte_clicks(Request $request)
    {
        // Validar los parámetros requeridos
        $request->validate([
            'id_temporada' => 'integer',
        ]);

        $nombreArchivo = 'clicks_en_noticias_' . now()->format('Ymd_His') . '.xlsx';

        return Excel::download(new NoticiasExport($request), $nombreArchivo);
    }

    /**
     * Funciones API
     */
    public function lista_api (Request $request)
    {
        //
        $id_temporada = $request->input('id_temporada');
        $clase = $request->input('clase');
        $publicaciones = Publicacion::where('id_temporada', $id_temporada)->where('clase', $clase)->get();
        return response()->json($publicaciones);
    }
    public function datos_publicacion_api(Request $request)
    {
        //
        $publicacion = Publicacion::find($request->input('id'));
        return response()->json($publicacion);
    }
}
