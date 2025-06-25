<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\Jackpot;
use App\Models\JackpotPreg;
use App\Models\JackpotRes;
use App\Models\JackpotIntentos;
use App\Models\Distribuidor;
use App\Models\Clase;
use App\Models\Cuenta;
use App\Models\Temporada;
use App\Models\UsuariosSuscripciones;
use App\Models\User;
use App\Models\AccionesUsuarios;
use Illuminate\Support\Facades\DB;

use App\Exports\ReporteJackpotExport;
use Maatwebsite\Excel\Facades\Excel;

class JackpotsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $id_temporada = $request->input('id_temporada');
        $jackpots = Jackpot::where('id_temporada', $id_temporada)->paginate();
        $temporada = Temporada::find($id_temporada);
        $cuentas = Cuenta::all();
        $cuenta = Cuenta::find($temporada->id_cuenta);
        $color_barra_superior = $cuenta->fondo_menu;
        $logo_cuenta = 'https://system.panduitlatam.com/img/publicaciones/'.$cuenta->logotipo;
        return view('admin/jackpot_lista', compact('jackpots', 'temporada',
'cuentas',
'cuenta',
'color_barra_superior',
'logo_cuenta',));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        //
        $temporada = Temporada::find($request->input('id_temporada'));
        return view('admin/jackpot_form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         //
         $jackpot = new Jackpot();

         $jackpot->id_cuenta = $request->IdCuenta;
         $jackpot->id_temporada = $request->IdTemporada;
         $jackpot->titulo = $request->Titulo;
         $jackpot->mensaje_antes = $request->MensajeAntes;
         $jackpot->mensaje_despues = $request->MensajeDespues;
         
         $jackpot->fecha_publicacion = date('Y-m-d H:i:s', strtotime($request->FechaPublicacion.' '.$request->HoraPublicacion));
         $jackpot->fecha_vigencia = date('Y-m-d H:i:s', strtotime($request->FechaVigencia.' '.$request->HoraVigencia));
         $jackpot->intentos = $request->Intentos;
         $jackpot->trivia = $request->Trivia;
         $jackpot->region = $request->Region;
         $jackpot->estado = $request->Estado;
         $jackpot->en_trivia = $request->EnTrivia;
         $jackpot->tipo = $request->Tipo;
 
         $jackpot->save();
 
         return redirect()->route('jackpots.show', $jackpot->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        //
        $jackpot = Jackpot::find($id);
        $preguntas = JackpotPreg::where('id_jackpot',$id)->get();
        return view('admin/jackpot_detalles', compact('jackpot', 'preguntas'));
    }

    public function resultados(string $id)
    {
        //
        $jackpot = Jackpot::find($id);
        $preguntas = JackpotPreg::where('id_jackpot',$id)->get();
        
        $respuestas = DB::table('jackpot_respuestas')
            ->join('usuarios', 'jackpot_respuestas.id_usuario', '=', 'usuarios.id')
            ->join('jackpot_preguntas', 'jackpot_respuestas.id_pregunta', '=', 'jackpot_preguntas.id')
            ->where('jackpot_respuestas.id_jackpot', '=', $id)
            ->select('jackpot_respuestas.id as id_respuesta',
                    'jackpot_respuestas.respuesta_correcta as respuesta_resultado',
                    'jackpot_respuestas.*', 
                    'usuarios.id as id_usuario',
                    'usuarios.*',
                    'jackpot_preguntas.*')
            ->orderBy('jackpot_respuestas.fecha_registro', 'desc')
            ->get();
        $ganadores = DB::table('jackpot_intentos')
            ->join('usuarios', 'jackpot_intentos.id_usuario', '=', 'usuarios.id')
            ->where('jackpot_intentos.id_jackpot', '=', $id)
            ->select('jackpot_intentos.id as id_ganador', 'jackpot_intentos.*', 'usuarios.id as id_usuario', 'usuarios.nombre as nombre_usuario', 'usuarios.*')
            ->orderBy('jackpot_intentos.fecha_registro', 'desc')
            ->get();
        return view('admin/jackpot_resultados', compact('jackpot', 'preguntas', 'respuestas', 'ganadores'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        
        //
        $jackpot = Jackpot::find($id);
        return view('admin/jackpot_form_actualizar', compact('jackpot'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $jackpot = Jackpot::find($id);
        $jackpot->id_cuenta = $request->IdCuenta;
         $jackpot->id_temporada = $request->IdTemporada;
         $jackpot->titulo = $request->Titulo;
         $jackpot->mensaje_antes = $request->MensajeAntes;
         $jackpot->mensaje_despues = $request->MensajeDespues;
         
         $jackpot->fecha_publicacion = date('Y-m-d H:i:s', strtotime($request->FechaPublicacion.' '.$request->HoraPublicacion));
         $jackpot->fecha_vigencia = date('Y-m-d H:i:s', strtotime($request->FechaVigencia.' '.$request->HoraVigencia));
         $jackpot->intentos = $request->Intentos;
         $jackpot->trivia = $request->Trivia;
         $jackpot->region = $request->Region;
         $jackpot->estado = $request->Estado;
         $jackpot->en_trivia = $request->EnTrivia;
         $jackpot->tipo = $request->Tipo;
 
         $jackpot->save();

         return redirect()->route('jackpots.show', $jackpot->id);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
         //
         $jackpot = Jackpot::findOrFail($id);
         $id_temporada = $jackpot->id_temporada;
         // Buscar y eliminar registros relacionados en otras tablas
         JackpotPreg::where('id_jackpot', $id)->delete();
         JackpotRes::where('id_jackpot', $id)->delete();
         JackpotIntentos::where('id_jackpot', $id)->delete();
 
 
         $jackpot->delete();
         return redirect()->route('jackpots', ['id_temporada'=>$id_temporada]);
    }

    public function destroy_intento(string $id)
    {
        //
        $intento = JackpotIntentos::findOrFail($id);
        $id_jackpot =  $intento->id_jackpot;
        
        $intento->delete();
        return redirect()->route('jackpots.resultados', $id_jackpot);
    }

    public function destroy_respuesta(string $id)
    {
        //
        $respuesta = JackpotRes::findOrFail($id);
        $id_jackpot =  $respuesta->id_jackpot;
        $respuesta->delete();
        return redirect()->route('jackpots.resultados', $id_jackpot);
    }


    /**
     * Funciones de preguntas
     */

     public function store_pregunta(Request $request)
    {
        //
         //
         $pregunta = new JackpotPreg();

        $pregunta->id_jackpot = $request->IdJackpot;
        $pregunta->pregunta = $request->Pregunta;
        $pregunta->respuesta_a = $request->RespuestaA;
        $pregunta->respuesta_b = $request->RespuestaB;
        $pregunta->respuesta_c = $request->RespuestaC;
        $pregunta->respuesta_d = $request->RespuestaD;
        $pregunta->respuesta_correcta = $request->RespuestaCorrecta;
        $pregunta->orden = 0;

        $pregunta->save();
 
        return redirect()->route('jackpots.show', $pregunta->id_jackpot);
    }

    public function update_pregunta(Request $request, string $id)
    {
        //
         $pregunta = JackpotPreg::find($id);

         $pregunta->id_jackpot = $request->IdJackpot;
         $pregunta->pregunta = $request->Pregunta;
         $pregunta->respuesta_a = $request->RespuestaA;
         $pregunta->respuesta_b = $request->RespuestaB;
         $pregunta->respuesta_c = $request->RespuestaC;
         $pregunta->respuesta_d = $request->RespuestaD;
         $pregunta->respuesta_correcta = $request->RespuestaCorrecta;
         $pregunta->orden = 0;
 
         $pregunta->save();

         return redirect()->route('jackpots.show', $pregunta->id_jackpot);
    }

    public function destroy_pregunta(string $id)
    {
        //
        $pregunta = JackpotPreg::findOrFail($id);
        $id_jackpot = $pregunta->id_jackpot;
        // Buscar y eliminar registros relacionados en otras tablas
        JackpotRes::where('id_pregunta', $pregunta->id)->delete();

        $pregunta->delete();
        return redirect()->route('jackpots.show', $id_jackpot);
    }

     /**
     * Exports de excel
     */

     public function resultados_excel (Request $request)
    {
        return Excel::download(new ReporteJackpotExport($request), 'reporte_jackpot.xlsx');
        
    }


    /**
     * Funciones API
     */

     public function todos_datos_jackpot_api(Request $request)
     {
         //Variables
         $fecha_actual = Carbon::now();
         $id_temporada = $request->input('id_temporada');
         // consulta
          $fecha_hoy = Carbon::now();
         $jackpot = Jackpot::where('id_temporada', $id_temporada)
                       ->where('fecha_publicacion', '<=', $fecha_hoy)
                        ->where('fecha_vigencia', '>=', $fecha_hoy)
                        ->where('en_trivia', 'no')
                       ->first();
        if($jackpot){
            $preguntas = JackpotPreg::where('id_jackpot',$jackpot->id)->get();
            $respuestas = JackpotRes::where('id_jackpot',$jackpot->id)->where('id_usuario',$request->input('id_usuario'))->get();
            $intentos = JackpotIntentos::where('id_jackpot',$jackpot->id)->where('id_usuario',$request->input('id_usuario'))->get();
            
        }else{
            $preguntas = null;
            $respuestas = null;
            $intentos = null;
        }

        $usuario = User::find($request->input('id_usuario'));
        $suscripcion = UsuariosSuscripciones::where('id_usuario', $request->input('id_usuario'))->where('id_temporada', $id_temporada )->first();
        $distribuidor = Distribuidor::where('id', $suscripcion->id_distribuidor)->first();

        /*
        if(($request->input('id_temporada')=='9'&&$request->input('id_usuario')!='1197')||$request->input('id_usuario')=='1'){
            $jackpot = null;
            $preguntas = null;
            $respuestas = null;
            $intentos = null;
            $suscripcion = null;
            $distribuidor = null;
        }
            */
        
        
        $completo = [
            'jackpot' => $jackpot,
            'preguntas' => $preguntas,
            'respuestas' => $respuestas,
            'intentos' => $intentos,
            'suscripcion' => $suscripcion,
            'distribuidor' => $distribuidor,
        ];

         return response()->json($completo);
     }


     public function datos_jackpot_api(Request $request)
     {
         //Variables
         $fecha_actual = Carbon::now();
         $id_temporada = $request->input('id_temporada');
         // consulta
         $jackpot = Jackpot::where('id_temporada', $id_temporada)
                       ->where('estado', 'activo')
                       ->first();
         return response()->json($jackpot);
     }

     public function preguntas_jackpot_api(Request $request)
    {
        //
        $preguntas = JackpotPreg::where('id_jackpot',$request->input('id'))->get();
        return response()->json($preguntas);
    }

     public function respuestas_jackpot_api(Request $request)
    {
        //
        $respuestas = JackpotRes::where('id_jackpot',$request->input('id_jackpot'))->where('id_usuario',$request->input('id_usuario'))->get();
        return response()->json($respuestas);
    }

    public function intentos_jackpot_api(Request $request)
    {
        //
        $intentos = JackpotIntentos::where('id_jackpot',$request->input('id_jackpot'))->where('id_usuario',$request->input('id_usuario'))->get();
        return response()->json($intentos);
    }

    public function registrar_respuestas_jackpot_api(Request $request)
    {
        $id_jackpot = $request->input('id_jackpot');
        $id_usuario = $request->input('id_usuario');
        $respuestas_json = $request->input('respuestas');
        $jackpot = Jackpot::find($id_jackpot);
        $temporada = Temporada::find($jackpot->id_temporada);
        $suscripcion = UsuariosSuscripciones::where('id_usuario',$id_usuario)->where('id_temporada',$temporada->id)->first();

        //$respuestas_array = json_decode($respuestas_json, true);
        $hay_respuestas = JackpotRes::where('id_jackpot', $id_jackpot)->where('id_usuario', $id_usuario)->first();
        if(!$hay_respuestas){
            foreach ($respuestas_json as $pregunta=>$respuesta) {
                $registro_respuesta = JackpotRes::where('id_jackpot', $id_jackpot)->where('id_usuario', $id_usuario)->where('id_pregunta', $pregunta)->first();
                // Verificar si la visualización existe
                if(!$registro_respuesta){
                    $pregunta_reg = JackpotPreg::find($pregunta);
                    if($respuesta==$pregunta_reg->respuesta_correcta){
                        $respuesta_correcta = 'correcto';
                    }else{
                        $respuesta_correcta = 'incorrecto';
                    }
                    // Si no existe, crear una nueva visualización
                    $registro_respuesta = new JackpotRes();
                    $registro_respuesta->id_usuario = $id_usuario;
                    $registro_respuesta->id_jackpot = $id_jackpot;
                    $registro_respuesta->id_temporada = $temporada->id;
                    if($suscripcion){
                        $registro_respuesta->id_distribuidor = $suscripcion->id_distribuidor;
                    }
                    $registro_respuesta->id_pregunta = $pregunta;
                    $registro_respuesta->respuesta_usuario = $respuesta;
                    $registro_respuesta->respuesta_correcta = $respuesta_correcta;
                    $registro_respuesta->fecha_registro = date('Y-m-d H:i:s');

                    $registro_respuesta->save();

                    $accion = new AccionesUsuarios;
                    $usuario = User::find($id_usuario);
                    $accion->id_usuario = $usuario->id;
                    $accion->nombre = $usuario->nombre.' '.$usuario->apellidos;
                    $accion->correo = $usuario->email;
                    $accion->accion = 'respondio preguntas minijuego';
                    $accion->descripcion = 'Se respondieron las preguntas del minijuego: '.$jackpot->titulo;
                    $accion->id_cuenta = $jackpot->id_cuenta;
                    $accion->id_temporada = $jackpot->id_temporada;
                    $accion->funcion = 'usuario';
                    $accion->save();
                }
            }
        }

        
    }

    public function registrar_intento_jackpot_api(Request $request)
    {
        $id_jackpot = $request->input('id_jackpot');
        $id_usuario = $request->input('id_usuario');
        $jackpot = Jackpot::find($id_jackpot);
        $temporada = Temporada::find($jackpot->id_temporada);
        $suscripcion = UsuariosSuscripciones::where('id_usuario',$id_usuario)->where('id_temporada',$temporada->id)->first();

        $tiro = $request->input('tiro');
        $slot_1 = $request->input('slot_1');
        $slot_2 = $request->input('slot_2');
        $slot_3 = $request->input('slot_3');
        $slot_premio = $request->input('slot_premio');
        $puntaje = $request->input('puntaje');

        $registro_intento = new JackpotIntentos();
        $registro_intento->id_usuario = $id_usuario;
        $registro_intento->id_jackpot = $id_jackpot;
        $registro_intento->id_temporada = $temporada->id;
        if($suscripcion){
            $registro_intento->id_distribuidor = $suscripcion->id_distribuidor;
        }
        $registro_intento->tiro = $tiro;
        $registro_intento->slot_1 = $slot_1;
        $registro_intento->slot_2 = $slot_2;
        $registro_intento->slot_3 = $slot_3;
        $registro_intento->slot_premio = $slot_premio;
        $registro_intento->puntaje = $puntaje;
        $registro_intento->fecha_registro = date('Y-m-d H:i:s');

        $registro_intento->save();

        $accion = new AccionesUsuarios;
        $usuario = User::find($id_usuario);
        $accion->id_usuario = $usuario->id;
        $accion->nombre = $usuario->nombre.' '.$usuario->apellidos;
        $accion->correo = $usuario->email;
        $accion->accion = 'minijuego intento';
        $accion->descripcion = 'Se completó un inento en el minijuego: '.$jackpot->titulo;
        $accion->id_cuenta = $jackpot->id_cuenta;
        $accion->id_temporada = $jackpot->id_cuenta;
        $accion->funcion = 'usuario';
        $accion->save();

        
    }

    
}
