<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\Cuenta;
use App\Models\Clase;
use App\Models\Temporada;
use App\Models\UsuariosSuscripciones;
use App\Models\Logro;
use App\Models\LogroParticipacion;
use App\Models\LogroAnexo;
use App\Models\User;
use App\Models\Distribuidor;
use Illuminate\Support\Facades\DB;

class LogrosController extends Controller
{
    //
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        //
        $id_temporada = $request->input('id_temporada');
        $logros = Logro::where('id_temporada', $id_temporada)->paginate();
        return view('admin/logro_lista', compact('logros'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        //
        $temporada = Temporada::find($request->input('id_temporada'));
        return view('admin/logro_form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
         //
         $logro = new Logro();

         // Validar la solicitud
       $request->validate([
        'Imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Ajusta las reglas de validación según tus necesidades
        'ImagenFondo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Ajusta las reglas de validación según tus necesidades
        ]);

        if ($request->hasFile('Imagen')) {
            $imagen = $request->file('Imagen');
            $nombreImagen = 'logro_'.time().'.'.$imagen->extension();
            $imagen->move(public_path('img/publicaciones'), $nombreImagen);
        }else{
            $nombreImagen = 'default.jpg';
        }
        if ($request->hasFile('ImagenFondo')) {
            $imagen_fondo = $request->file('ImagenFondo');
            $nombreImagenFondo = 'fondo_logro_'.time().'.'.$imagen_fondo->extension();
            $imagen_fondo->move(public_path('img/publicaciones'), $nombreImagenFondo);
        }else{
            $nombreImagenFondo = 'default_fondo.jpg';
        }

         $logro->id_temporada = $request->IdTemporada;
         $logro->nombre = $request->Nombre;
         $logro->instrucciones = $request->Instrucciones;
         $logro->contenido = $request->Contenido;
         $logro->premio = $request->Premio;
         $logro->nivel_a = $request->NivelA;
         $logro->nivel_b = $request->NivelB;
         $logro->nivel_c = $request->NivelC;
         $logro->nivel_especial = $request->NivelEspecial;
         $logro->nivel_usuario = $request->NivelUsuario;
         $logro->imagen = $nombreImagen;
         $logro->imagen_fondo = $nombreImagenFondo;
         $logro->fecha_inicio = date('Y-m-d H:i:s', strtotime($request->FechaInicio.' '.$request->HoraInicio));
         $logro->fecha_vigente = date('Y-m-d H:i:s', strtotime($request->FechaVigente.' '.$request->HoraVigente));
 
         $logro->save();
 
         return redirect()->route('logros.show', $logro->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $logro = Logro::find($id);
        $participaciones = LogroParticipacion::where('id_logro', $id)->get();
        
        return view('admin/logro_detalles', compact('logro', 'participaciones'));
    }

    public function participacion(Request $request)
    {
        //
        $logro = Logro::find($request->input('id_logro'));
        $participacion = LogroParticipacion::find($request->input('id_participacion'));
        $anexos = LogroAnexo::where('id_participacion',$request->input('id_participacion'))->get();
        
        
        return view('admin/logro_participacion', compact('logro', 'participacion', 'anexos'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $logro = Logro::find($id);
        return view('admin/logro_form_actualizar', compact('logro'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $logro = Logro::find($id);

        $request->validate([
            'Imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Ajusta las reglas de validación según tus necesidades
            'ImagenFondo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Ajusta las reglas de validación según tus necesidades
            ]);

        if ($request->hasFile('Imagen')) {
            $imagen = $request->file('Imagen');
            $nombreImagen = 'logro'.time().'.'.$imagen->extension();
            $imagen->move(base_path('../public_html/plsystem/img/publicaciones'), $nombreImagen);
        }else{
            $nombreImagen = $logro->imagen;
        }
        if ($request->hasFile('ImagenFondo')) {
            $imagen_fondo = $request->file('ImagenFondo');
            $nombreImagenFondo = 'fondo_logro_'.time().'.'.$imagen_fondo->extension();
            $imagen_fondo->move(base_path('../public_html/plsystem/img/publicaciones'), $nombreImagenFondo);
        }else{
            $nombreImagenFondo = $logro->imagen_fondo;
        }

        $logro->id_temporada = $request->IdTemporada;
        $logro->nombre = $request->Nombre;
        $logro->instrucciones = $request->Instrucciones;
        $logro->premio = $request->Premio;
        $logro->contenido = $request->Contenido;
        $logro->nivel_a = $request->NivelA;
        $logro->nivel_b = $request->NivelB;
        $logro->nivel_c = $request->NivelC;
        $logro->nivel_especial = $request->NivelEspecial;
        $logro->nivel_usuario = $request->NivelUsuario;
        $logro->imagen = $nombreImagen;
        $logro->imagen_fondo = $nombreImagenFondo;
        $logro->fecha_inicio = date('Y-m-d H:i:s', strtotime($request->FechaInicio.' '.$request->HoraInicio));
        $logro->fecha_vigente = date('Y-m-d H:i:s', strtotime($request->FechaVigente.' '.$request->HoraVigente));
 
         $logro->save();
 
         return redirect()->route('logros.show', $logro->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $logro = Logro::find($id);
        $id_temporada = $logro->id_temporada;
        // Buscar y eliminar registros relacionados en otras tablas
        LogroAnexo::where('id_logro', $id)->delete();
        LogroParticipacion::where('id_logro', $id)->delete();


        $logro->delete();
        return redirect()->route('logros', ['id_temporada'=>$id_temporada]);
    }

    public function destroy_participacion(string $id)
    {
        //
        $participacion = LogroParticipacion::find($id);
        $id_temporada = $participacion->id_temporada;
        // Buscar y eliminar registros relacionados en otras tablas
        LogroAnexo::where('id_participacion', $participacion->$id)->delete();


        $logro->delete();
        return redirect()->route('logros', ['id_temporada'=>$id_temporada]);
    }

    public function lista_logros_api(Request $request)
    {
        $id_cuenta = $request->input('id_cuenta');
        $cuenta = Cuenta::find($id_cuenta);
        $id_temporada = $cuenta->temporada_actual;
        $id_usuario = $request->input('id_usuario');
        $logros = Logro::where('id_temporada', $id_temporada)->get();
        $participaciones = LogroParticipacion::where('id_usuario', $id_usuario)->get();
        $completo = [
            'logros' => $logros,
            'participaciones' => $participaciones
        ];
        return response()->json($completo);
    }

    public function detalles_logro_api (Request $request)
    {
        $id_cuenta = $request->input('id_cuenta');
        $cuenta = Cuenta::find($id_cuenta);
        $id_temporada = $cuenta->temporada_actual;
        $id_usuario = $request->input('id_usuario');
        $logro = Logro::find($request->input('id'));
        $participaciones = LogroParticipacion::where('id_logro', $id_usuario)->get();

        $completo = [
            'logro' => $logro,
            'participaciones' => $participaciones
        ];

        return response()->json($completo);
    }

    public function participar_logro_api (Request $request)
    {
        $id_cuenta = $request->input('id_cuenta');
        $cuenta = Cuenta::find($id_cuenta);
        $id_temporada = $cuenta->temporada_actual;
        $id_usuario = $request->input('id_usuario');
        $id_logro = $request->input('id_logro');
        $suscripcion = UsuariosSuscripciones::where('id_temporada', $id_temporada)->where('id_usuario', $id_usuario)->first();
        $id_distribuidor = $suscripcion->id_distribuidor;
        $logro = Logro::find($id_logro);

        $participacion = new LogroParticipacion();
        $participacion->id_logro = $id_logro;
        $participacion->id_temporada = $id_temporada;
        $participacion->id_distribuidor = $id_distribuidor;
        $participacion->id_usuario = $id_usuario;
        $participacion->estado = 'participante';
        $participacion->fecha_registro = date('Y-m-d H:i:s');

        $participacion->save();

        return 'guardado';
    }
}
