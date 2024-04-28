<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\Clase;
use App\Models\Temporada;
use App\Models\UsuariosSuscripciones;
use App\Models\Trivia;
use App\Models\TriviaPreg;
use App\Models\TriviaRes;
use App\Models\TriviaGanador;
use App\Models\User;
use App\Models\Distribuidor;
use Illuminate\Support\Facades\DB;


class TriviasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        //
        $id_temporada = $request->input('id_temporada');
        $trivias = Trivia::where('id_temporada', $id_temporada)->paginate();
        return view('admin/trivia_lista', compact('trivias'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        //
        $temporada = Temporada::find($request->input('id_temporada'));
        return view('admin/trivia_form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
         //
         $trivia = new Trivia();

         $trivia->id_cuenta = $request->IdCuenta;
         $trivia->id_temporada = $request->IdTemporada;
         $trivia->titulo = $request->Titulo;
         $trivia->descripcion = $request->Descripcion;
         $trivia->mensaje_antes = $request->MensajeAntes;
         $trivia->mensaje_despues = $request->MensajeDespues;
         $trivia->fecha_publicacion = date('Y-m-d H:i:s', strtotime($request->FechaPublicacion.' '.$request->HoraPublicacion));
         $trivia->fecha_vigencia = date('Y-m-d H:i:s', strtotime($request->FechaVigencia.' '.$request->HoraVigencia));
         $trivia->puntaje = $request->Puntaje;
         $trivia->estado = $request->Estado;
 
         $trivia->save();
 
         return redirect()->route('trivias.show', $trivia->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $trivia = Trivia::find($id);
        $preguntas = TriviaPreg::where('id_trivia',$id)->get();
        return view('admin/trivia_detalles', compact('trivia', 'preguntas'));
    }

    public function resultados(string $id)
    {
        //
        $trivia = Trivia::find($id);
        $preguntas = TriviaPreg::where('id_trivia',$id)->get();
        
        $respuestas = DB::table('trivias_respuestas')
            ->join('usuarios', 'trivias_respuestas.id_usuario', '=', 'usuarios.id')
            ->join('trivias_preguntas', 'trivias_respuestas.id_pregunta', '=', 'trivias_preguntas.id')
            ->where('trivias_respuestas.id_trivia', '=', $id)
            ->select('trivias_respuestas.id as id_respuesta','trivias_respuestas.respuesta_correcta as respuesta_resultado' , 'trivias_respuestas.*', 'usuarios.id as id_usuario', 'usuarios.*', 'trivias_preguntas.*')
            ->orderBy('trivias_respuestas.fecha_registro', 'desc')
            ->get();
        $ganadores = DB::table('trivias_ganadores')
            ->join('usuarios', 'trivias_ganadores.id_usuario', '=', 'usuarios.id')
            ->join('distribuidores', 'trivias_ganadores.id_distribuidor', '=', 'distribuidores.id')
            ->where('trivias_ganadores.id_trivia', '=', $id)
            ->select('trivias_ganadores.id as id_ganador', 'trivias_ganadores.*', 'usuarios.id as id_usuario', 'usuarios.nombre as nombre_usuario', 'usuarios.*', 'distribuidores.nombre as nombre_distribuidor', 'distribuidores.*')
            ->orderBy('trivias_ganadores.fecha_registro', 'desc')
            ->get();
        return view('admin/trivia_resultados', compact('trivia', 'preguntas', 'respuestas', 'ganadores'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $trivia = Trivia::find($id);
        return view('admin/trivia_form_actualizar', compact('trivia'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $trivia = Trivia::find($id);

         $trivia->id_cuenta = $request->IdCuenta;
         $trivia->id_temporada = $request->IdTemporada;
         $trivia->titulo = $request->Titulo;
         $trivia->descripcion = $request->Descripcion;
         $trivia->mensaje_antes = $request->MensajeAntes;
         $trivia->mensaje_despues = $request->MensajeDespues;
         $trivia->fecha_publicacion = date('Y-m-d H:i:s', strtotime($request->FechaPublicacion.' '.$request->HoraPublicacion));
         $trivia->fecha_vigencia = date('Y-m-d H:i:s', strtotime($request->FechaVigencia.' '.$request->HoraVigencia));
         $trivia->puntaje = $request->Puntaje;
         $trivia->estado = $request->Estado;
 
         $trivia->save();
 
         return redirect()->route('trivias.show', $trivia->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $trivia = Trivia::findOrFail($id);
        $id_temporada = $trivia->id_temporada;
        // Buscar y eliminar registros relacionados en otras tablas
        TriviaPreg::where('id_trivia', $id)->delete();
        TriviaRes::where('id_trivia', $id)->delete();


        $trivia->delete();
        return redirect()->route('trivias', ['id_temporada'=>$id_temporada]);
    }

    public function destroy_ganador(string $id)
    {
        //
        $ganador = TriviaGanador::findOrFail($id);
        $id_trivia =  $ganador->id_trivia;
        
        $ganador->delete();
        return redirect()->route('trivias.resultados', $id_trivia);
    }

    public function destroy_respuesta(string $id)
    {
        //
        $respuesta = TriviaRes::findOrFail($id);
        $id_trivia =  $respuesta->id_trivia;
        $respuesta->delete();
        return redirect()->route('trivias.resultados', $id_trivia);
    }

    /**
     * Funciones de preguntas
     */

     public function store_pregunta(Request $request)
    {
        //
         //
         $pregunta = new TriviaPreg();

        $pregunta->id_trivia = $request->IdTrivia;
        $pregunta->pregunta = $request->Pregunta;
        $pregunta->respuesta_a = $request->RespuestaA;
        $pregunta->respuesta_b = $request->RespuestaB;
        $pregunta->respuesta_c = $request->RespuestaC;
        $pregunta->respuesta_d = $request->RespuestaD;
        $pregunta->respuesta_correcta = $request->RespuestaCorrecta;
        $pregunta->orden = 0;

        $pregunta->save();
 
        return redirect()->route('trivias.show', $pregunta->id_trivia);
    }

    public function update_pregunta(Request $request, string $id)
    {
        //
         $pregunta = TriviaPreg::find($id);

         $pregunta->id_trivia = $request->IdTrivia;
         $pregunta->pregunta = $request->Pregunta;
         $pregunta->respuesta_a = $request->RespuestaA;
         $pregunta->respuesta_b = $request->RespuestaB;
         $pregunta->respuesta_c = $request->RespuestaC;
         $pregunta->respuesta_d = $request->RespuestaD;
         $pregunta->respuesta_correcta = $request->RespuestaCorrecta;
         $pregunta->orden = 0;
 
         $pregunta->save();

         return redirect()->route('trivias.show', $pregunta->id_trivia);
    }

    public function destroy_pregunta(string $id)
    {
        //
        $pregunta = TriviaPreg::findOrFail($id);
        $id_trivia = $pregunta->id_trivia;
        // Buscar y eliminar registros relacionados en otras tablas
        TriviaRes::where('id_pregunta', $pregunta->id)->delete();

        $pregunta->delete();
        return redirect()->route('trivias.show', $id_trivia);
    }


    /**
     * Funciones API
     */

     public function todos_datos_trivia_api(Request $request)
     {
         //Variables
         $fecha_actual = Carbon::now();
         $id_temporada = $request->input('id_temporada');
         $id_usuario = $request->input('id_usuario');
         $suscripcion = UsuariosSuscripciones::where('id_usuario', $id_usuario)->where('id_temporada', $id_temporada)->first();
         $distribuidor = Distribuidor::where('id', $suscripcion->id_distribuidor)->first();
         // consulta
         $trivia = Trivia::where('id_temporada', $id_temporada)
                       ->where('estado', 'activo')
                       ->first();
        $preguntas = TriviaPreg::where('id_trivia',$trivia->id)->get();
        $respuestas = TriviaRes::where('id_trivia',$trivia->id)->where('id_usuario',$id_usuario)->get();
        $respuestas_historico = TriviaRes::where('id_usuario',$id_usuario)->pluck('puntaje')->sum();

        $participantes = UsuariosSuscripciones::where('id_temporada', $id_temporada)->where('id_distribuidor', $distribuidor->id)->get();
        $ganadores= TriviaGanador::where('id_trivia', $trivia->id)->get();
        $ganador= TriviaGanador::where('id_trivia', $trivia->id)->where('id_usuario', $id_usuario)->first();
        $mis_premios= TriviaGanador::where('id_trivia', $trivia->id)->where('id_usuario', $id_usuario)->get();
        $soy_ganador = false;
        if($ganador){
            $soy_ganador = true;
        }
        $premios = array();
        foreach($participantes as $participante){
            $respuestas_participante = TriviaRes::where('id_trivia',$trivia->id)->where('id_usuario',$participante->id_usuario)->get();
            // Verificar si hay respuestas para este participante
            if ($respuestas_participante->isNotEmpty()) {
                $correctas = true;
                $fecha = null;

                foreach ($respuestas_participante as $respuesta) {
                    if ($respuesta->correcta == 'incorrecto') {
                        $correctas = false;
                    }
                    $fecha = $respuesta->fecha_registro;
                }

                // Agregar el registro solo si hay respuestas
                if($correctas){
                    $premios[] = [
                        'id' => $id_usuario,
                        'fecha' => $fecha
                    ];
                }
                
            }
            
        }

        
        $completo = [
            'trivia' => $trivia,
            'participante' => $participante,
            'distribuidor' => $distribuidor,
            'preguntas' => $preguntas,
            'respuestas' => $respuestas,
            'premios' => $ganadores,
            'ganador' => $soy_ganador,
            'mis_premios' => $mis_premios,
            'respuestas_historico' => $respuestas_historico
        ];

         return response()->json($completo);
     }

    public function datos_trivia_api(Request $request)
    {
        //Variables
        $fecha_actual = Carbon::now();
        // consulta
        $trivia = Trivia::where('fecha_publicacion', '<=', $fecha_actual)
                      ->where('fecha_vigencia', '>', $fecha_actual)
                      ->first();
        return response()->json($trivia);
    }

    public function registrar_respuestas_trivia_api(Request $request)
    {
        $id_trivia = $request->input('id_trivia');
        $id_usuario = $request->input('id_usuario');
        $respuestas_json = $request->input('respuestas');
        $trivia = Trivia::find($id_trivia);
        $temporada = Temporada::find($jackpot->id_temporada);
        $suscripcion = UsuariosSuscripciones::where('id_usuario', $id_usuario)->where('id_temporada', $trivia->id_temporada)->first();
        $distribuidor = Distribuidor::where('id', $suscripcion->id_distribuidor)->first();
        //return response()->json($respuestas_json);
        
        //$respuestas_array = json_decode($respuestas_json, true);
        $hay_respuestas = TriviaRes::where('id_trivia', $id_trivia)->where('id_usuario', $id_usuario)->first();
        $hay_ganador= TriviaGanador::where('id_trivia', $id_trivia)->where('id_distribuidor', $distribuidor->id)->first();
        
        if(!$hay_respuestas){
            $guardadas = true;
            $todas_correctas = true;
            foreach ($respuestas_json as $pregunta=>$respuesta) {
                $registro_respuesta = TriviaRes::where('id_trivia', $id_trivia)->where('id_usuario', $id_usuario)->where('id_pregunta', $pregunta)->first();
                
                if(!$registro_respuesta){
                    $pregunta_reg = TriviaPreg::find($pregunta);
                    if($respuesta==$pregunta_reg->respuesta_correcta){
                        $respuesta_correcta = 'correcto';
                        $puntaje = $trivia->puntaje;
                    }else{
                        $respuesta_correcta = 'incorrecto';
                        $puntaje = 0;
                        $todas_correctas = false;
                    }
                    
                    // Si no existe, crear una nueva visualización
                    $nueva_respuesta = new TriviaRes();
                    $nueva_respuesta->id_usuario = $id_usuario;
                    $nueva_respuesta->id_trivia = $id_trivia;
                    $nueva_respuesta->id_temporada = $temporada->id;
                    if($suscripcion){
                        $nueva_respuesta->id_distribuidor = $suscripcion->id_distribuidor;
                    }
                    $nueva_respuesta->id_pregunta = $pregunta;
                    $nueva_respuesta->puntaje = $puntaje;
                    $nueva_respuesta->respuesta_usuario = $respuesta;
                    $nueva_respuesta->respuesta_correcta = $respuesta_correcta;
                    $nueva_respuesta->fecha_registro = date('Y-m-d H:i:s');
                    if(!$nueva_respuesta->save()){
                        $guardadas = false;
                        return response()->json(['success' => false, 'message' => 'No se pudo guardar la respuesta '.$registro_respuesta]);
                    }
                    
                }
                
            }
            if(!$hay_ganador&&$todas_correctas){
                $nuevo_ganador = new TriviaGanador();
                $nuevo_ganador->id_trivia = $id_trivia;
                $nuevo_ganador->id_temporada = $temporada->id;
                if($suscripcion){
                    $nuevo_ganador->id_distribuidor = $suscripcion->id_distribuidor;
                }
                $nuevo_ganador->id_usuario = $id_usuario;
                $nuevo_ganador->id_distribuidor = $distribuidor->id;
                $nuevo_ganador->fecha_registro = date('Y-m-d H:i:s');
                $nuevo_ganador->save();

            }
            if($guardadas){
                return response()->json(['success' => true, 'message' => 'Se guardaron todas las preguntas']);
            }else{
                return response()->json(['success' => false, 'message' => 'Algúna pregunta no se guardó']);
            }
        }else{
            return response()->json(['success' => false, 'message' => 'Ya hay respuestas']);
        }
        
        
        
    }
}
