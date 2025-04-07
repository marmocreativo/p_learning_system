<?php

namespace App\Http\Controllers;
use App\Models\Cuenta;
use App\Models\Temporada;
use App\Models\Publicacion;
use App\Models\Cintillo;
use App\Models\Popup;

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

        $request->validate([
            'Fondo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Ajusta las reglas de validación según tus necesidades
            'ImagenVideo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Ajusta las reglas de validación según tus necesidades
            'Logotipo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Ajusta las reglas de validación según tus necesidades
        ]);

        if ($request->hasFile('Logotipo')) {
            $logotipo = $request->file('Logotipo');
            $nombreLogotipo = 'fondo'.time().'.'.$fondo->extension();
            $logotipo->move(base_path('../public_html/system.panduitlatam.com/img/publicaciones'), $nombreLogotipo);
        }else{
            $nombreLogotipo = 'default.png';
        }

        if ($request->hasFile('Fondo')) {
            $fondo = $request->file('Fondo');
            $nombreFondo = 'fondo'.time().'.'.$fondo->extension();
            $fondo->move(base_path('../public_html/system.panduitlatam.com/img/publicaciones'), $nombreFondo);
        }else{
            $nombreFondo = 'default.jpg';
        }

        if ($request->hasFile('ImagenVideo')) {
            $imagenVideo = $request->file('ImagenVideo');
            $nombreImagenVideo = 'fondo'.time().'.'.$imagenVideo->extension();
            $imagenVideo->move(base_path('../public_html/system.panduitlatam.com/img/publicaciones'), $nombreImagenVideo);
        }else{
            $nombreImagenVideo = 'default.jpg';
        }

        $cuenta->nombre = $request->Nombre;
        $cuenta->sesiones = $request->Sesiones;
        $cuenta->trivias = $request->Trivias;
        $cuenta->jackpots = $request->Jackpots;
        $cuenta->canjeo_puntos = $request->CanjeoPuntos;
        $cuenta->bono_login = $request->BonoLogin;
        $cuenta->bono_login_cantidad = $request->BonoLoginCantidad;
        $cuenta->logotipo = $nombreLogotipo;
        $cuenta->fondo = $nombreFondo;
        $cuenta->imagen_video = $nombreImagenVideo;
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

        $request->validate([
            'Fondo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Ajusta las reglas de validación según tus necesidades
            'ImagenVideo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Ajusta las reglas de validación según tus necesidades
            'Logotipo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Ajusta las reglas de validación según tus necesidades
        ]);

        if ($request->hasFile('Logotipo')) {
            $logotipo = $request->file('Logotipo');
            $nombreLogotipo = 'logo'.time().'.'.$logotipo->extension();
            $logotipo->move(base_path('../public_html/system.panduitlatam.com/img/publicaciones'), $nombreLogotipo);
        }else{
            $nombreLogotipo = $cuenta->logotipo;
        }

        if ($request->hasFile('Fondo')) {
            $fondo = $request->file('Fondo');
            $nombreFondo = 'fondo'.time().'.'.$fondo->extension();
            $fondo->move(base_path('../public_html/system.panduitlatam.com/img/publicaciones'), $nombreFondo);
        }else{
            $nombreFondo = $cuenta->fondo;
        }

        if ($request->hasFile('ImagenVideo')) {
            $imagenVideo = $request->file('ImagenVideo');
            $nombreImagenVideo = 'fondo'.time().'.'.$imagenVideo->extension();
            $imagenVideo->move(base_path('../public_html/system.panduitlatam.com/img/publicaciones'), $nombreImagenVideo);
        }else{
            $nombreImagenVideo = $cuenta->imagen_video;
        }

        $cuenta->nombre = $request->Nombre;
        $cuenta->sesiones = $request->Sesiones;
        $cuenta->trivias = $request->Trivias;
        $cuenta->jackpots = $request->Jackpots;
        $cuenta->canjeo_puntos = $request->CanjeoPuntos;
        $cuenta->bono_login = $request->BonoLogin;
        $cuenta->bono_login_cantidad = $request->BonoLoginCantidad;
        $cuenta->temporada_actual = $request->TemporadaActual;
        $cuenta->badge = $request->Badge;
        $cuenta->titulo = $request->Titulo;
        $cuenta->titulo_resaltado = $request->TituloResaltado;
        $cuenta->boton_texto = $request->BotonTexto;
        $cuenta->boton_enlace = $request->BotonEnlace;
        $cuenta->logotipo = $nombreLogotipo;
        $cuenta->fondo = $nombreFondo;
        $cuenta->imagen_video = $nombreImagenVideo;
        $cuenta->link_video = $request->LinkVideo;
        $cuenta->fondo_menu = $request->FondoMenu;
        $cuenta->texto_menu = $request->TextoMenu;
        $cuenta->color_realse = $request->ColorRealse;

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

    /**
     * API 2025
     */

    public function update_2025(Request $request)
    {
         // Obtener solo la fecha `updated_at` de la cuenta
         $cuenta = Cuenta::where('id', $request->input('idCuenta'))
         ->select('updated_at', 'temporada_actual') // Selecciona las columnas necesarias
         ->first(); // Obtiene el primer registro que coincide con la condición
        
        $cuentaUpdatedAt = $cuenta->updated_at;
        $temporadaActual = $cuenta->temporada_actual;

        // Obtener solo la fecha `updated_at` de la temporada
        $temporadaUpdatedAt = Temporada::where('id', $temporadaActual)
        ->value('updated_at'); // Solo obtiene la columna `updated_at`
        
        $respuesta = [
            'cuenta' => $cuentaUpdatedAt ? $cuentaUpdatedAt : null,
            'temporada' => $temporadaUpdatedAt ? $temporadaUpdatedAt : null,
        ];

         return response()->json($respuesta);
    }

    public function context_2025(Request $request)
    {
        $cuenta = Cuenta::where('id', $request->input('idCuenta'))->first();
        $temporada = Temporada::where('id', $cuenta->temporada_actual)->first();
        $aviso_privacidad = Publicacion::where('id_temporada', $cuenta->temporada_actual)->where('funcion', 'aviso')->first();
        $terminos_y_condiciones = Publicacion::where('id_temporada', $cuenta->temporada_actual)->where('funcion', 'terminos')->first();
        $cintillo = Cintillo::where('id_temporada', $cuenta->temporada_actual)
                    ->where('fecha_inicio', '<=', now()) // Fecha de inicio pasada o hoy
                    ->where('fecha_final', '>=', now()) // Fecha final aún vigente
                    ->orderBy('fecha_inicio', 'desc') // Ordenado por la fecha de inicio más reciente
                    ->first();
        $popup = Popup::where('id_temporada', $cuenta->temporada_actual)
                ->where('fecha_inicio', '<=', now()) // Fecha de inicio pasada o hoy
                ->where('fecha_final', '>=', now()) // Fecha final aún vigente
                ->orderBy('fecha_inicio', 'desc') // Ordenado por la fecha de inicio más reciente
                ->first();

            $noticias = Publicacion::where('id_temporada', $cuenta->temporada_actual)
                ->where('clase', 'noticia')
                ->orderBy('fecha_publicacion', 'desc')
                ->limit(16)
                ->get();
        
        $respuesta = [
            'cuenta' => $cuenta ? $cuenta : null,
            'temporada' => $temporada ? $temporada : null,
            'aviso_privacidad' => $aviso_privacidad ? $aviso_privacidad : null,
            'terminos_y_condiciones' => $terminos_y_condiciones ? $terminos_y_condiciones : null,
            'noticias' => $noticias ? $noticias : null,
            'cintillo' => $cintillo ? $cintillo : null,
            'popup' => $popup ? $popup : null,
        ];

         return response()->json($respuesta);
    }
}
