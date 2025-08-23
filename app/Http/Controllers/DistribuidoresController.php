<?php

namespace App\Http\Controllers;
use App\Models\Distribuidor;
use App\Models\Sucursal;
use App\Models\DistribuidoresSuscripciones;
use App\Models\Temporada;
use App\Models\Clase;
use App\Models\Cuenta;
use App\Models\User;
use App\Models\UsuariosSuscripciones;

use App\Exports\ReporteDistribuidorActividades;
use App\Exports\ReporteDistribuidorSesiones;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DistribuidoresController extends Controller
{
    //
     /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    $busqueda = $request->input('busqueda');

    $distribuidores = Distribuidor::when($busqueda, function ($query, $busqueda) {
        return $query->where('nombre', 'like', '%' . $busqueda . '%');
    })->paginate(10); // Puedes ajustar el número de resultados por página

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

        $request->validate([
            'Imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Ajusta las reglas de validación según tus necesidades
            'ImagenFondoA' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Ajusta las reglas de validación según tus necesidades
            'ImagenFondoB' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Ajusta las reglas de validación según tus necesidades
        ]);
    
        if ($request->hasFile('Imagen')) {
            $imagen = $request->file('Imagen');
            $nombreImagen = 'sesion_'.time().'.'.$imagen->extension();
            $imagen->move(base_path('../public_html/img/publicaciones'), $nombreImagen);
        }else{
            $nombreImagen = 'distribuidor_default.jpg';
        }
        if ($request->hasFile('ImagenFondoA')) {
            $imagen = $request->file('ImagenFondoA');
            $nombreImagenFondoA = 'sesion_'.time().'.'.$imagen->extension();
            $imagen->move(base_path('../public_html/img/publicaciones'), $nombreImagenFondoA);
        }else{
            $nombreImagenFondoA = 'fondo_distribuidor_default.jpg';
        }
        if ($request->hasFile('ImagenFondoB')) {
            $imagen = $request->file('ImagenFondoB');
            $nombreImagenFondoB = 'sesion_'.time().'.'.$imagen->extension();
            $imagen->move(base_path('../public_html/img/publicaciones'), $nombreImagenFondoB);
        }else{
            $nombreImagenFondoB = 'fondo_distribuidor_default.jpg';
        }

        $distribuidor->nombre = $request->Nombre;
        $distribuidor->pais = $request->Pais;
        $distribuidor->region = $request->Region;
        $distribuidor->nivel = $request->Nivel;
        $distribuidor->estado = $request->Estado;
        $distribuidor->imagen = $request->nombreImagen;
        $distribuidor->imagen_fondo_a = $request->nombreImagenFondoA;
        $distribuidor->imagen_fondo_b = $request->nombreImagenFondoB;


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
        $sucursales = Sucursal::where('id_distribuidor', $id)->get();
        return view('admin/distribuidor_detalles', compact('distribuidor', 'sucursales'));

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

        $request->validate([
            'Imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Ajusta las reglas de validación según tus necesidades
            'ImagenFondoA' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Ajusta las reglas de validación según tus necesidades
            'ImagenFondoB' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Ajusta las reglas de validación según tus necesidades
        ]);

        if ($request->hasFile('Imagen')) {
            $imagen = $request->file('Imagen');
            $nombreImagen = 'distribuidor_'.time().'.'.$imagen->extension();
            $imagen->move(base_path('../public_html/img/publicaciones'), $nombreImagen);
        }else{
            $nombreImagen = $distribuidor->imagen;
        }
        
        if ($request->hasFile('ImagenFondoA')) {
            $imagen = $request->file('ImagenFondoA');
            $nombreImagenFondoA = 'distribuidor_f_a_'.time().'.'.$imagen->extension();
            $imagen->move(base_path('../public_html/img/publicaciones'), $nombreImagenFondoA);
        }else{
            $nombreImagenFondoA = $distribuidor->imagen_fondo_a;
        }
        if ($request->hasFile('ImagenFondoB')) {
            $imagen = $request->file('ImagenFondoB');
            $nombreImagenFondoB = 'distribuidor__f_b'.time().'.'.$imagen->extension();
            $imagen->move(base_path('../public_html/img/publicaciones'), $nombreImagenFondoB);
        }else{
            $nombreImagenFondoB = $distribuidor->imagen_fondo_b;
        }

        // Antes de actualizar necesito revisar todos los usuarios suscritos al distribuidor
        $suscripciones = UsuariosSuscripciones::select('id_usuario')
            ->where('id_distribuidor', $distribuidor->id)
            ->distinct()
            ->get();

        foreach($suscripciones as $suscripcion){
            $usuario = User::find($suscripcion->id_usuario);
            if ($usuario && Hash::check($distribuidor->default_pass, $usuario->password)) {
                $usuario->password = Hash::make($request->DefaultPass);
                $usuario->save();
            }
        }
            
        
        $distribuidor->nombre = $request->Nombre;
        $distribuidor->pais = $request->Pais;
        $distribuidor->region = $request->Region;
        $distribuidor->default_pass = $request->DefaultPass;
        $distribuidor->nivel = $request->Nivel;
        $distribuidor->estado = $request->Estado;
        $distribuidor->imagen = $nombreImagen;
        $distribuidor->imagen_fondo_a = $nombreImagenFondoA;
        $distribuidor->imagen_fondo_b = $nombreImagenFondoB;

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

    /**
     * Funciones por temporada
     */

    public function distribuidores_suscritos(Request $request)
    {
        //
         $id_temporada = $request->input('id_temporada');
        $temporada = Temporada::find($id_temporada);
        $cuenta = Cuenta::find($temporada->id_cuenta);
        $id_cuenta = $temporada->id_cuenta;
        $cuentas = Cuenta::all();
        $color_barra_superior = $cuenta->fondo_menu;
        $logo_cuenta = 'https://system.panduitlatam.com/img/publicaciones/'.$cuenta->logotipo;

        $suscripciones = DB::table('distribuidores')
            ->join('distribuidores_suscripciones', 'distribuidores.id', '=', 'distribuidores_suscripciones.id_distribuidor')
            ->where('distribuidores_suscripciones.id_temporada', '=', $id_temporada)
            ->select('distribuidores.*', 'distribuidores_suscripciones.*')
            ->get();
        //$usuarios = UsuariosSuscripciones::where('id_temporada', $id_temporada)->paginate();
        return view('admin/distribuidor_lista_suscripciones', compact('suscripciones', 'cuenta', 'temporada', 'cuentas',
'color_barra_superior',
'logo_cuenta',));
    }

    public function reporte_usuarios(Request $request)
    {
        //
        // Aquí van los usuarios
    }

    public function suscripcion(Request $request,)
    {
        //
        $id_temporada = $request->input('id_temporada');
        $temporada = Temporada::find($id_temporada);
        $clases = Clase::where('elementos','usuarios')->get();
        return view('admin/distribuidor_form_suscripcion', compact('clases', 'temporada'));
    }

    public function suscribir(Request $request)
    {
            // Verificar si el usuario ya existe
        $distribuidor = Distribuidor::where('nombre', $request->Nombre)->first();
        

        if (!$distribuidor) {
            $distribuidor = new Distribuidor();
            
            $distribuidor->nombre = $request->Nombre;
            $distribuidor->pais = $request->Pais;
            $distribuidor->region = $request->Region;
            $distribuidor->nivel = $request->Nivel;
            $distribuidor->estado = $request->Estado;

            $distribuidor->save();
        }


        $suscripcion = DistribuidoresSuscripciones::where('id_distribuidor', $distribuidor->id)->where('id_temporada', $request->IdTemporada)->first();
        if (!$suscripcion) {
            $suscripcion = new DistribuidoresSuscripciones();
            $suscripcion->id_distribuidor = $distribuidor->id;
            $suscripcion->id_cuenta = $request->IdCuenta;
            $suscripcion->id_temporada = $request->IdTemporada;
            $suscripcion->cantidad_usuarios = 0;
            $suscripcion->nivel = 'completo';
            $suscripcion->save();
        }
        
        return redirect()->route('distribuidores.suscritos', ['id_temporada'=>$request->IdTemporada]);
        
    }

    public function desuscribir(Request $request, string $id)
    {
        //
        $id_temporada = $request->input('id_temporada');
        $suscripcion = UsuariosSuscripciones::find($id);
        $suscripcion->delete();
        return redirect()->route('admin_usuarios_suscritos', ['id_temporada'=>$request->IdTemporada]);
        
    }

    /**
     * Sucursales
     */

     public function crear_sucursal(Request $request)
    {
            // Verificar si el usuario ya existe
        $sucursal = new Sucursal();
        $sucursal->id_distribuidor = $request->IdDistribuidor;
        $sucursal->nombre = $request->Nombre;
        $sucursal->save();
        
        
        return redirect()->route('distribuidores.show', $request->IdDistribuidor);
        
    }

    public function borrar_sucursal(Request $request, string $id)
    {
        //
        $sucursal = Sucursal::find($id);
        $sucursal->delete();
        return redirect()->route('distribuidores.show', $request->IdDistribuidor);
        
    }

    public function reporte_actividades (Request $request)
    {
        $id_cuenta = $request->input('id_cuenta');
        $fecha_inicio = $request->input('fecha_inicio');
        $fecha_final = $request->input('fecha_final');
        $id_distribuidor = $request->input('id_distribuidor');

        // Crear una instancia del exportador con los parámetros requeridos
        $export = new ReporteDistribuidorActividades($request, $id_cuenta, $id_distribuidor, $fecha_inicio, $fecha_final);
        
        // Generar un nombre de archivo único usando timestamp
        $filename = 'reporte_actividades'. time() . '.xlsx';

        // Generar la respuesta de descarga con los encabezados HTTP
        $response = Excel::download($export, $filename);

        // Establecer encabezados para desactivar la caché
        $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', '0');

        // Retornar la respuesta al navegador
        return $response;


    }
    public function reporte_sesiones (Request $request)
    {
        $id_cuenta = $request->input('id_cuenta');
        $id_distribuidor = $request->input('id_distribuidor');

        // Crear una instancia del exportador con los parámetros requeridos
        $export = new ReporteDistribuidorActividades($request, $id_cuenta, $id_distribuidor);
        
        // Generar un nombre de archivo único usando timestamp
        $filename = 'reporte_sesiones'. time() . '.xlsx';

        // Generar la respuesta de descarga con los encabezados HTTP
        $response = Excel::download($export, $filename);

        // Establecer encabezados para desactivar la caché
        $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', '0');

        // Retornar la respuesta al navegador
        return $response;
    } 
}
