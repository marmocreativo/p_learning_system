<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UsuariosSuscripciones;
use App\Models\Temporada;
use App\Models\Clase;
use App\Models\Distribuidor;
use App\Models\DistribuidorSuscripciones;
use App\Models\SesionVis;
use App\Models\EvaluacionesRespuestas;
use App\Models\TriviaRes;
use App\Models\JackpotIntentos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UsuariosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $usuarios = User::paginate();
        return view('admin/usuario_lista', compact('usuarios'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $clases = Clase::where('elementos','usuarios')->get();
        return view('admin/usuario_form', compact('clases'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        
        $usuario = new User();

        $usuario->legacy_id = uniqid('',true);
        $usuario->nombre = $request->Nombre;
        $usuario->apellidos = $request->Apellidos;
        $usuario->email = $request->Email;
        $usuario->telefono = $request->Telefono;
        $usuario->whatsapp = $request->Whatsapp;
        $usuario->fecha_nacimiento = $request->FechaNacimiento;
        $usuario->password = Hash::make($request->Password);
        $usuario->lista_correo = $request->ListaCorreo;
        $usuario->imagen = 'default.jpg';
        $usuario->clase = $request->Clase;
        $usuario->estado = $request->Estado;

        $usuario->save();

        return redirect()->route('admin_usuarios.show', $usuario->id);
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        
        $usuario = User::find($id);
        return view('admin/usuario_detalles', compact('usuario'));
        

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $clases = Clase::where('elementos','usuarios')->get();
        $usuario = User::find($id);
        return view('admin/usuario_form_actualizar')->with(compact('clases','usuario'));
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        
        $usuario = User::find($id);

        $usuario->legacy_id = $request->LegacyId;
        $usuario->nombre = $request->Nombre;
        $usuario->apellidos = $request->Apellidos;
        $usuario->email = $request->Email;
        $usuario->telefono = $request->Telefono;
        $usuario->whatsapp = $request->Whatsapp;
        $usuario->fecha_nacimiento = $request->FechaNacimiento;
        $usuario->lista_correo = $request->ListaCorreo;
        $usuario->clase = $request->Clase;
        $usuario->estado = $request->Estado;

        $usuario->save();

        return redirect()->route('admin_usuarios.show', $usuario->id);
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        
        $usuario = User::find($id);
        $usuario->delete();
        return redirect()->route('admin_usuarios');
        
    }

    /**
     * Usuarios por temporada
     */
    public function usuarios_suscritos (Request $request)
    {
        //
        $id_temporada = $request->input('id_temporada');
        $suscripciones = DB::table('usuarios')
            ->join('usuarios_suscripciones', 'usuarios.id', '=', 'usuarios_suscripciones.id_usuario')
            ->where('usuarios_suscripciones.id_temporada', '=', $id_temporada)
            ->select('usuarios.*', 'usuarios_suscripciones.*')
            ->get();
        //$usuarios = UsuariosSuscripciones::where('id_temporada', $id_temporada)->paginate();
        return view('admin/usuario_lista_suscripciones', compact('suscripciones'));
    }

    public function suscripcion(Request $request)
    {
        //
        $id_temporada = $request->input('id_temporada');
        $temporada = Temporada::find($id_temporada);
        $clases = Clase::where('elementos','usuarios')->get();
        $distribuidores = DB::table('distribuidores')
            ->join('distribuidores_suscripciones', 'distribuidores.id', '=', 'distribuidores_suscripciones.id_distribuidor')
            ->where('distribuidores_suscripciones.id_temporada', '=', $id_temporada)
            ->select('distribuidores.*', 'distribuidores_suscripciones.*')
            ->get();
        return view('admin/usuario_form_suscripcion', compact('clases', 'temporada', 'distribuidores'));
    }

    public function suscribir(Request $request)
    {
            // Verificar si el usuario ya existe
        $usuario = User::where('email', $request->Email)->first();
        

        if (!$usuario) {
            $usuario = new User();
            
            $usuario->legacy_id = uniqid('',true);
            $usuario->nombre = $request->Nombre;
            $usuario->apellidos = $request->Apellidos;
            $usuario->email = $request->Email;
            $usuario->telefono = $request->Telefono;
            $usuario->whatsapp = $request->Whatsapp;
            $usuario->fecha_nacimiento = $request->FechaNacimiento;
            $usuario->password = Hash::make($request->Password);
            $usuario->lista_correo = $request->ListaCorreo;
            $usuario->imagen = 'default.jpg';
            $usuario->clase = $request->Clase;
            $usuario->estado = $request->Estado;

            $usuario->save();
        }


        $suscripcion = UsuariosSuscripciones::where('id_usuario', $usuario->id)->where('id_temporada', $request->IdTemporada)->first();
        if (!$suscripcion) {
            $suscripcion = new UsuariosSuscripciones();
            $suscripcion->id_usuario = $usuario->id;
            $suscripcion->id_cuenta = $request->IdCuenta;
            $suscripcion->id_temporada = $request->IdTemporada;
            $suscripcion->id_distribuidor = $request->IdDistribuidor;
            $suscripcion->confirmacion_puntos = 'pendiente';
            $suscripcion->funcion = 'usuario';
            $suscripcion->save();
        }
        
        return redirect()->route('admin_usuarios_suscritos', ['id_temporada'=>$request->IdTemporada]);
        
    }

    public function cambiar_a_lider(Request $request)
    {

        $suscripcion = UsuariosSuscripciones::find($request->id);

        if ($suscripcion) {
            $suscripcion->funcion = 'lider';
            $suscripcion->save();
        }
        
        return redirect()->back();

        
    }

    public function cambiar_a_usuario(Request $request)
    {

        $suscripcion = UsuariosSuscripciones::find($request->id);
        if ($suscripcion) {
            $suscripcion->funcion = 'usuario';
            $suscripcion->save();
        }
        
        return redirect()->back();
        
    }

    public function desuscribir(Request $request, string $id)
    {
        //
        $id_temporada = $request->input('id_temporada');
        $suscripcion = UsuariosSuscripciones::find($id);
        $suscripcion->delete();
        return redirect()->route('admin_usuarios_suscritos', ['id_temporada'=>$request->IdTemporada]);
        
    }

    public function usuarios_suscritos_api (Request $request)
    {
        //
        $id_temporada = $request->input('id_temporada');
        $suscripciones = DB::table('usuarios')
            ->join('usuarios_suscripciones', 'usuarios.id', '=', 'usuarios_suscripciones.id_usuario')
            ->where('usuarios_suscripciones.id_temporada', '=', $id_temporada)
            ->select('usuarios.*', 'usuarios_suscripciones.*')
            ->get();
        //$usuarios = UsuariosSuscripciones::where('id_temporada', $id_temporada)->paginate();
        return response()->json($suscripciones);
    }
    public function puntaje_usuario_api (Request $request)
    {
        //
        $id_temporada = $request->input('id_temporada');
        $id_usuario = $request->input('id_usuario');
        $visualizaciones = SesionVis::where('id_usuario',$id_usuario)->pluck('puntaje')->sum();
        $evaluaciones = EvaluacionesRespuestas::where('id_usuario',$id_usuario)->pluck('puntaje')->sum();
        $trivia = TriviaRes::where('id_usuario',$id_usuario)->pluck('puntaje')->sum();
        $jackpots = JackpotIntentos::where('id_usuario',$id_usuario)->pluck('puntaje')->sum();

        $puntajes = [
            'visualizaciones' =>$visualizaciones,
            'evaluaciones' =>$evaluaciones,
            'trivia' =>$trivia,
            'jackpots' =>$jackpots,
        ];
        return response()->json($puntajes);
    }
}
