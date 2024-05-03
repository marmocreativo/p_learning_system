<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UsuariosSuscripciones;
use App\Models\Temporada;
use App\Models\Clase;
use App\Models\Distribuidor;
use App\Models\DistribuidorSuscripciones;
use App\Models\SesionVis;
use App\Models\SesionEv;
use App\Models\EvaluacionRes;
use App\Models\TriviaGanador;
use App\Models\TriviaRes;
use App\Models\Trivia;
use App\Models\JackpotIntentos;
use App\Models\JackpotRes;
use App\Models\Jackpot;
use App\Models\Cuenta;
use App\Models\Tokens;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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
            ->join('distribuidores', 'usuarios_suscripciones.id_distribuidor', '=', 'distribuidores.id')
            ->where('usuarios_suscripciones.id_temporada', '=', $id_temporada)
            ->select('usuarios.*', 'usuarios_suscripciones.*', 'distribuidores.nombre as nombre_distribuidor')
            ->get();
            $suscriptores_activos = 0;
            $suscriptores_participantes = 0;
            $suscriptores_totales = 0;

        foreach($suscripciones as $suscriptor){
            $activo = false;
            $participante = false;
            $hay_login = Tokens::where('tokenable_id', $suscriptor->id_usuario)->first();
            if($hay_login){ $activo=true; }
            $hay_sesiones = SesionVis::where('id_temporada', $id_temporada)->where('id_usuario', $suscriptor->id_usuario)->first();
            if($hay_sesiones){ $participante=true; }
            $hay_evaluaciones = EvaluacionRes::where('id_temporada', $id_temporada)->where('id_usuario', $suscriptor->id_usuario)->first();
            if($hay_evaluaciones){ $participante=true; }
            $hay_trivias = TriviaRes::where('id_temporada', $id_temporada)->where('id_usuario', $suscriptor->id_usuario)->first();
            if($hay_trivias){ $participante=true; }
            $hay_jackpot = JackpotIntentos::where('id_temporada', $id_temporada)->where('id_usuario', $suscriptor->id_usuario)->first();
            if($hay_jackpot){ $participante=true; }

            if($participante){ $activo=true; }


            if($activo){ $suscriptores_activos++; }
            if($participante){ $suscriptores_participantes++; }
            $suscriptores_totales ++;
        }

        $clases = Clase::where('elementos','usuarios')->get();
        $distribuidores = Distribuidor::all();
        //$usuarios = UsuariosSuscripciones::where('id_temporada', $id_temporada)->paginate();
        return view('admin/usuario_lista_suscripciones', compact('suscripciones', 'suscriptores_totales', 'suscriptores_activos', 'suscriptores_participantes', 'clases', 'distribuidores'));
    }


    public function suscribir_update(Request $request, string $id)
    {
        
        $suscripcion = UsuariosSuscripciones::find($id);
        $id_usuario = $suscripcion->id_usuario;
        $id_temporada = $request->IdTemporada;

        //Actualizo
        $suscripcion->id_distribuidor = $request->IdDistribuidor;
        $suscripcion->funcion = $request->Funcion;
        $suscripcion->save();

        // reasigno el distribuidor en las actividades
        $visualizaciones = SesionVis::where('id_usuario',$id_usuario)->where('id_temporada',$id_temporada)->get();
        foreach($visualizaciones as $visualizacion){
            $visualizacion->id_distribuidor = $request->IdDistribuidor;
            $visualizacion->save();
        }

        $evaluaciones_respuestas = EvaluacionRes::where('id_usuario',$id_usuario)->where('id_temporada',$id_temporada)->get();
        foreach($evaluaciones_respuestas as $respuesta){
            $respuesta->id_distribuidor = $request->IdDistribuidor;
            $respuesta->save();
        }

        $trivias_respuestas = TriviaRes::where('id_usuario',$id_usuario)->where('id_temporada',$id_temporada)->get();
        foreach($trivias_respuestas as $respuesta){
            $respuesta->id_distribuidor = $request->IdDistribuidor;
            $respuesta->save();
        }

        $trivias_ganadores = TriviaGanador::where('id_usuario',$id_usuario)->where('id_temporada',$id_temporada)->get();
        foreach($trivias_ganadores as $ganador){
            $ganador->id_distribuidor = $request->IdDistribuidor;
            $ganador->save();
        }

        $jackpot_respuestas = JackpotRes::where('id_usuario',$id_usuario)->where('id_temporada',$id_temporada)->get();
        foreach($jackpot_respuestas as $respuesta){
            $respuesta->id_distribuidor = $request->IdDistribuidor;
            $respuesta->save();
        }

        $jackpot_intentos = JackpotIntentos::where('id_usuario',$id_usuario)->where('id_temporada',$id_temporada)->get();
        foreach($jackpot_intentos as $intento){
            $intento->id_distribuidor = $request->IdDistribuidor;
            $intento->save();
        }
        
        return redirect()->route('admin_usuarios_suscritos', ['id_temporada'=>$request->IdTemporada]);
        
    }

    public function suscripcion(Request $request)
    {
        //
        $id_temporada = $request->input('id_temporada');
        $temporada = Temporada::find($id_temporada);
        $clases = Clase::where('elementos','usuarios')->get();
        $distribuidores = Distribuidor::all();
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
        return redirect()->route('admin_usuarios_suscritos', ['id_temporada'=>$id_temporada]);
        
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
        $visualizaciones = SesionVis::where('id_usuario',$id_usuario)->where('id_temporada',$id_temporada)->pluck('puntaje')->sum();
        $evaluaciones = EvaluacionRes::where('id_usuario',$id_usuario)->where('id_temporada',$id_temporada)->pluck('puntaje')->sum();
        $trivia = TriviaRes::where('id_usuario',$id_usuario)->where('id_temporada',$id_temporada)->pluck('puntaje')->sum();
        $jackpots = JackpotIntentos::where('id_usuario',$id_usuario)->where('id_temporada',$id_temporada)->pluck('puntaje')->sum();

        $puntajes = [
            'visualizaciones' =>$visualizaciones,
            'evaluaciones' =>$evaluaciones,
            'trivia' =>$trivia,
            'jackpots' =>$jackpots,
        ];
        return response()->json($puntajes);
    }

    public function datos_lider_api (Request $request)
    {
        $id_usuario = $request->input('id_usuario');
        $id_cuenta = $request->input('id_cuenta');
        $cuenta = Cuenta::find($id_cuenta);
        $id_temporada = $cuenta->temporada_actual;
        $usuario = User::find($id_usuario);
        $temporada = Temporada::find($id_temporada);
        $suscripcion = UsuariosSuscripciones::where('id_temporada', $id_temporada)->where('id_usuario', $id_usuario)->first();
        $sesiones = SesionEv::where('id_temporada', $id_temporada)->get();
        $trivias = Trivia::where('id_temporada', $id_temporada)->get();
        $jackpots = Jackpot::where('id_temporada', $id_temporada)->get();
       
        $distribuidor = Distribuidor::find($suscripcion->id_distribuidor);
        $suscriptores = DB::table('usuarios_suscripciones')
            ->join('usuarios', 'usuarios_suscripciones.id_usuario', '=', 'usuarios.id')
            ->where('usuarios_suscripciones.id_temporada', '=', $id_temporada)
            ->where('usuarios_suscripciones.id_distribuidor', '=', $suscripcion->id_distribuidor)
            ->select('usuarios.nombre', 'usuarios.apellidos','usuarios.email', 'usuarios_suscripciones.*')
            ->get();
        $participaciones = array();
        $ids = array();
        $logins = 0;
        $hoy = Carbon::now();
        $inicio = $hoy->copy()->subDays(7);
        $fin = $hoy->copy()->addDays(7);

        foreach($suscriptores as $suscriptor){
            $n_visualizaciones = SesionVis::where('id_usuario', $suscriptor->id_usuario)->count();
            $n_evaluaciones = EvaluacionRes::where('id_usuario', $suscriptor->id_usuario)->distinct('id_usuario')->count();
            $n_trivias = TriviaRes::where('id_usuario', $suscriptor->id_usuario)->groupBy('id_usuario')->count();
            $n_jackpots = TriviaRes::where('id_usuario', $suscriptor->id_usuario)->groupBy('id_usuario')->count();
            $n_extras = 0;
            $participaciones[$suscriptor->id_usuario] = [
                'nombre' => $suscriptor->nombre,
                'apellidos' => $suscriptor->apellidos,
                'visualizaciones' => $n_visualizaciones,
                'evaluaciones' => $n_evaluaciones,
                'trivias' => $n_trivias,
                'jackpots' => $n_jackpots,
                'extras' => $n_extras
            ];
            $ids[] = $suscriptor->id_usuario;
        }
        $activos = 0;
        foreach($participaciones as $participacion){
            if(
                $participacion['visualizaciones']>0||
                $participacion['evaluaciones']>0||
                $participacion['trivias']>0||
                $participacion['jackpots']>0
                ){
                $activos ++;
            }
        } 

        //Bucle de fechas

        
        //Bucle de fechas
        $array_fechas = array();
        $array_visualizaciones = array();
        $array_evaluaciones = array();
        $array_trivias = array();
        $array_jackpots = array();
        for ($date = $inicio; $date->lessThanOrEqualTo($fin); $date->addDay()) {
            // Recorre todas las sesiones
            $conteo_visualizaciones = 0;
            $conteo_evaluaciones = 0;
            $conteo_trivias = 0;
            $conteo_jackpot = 0;
            foreach ($sesiones as $sesion) {
                // Recorre todos los IDs
                foreach ($ids as $id) {
                    // Realiza la consulta
                    $visualizaciones = SesionVis::where('id_sesion', $sesion->id)
                        ->where('id_usuario', $id)
                        ->whereDate('fecha_ultimo_video', $date->format('Y-m-d'))
                        ->count();
                    $evaluaciones = EvaluacionRes::where('id_sesion', $sesion->id)
                        ->where('id_usuario', $id)
                        ->whereDate('fecha_registro', $date->format('Y-m-d'))
                        ->distinct('id_usuario')->count();
                    $conteo_visualizaciones +=$visualizaciones;
                    $conteo_evaluaciones +=$evaluaciones;
                }
                $array_visualizaciones[]= $conteo_visualizaciones;
                $array_evaluaciones[]= $conteo_evaluaciones;
            }
            foreach ($trivias as $trivia) {
                // Recorre todos los IDs
                foreach ($ids as $id) {
                    // Realiza la consulta
                    $respuestas = TriviaRes::where('id_trivia', $trivia->id)
                        ->where('id_usuario', $id)
                        ->whereDate('fecha_registro', $date->format('Y-m-d'))
                        ->distinct('id_usuario')->count();
                    $conteo_trivias += $respuestas;
                }
                $array_trivias[]= $conteo_trivias;
            }

            foreach ($jackpots as $jackpot) {
                // Recorre todos los IDs
                foreach ($ids as $id) {
                    // Realiza la consulta
                    $respuestas = JackpotIntentos::where('id_jackpot', $jackpot->id)
                        ->where('id_usuario', $id)
                        ->whereDate('fecha_registro', $date->format('Y-m-d'))
                        ->distinct('id_usuario')->count();
                    $conteo_jackpot += $respuestas;
                }
                $array_jackpots[]= $conteo_jackpot;
            }
            $array_fechas[] = $date->format('Y-m-d');
        }
        /*
        foreach($sesiones as $sesion){
            $conteo = 0;
            $conteo_eval = 0;
            foreach($participaciones as $id => $participacion){
                $visualizaciones = SesionVis::where('id_sesion', $sesion->id)->where('id_usuario', $id)->count();
                $evaluaciones = EvaluacionRes::where('id_sesion', $sesion->id)->where('id_usuario', $id)->distinct('id_usuario')->count();
                $conteo += $visualizaciones;
                $conteo_eval += $evaluaciones;
            }
            $array_visualizaciones[$sesion->id] = [
                'titulo'=> $sesion->titulo,
                'participaciones'=> $conteo,
            ];
            $array_evaluaciones[$sesion->id] = [
                'titulo'=> $sesion->titulo,
                'participaciones'=> $conteo_eval,
            ];
        }

        foreach($trivias as $trivia){
            $conteo = 0;
            foreach($participaciones as $id => $participacion){
                $respuestas = TriviaRes::where('id_trivia', $trivia->id)->where('id_usuario', $id)->distinct('id_usuario')->count();
                $conteo += $respuestas;
            }
            $array_trivias[$trivia->id] = [
                'titulo'=> $trivia->titulo,
                'participaciones'=> $conteo,
            ];
        }
        foreach($jackpots as $jackpot){
            $conteo = 0;
            foreach($participaciones as $id => $participacion){
                $intentos = JackpotIntentos::where('id_jackpot', $jackpot->id)->where('id_usuario', $id)->distinct('id_usuario')->count();
                $conteo += $respuestas;
            }
            $array_jackpots[$jackpot->id] = [
                'titulo'=> $jackpot->titulo,
                'participaciones'=> $conteo,
            ];
        }
        */

        $total_visualizaciones = 0;
        $total_evaluaciones = 0;
        $total_trivias = 0;
        $total_jackpots = 0;
        
        foreach($participaciones as $participacion){
            $total_visualizaciones += $participacion['visualizaciones'];
            $total_evaluaciones += $participacion['evaluaciones'];
            $total_trivias += $participacion['trivias'];
            $total_jackpots += $participacion['jackpots'];
        }

        $completo = [
            'usuario' => $usuario,
            'temporada' => $temporada,
            'suscripcion' => $suscripcion,
            'distribuidor' => $distribuidor,
            'suscriptores' => $suscriptores,
            'participaciones' => $participaciones,
            'activos' => $activos,
            'total_visualizaciones' => $total_visualizaciones,
            'total_evaluaciones' => $total_evaluaciones,
            'total_trivias' => $total_trivias,
            'total_jackpots' => $total_jackpots,
            'fechas' => $array_fechas,
            'sesiones_vis' => $array_visualizaciones,
            'sesiones_eval' => $array_evaluaciones,
            'trivias_res' => $array_trivias,
            'jackpots_intent' => $array_jackpots,
        ];
        return response()->json($completo);
    }

    public function agregar_usuario_api (Request $request)
    {
            // Verificar si el usuario ya existe
        $usuario = User::where('email', $request->correo)->first();
        

        if (!$usuario) {
            $usuario = new User();
            
            $usuario->legacy_id = uniqid('',true);
            $usuario->nombre = $request->nombre;
            $usuario->apellidos = $request->apellidos;
            $usuario->email = $request->correo;
            $usuario->telefono = '';
            $usuario->whatsapp = '';
            $usuario->fecha_nacimiento = null;
            $usuario->password = Hash::make('12345');
            $usuario->lista_correo = 'si';
            $usuario->imagen = 'default.jpg';
            $usuario->clase = 'usuario';
            $usuario->estado = 'activo';

            $usuario->save();
        }


        $suscripcion = UsuariosSuscripciones::where('id_usuario', $usuario->id)->where('id_temporada', $request->id_temporada)->first();
        if (!$suscripcion) {
            $suscripcion = new UsuariosSuscripciones();
            $suscripcion->id_usuario = $usuario->id;
            $suscripcion->id_cuenta = $request->id_cuenta;
            $suscripcion->id_temporada = $request->id_temporada;
            $suscripcion->id_distribuidor = $request->id_distribuidor;
            $suscripcion->confirmacion_puntos = 'pendiente';
            $suscripcion->funcion = 'usuario';
            $suscripcion->save();
        }
        
        return 'Guardado';
        
    }

    public function actualizar_usuario_api (Request $request)
    {
            // Verificar si el usuario ya existe
        $suscripcion = UsuariosSuscripciones::where('id', $request->suscripcion)->first();
        $usuario = User::where('id', $suscripcion->id_usuario)->first();
        $usuario->nombre = $request->nombre;
        $usuario->apellidos = $request->apellidos;
        $usuario->email = $request->correo;
        $usuario->save();
        
        return 'Guardado';
        
    }

    public function datos_basicos_lider_api (Request $request)
    {
        $id_cuenta = $request->input('id_cuenta');
        $id_usuario = $request->input('id_usuario');
        $cuenta = Cuenta::find($id_cuenta);
        $id_temporada = $cuenta->temporada_actual;
        $usuario = User::find($id_usuario);
        $temporada = Temporada::find($id_temporada);
        $suscripcion = UsuariosSuscripciones::where('id_temporada', $id_temporada)->where('id_usuario', $id_usuario)->first();
        $sesiones = SesionEv::where('id_temporada', $id_temporada)->count();
        $sesiones_pendientes = SesionEv::where('id_temporada', $id_temporada)->whereDate('fecha_publicacion', '>', now())->count();
        $lista_sesiones = SesionEv::where('id_temporada', $id_temporada)->whereDate('fecha_publicacion', '<=', now())->get();
        $trivias = Trivia::where('id_temporada', $id_temporada)->count();
        $trivias_pendientes = Trivia::where('id_temporada', $id_temporada)->whereDate('fecha_publicacion', '>', now())->count();
        $lista_trivias = Trivia::where('id_temporada', $id_temporada)->whereDate('fecha_publicacion', '<=', now())->get();
        $jackpots = Jackpot::where('id_temporada', $id_temporada)->count();
        $jackpots_pendientes = Jackpot::where('id_temporada', $id_temporada)->whereDate('fecha_publicacion', '>', now())->count();
        $lista_jackpots = Jackpot::where('id_temporada', $id_temporada)->whereDate('fecha_publicacion', '<=', now())->get();
       
        $distribuidor = Distribuidor::find($suscripcion->id_distribuidor);
        $suscriptores = DB::table('usuarios_suscripciones')
            ->join('usuarios', 'usuarios_suscripciones.id_usuario', '=', 'usuarios.id')
            ->where('usuarios_suscripciones.id_temporada', '=', $id_temporada)
            ->where('usuarios_suscripciones.id_distribuidor', '=', $suscripcion->id_distribuidor)
            ->select('usuarios.nombre', 'usuarios.apellidos','usuarios.email', 'usuarios_suscripciones.*')
            ->get();
        $array_suscriptores = array();
        $suscriptores_totales = 0;
        $suscriptores_activos = 0;
        $suscriptores_participantes = 0;
        $array_nombres = array();
        $top_sesiones = array();
        $array_nombres_sesiones = array();
        $top_trivias = array();
        $array_nombres_trivias = array();
        $top_jackpots = array();
        $array_jackpots = array();
        $top_10 = array();
        $no_usuarios_sesiones = 0;
        $no_usuarios_trivias = 0;
        $no_usuarios_jackpots = 0;

        foreach($lista_sesiones as $sesion){
            $conteo_vis = SesionVis::where('id_sesion', $sesion->id)->where('id_distribuidor', $suscripcion->id_distribuidor)->count();
            $conteo_res = EvaluacionRes::where('id_sesion', $sesion->id)->where('id_distribuidor', $suscripcion->id_distribuidor)->count();
            $top_sesiones[$sesion->id] =  $conteo_vis;
            $array_nombres_sesiones[$sesion->id] = $sesion->titulo;        }

        foreach($lista_trivias as $trivia){
            $conteo_res = TriviaRes::where('id_trivia', $trivia->id)->where('id_distribuidor', $suscripcion->id_distribuidor)->count();
            $top_trivias[$trivia->id] = $conteo_res;
            $array_nombres_trivias[$trivia->id] = $trivia->titulo;
        }
        foreach($lista_jackpots as $jackpot){
            $conteo_res = JackpotRes::where('id_jackpot', $jackpot->id)->where('id_distribuidor', $suscripcion->id_distribuidor)->count();
            $top_jacpots[$jackpot->id] = $conteo_res;
            $array_nombres_jackpots[$jackpot->id] = $jackpot->titulo;
        }


        foreach($suscriptores as $suscriptor){
            $array_nombres[$suscriptor->id_usuario] =  $suscriptor->nombre.' '.$suscriptor->apellidos;

            //Verifico si están activos
            $activo = false;
            $participante = false;
            $hay_login = Tokens::where('tokenable_id', $suscriptor->id_usuario)->first();
            if($hay_login){ $activo=true; }
            $hay_sesiones = SesionVis::where('id_temporada', $id_temporada)->where('id_usuario', $suscriptor->id_usuario)->first();
            if($hay_sesiones){ $participante=true; }
            $hay_evaluaciones = EvaluacionRes::where('id_temporada', $id_temporada)->where('id_usuario', $suscriptor->id_usuario)->first();
            if($hay_evaluaciones){ $participante=true; $no_usuarios_sesiones++;}
            $hay_trivias = TriviaRes::where('id_temporada', $id_temporada)->where('id_usuario', $suscriptor->id_usuario)->first();
            if($hay_trivias){ $participante=true; $no_usuarios_trivias++;}
            $hay_jackpot = JackpotIntentos::where('id_temporada', $id_temporada)->where('id_usuario', $suscriptor->id_usuario)->first();
            if($hay_jackpot){ $participante=true; $no_usuarios_jackpots++;}

            if($participante){ $activo=true; }


            // Cálculos de puntaje
            $puntos_sesiones = (int) SesionVis::where('id_temporada', $id_temporada)->where('id_usuario', $suscriptor->id_usuario)->sum('puntaje');
            $puntos_evaluaciones = (int) EvaluacionRes::where('id_temporada', $id_temporada)->where('id_usuario', $suscriptor->id_usuario)->sum('puntaje');
            $puntos_trivias = (int) TriviaRes::where('id_temporada', $id_temporada)->where('id_usuario', $suscriptor->id_usuario)->sum('puntaje');
            $puntos_jackpot = (int) JackpotIntentos::where('id_temporada', $id_temporada)->where('id_usuario', $suscriptor->id_usuario)->sum('puntaje');
            $puntos_extras = 0;
            $puntos_totales = $puntos_sesiones+$puntos_evaluaciones+$puntos_trivias+$puntos_jackpot+$puntos_extras;
            $top_10[$suscriptor->id_usuario] = $puntos_totales;
            
            
            $array_suscriptores[$suscriptor->id_usuario] = [ 
                'nombre' => $suscriptor->nombre,
                'apellidos' => $suscriptor->apellidos,
                'email' => $suscriptor->email,
                'suscripcion' => $suscriptor->id,
                'activo' => $activo,
                'participante' => $participante,
                'distribuidor' => $distribuidor->nombre,
                'puntos_sesiones' => $puntos_sesiones,
                'puntos_evaluaciones' => $puntos_evaluaciones,
                'puntos_trivias' => $puntos_trivias,
                'puntos_jackpots' => $puntos_jackpot,
                'puntos_extra' => $puntos_extras,
                'puntos_totales' => $puntos_totales
            ];

            if($activo){ $suscriptores_activos++; }
            if($participante){ $suscriptores_participantes++; }
            $suscriptores_totales ++;
        }
        // ordeno sesiones 
        $top_sesiones_ordenado = array();
        arsort($top_sesiones);
        foreach($top_sesiones as $id=>$puntos){
            $top_sesiones_ordenado[] = ['id' => $id, 'puntos' => $puntos];
        }

        // ordeno trivias 
        $top_trivias_ordenado = array();
        arsort($top_trivias);
        foreach($top_trivias as $id=>$puntos){
            $top_trivias_ordenado[] = ['id' => $id, 'puntos' => $puntos];
        }

        $top_jackpots_ordenado = array();
        arsort($top_jackpots);
        foreach($top_jackpots as $id=>$puntos){
            $top_jackpots_ordenado[] = ['id' => $id, 'puntos' => $puntos];
        }

        // ordeno top 10
        $top_10_ordenado = array();
        arsort($top_10);
        foreach($top_10 as $id=>$puntos){
            $top_10_ordenado[] = ['id' => $id, 'puntos' => $puntos];
        }

        //Gráfica
        $fecha_inicio = Carbon::now()->subDays(15); // Fecha 15 días atrás
        $fecha_final = Carbon::now(); // Fecha de hoy

        $fechas_array = [];
        $engagement_visualizaciones = [];
        $engagement_evaluaciones = [];
        $engagement_trivias = [];
        $engagement_jackpots = [];

        for ($fecha = $fecha_inicio; $fecha->lte($fecha_final); $fecha->addDay()) {
            $fechas_array[] = $fecha->toDateString();
            $engagement_visualizaciones[] = (int) SesionVis::where('id_temporada', $id_temporada)->whereDate('fecha_ultimo_video', $fecha->toDateString())->count();
            $engagement_evaluaciones[] = (int) EvaluacionRes::where('id_temporada', $id_temporada)->whereDate('fecha_registro', $fecha->toDateString())->count();
            $engagement_trivias[] = (int) TriviaRes::where('id_temporada', $id_temporada)->whereDate('fecha_registro', $fecha->toDateString())->count();
            $engagement_jackpots[] = (int) JackpotIntentos::where('id_temporada', $id_temporada)->whereDate('fecha_registro', $fecha->toDateString())->count();
        }


            $completo = [
                'usuario' => $usuario,
                'temporada' => $temporada,
                'suscripcion' => $suscripcion,
                'distribuidor' => $distribuidor,
                'sesiones' => $sesiones,
                'sesiones_pendientes' => $sesiones_pendientes,
                'trivias' => $trivias,
                'trivias_pendientes' => $trivias_pendientes,
                'jackpots' => $jackpots,
                'jackpots_pendientes' => $jackpots_pendientes,
                'suscriptores' => $array_suscriptores,
                'totales' => $suscriptores_totales,
                'activos' => $suscriptores_activos,
                'participantes' => $suscriptores_participantes,
                'array_nombres' => $array_nombres,
                'array_nombres_sesiones' => $array_nombres_sesiones,
                'array_nombres_trivias' => $array_nombres_trivias,
                'top_sesiones' => $top_sesiones_ordenado,
                'top_trivias' => $top_trivias_ordenado,
                'top_jackpots' => $top_trivias_ordenado,
                'top_10' => $top_10_ordenado,
                'no_usuarios_sesiones' => $no_usuarios_sesiones,
                'no_usuarios_trivias' => $no_usuarios_trivias,
                'no_usuarios_jackpots' => $no_usuarios_jackpots,
                'fechas_array' => $fechas_array,
                'engagement_visualizaciones' => $engagement_visualizaciones,
                'engagement_evaluaciones' => $engagement_evaluaciones,
                'engagement_trivias' => $engagement_trivias,
                'engagement_jackpots' => $engagement_jackpots,
            ];
            return response()->json($completo);


    }

    public function panel_lider_api (Request $request)
    {
        $id_cuenta = $request->input('id_cuenta');
        $id_usuario = $request->input('id_usuario');
        $cuenta = Cuenta::find($id_cuenta);
        $id_temporada = $cuenta->temporada_actual;
        $usuario = User::find($id_usuario);
        $temporada = Temporada::find($id_temporada);
        $suscripcion = UsuariosSuscripciones::where('id_temporada', $id_temporada)->where('id_usuario', $id_usuario)->first();
       
        $distribuidor = Distribuidor::find($suscripcion->id_distribuidor);

        //Gráfica
        $fecha_inicio = $request->input('fecha_inicio') ? Carbon::parse($request->input('fecha_inicio')) : Carbon::now()->subDays(15);
        $fecha_final = $request->input('fecha_final') ? Carbon::parse($request->input('fecha_final')) : Carbon::now();

        $fechas_array = [];
        $engagement_visualizaciones = [];
        $engagement_evaluaciones = [];
        $engagement_trivias = [];
        $engagement_jackpots = [];

        for ($fecha = $fecha_inicio; $fecha->lte($fecha_final); $fecha->addDay()) {
            $fechas_array[] = $fecha->toDateString();
            $engagement_visualizaciones[] = (int) SesionVis::where('id_temporada', $id_temporada)->whereDate('fecha_ultimo_video', $fecha->toDateString())->count();
            $engagement_evaluaciones[] = (int) EvaluacionRes::where('id_temporada', $id_temporada)->whereDate('fecha_registro', $fecha->toDateString())->count();
            $engagement_trivias[] = (int) TriviaRes::where('id_temporada', $id_temporada)->whereDate('fecha_registro', $fecha->toDateString())->count();
            $engagement_jackpots[] = (int) JackpotIntentos::where('id_temporada', $id_temporada)->whereDate('fecha_registro', $fecha->toDateString())->count();
        }


            $completo = [
                'fechas_array' => $fechas_array,
                'engagement_visualizaciones' => $engagement_visualizaciones,
                'engagement_evaluaciones' => $engagement_evaluaciones,
                'engagement_trivias' => $engagement_trivias,
                'engagement_jackpots' => $engagement_jackpots,
            ];
            return response()->json($completo);


    }

    public function eliminar_usuario_api (Request $request)
    {
        $id_cuenta = $request->input('id_cuenta');
        $id_temporada = $request->input('id_temporada');
        $id_suscripcion = $request->input('id_suscripcion');
        $cuenta = Cuenta::find($id_cuenta);
        $temporada = Temporada::find($id_cuenta);
        $suscripcion = UsuariosSuscripciones::find($id_suscripcion);
        $usuario = User::find($suscripcion->id_usuario);
        

        $visualizaciones = SesionVis::where('id_usuario',$usuario->id)->where('id_temporada', $id_temporada)->delete();
        $evaluaciones = EvaluacionRes::where('id_usuario',$usuario->id)->where('id_temporada', $id_temporada)->delete();
        $trivias = TriviaRes::where('id_usuario',$usuario->id)->where('id_temporada', $id_temporada)->delete();
        $trivias = TriviaGanador::where('id_usuario',$usuario->id)->where('id_temporada', $id_temporada)->delete();
        $jackpot_respuestas = JackpotRes::where('id_usuario',$usuario->id)->where('id_temporada', $id_temporada)->delete();
        $jackpot_intentos = JackpotIntentos::where('id_usuario',$usuario->id)->where('id_temporada', $id_temporada)->delete();
        $suscripcion->delete();

        return 'Eliminado';
        
       
    }
}
