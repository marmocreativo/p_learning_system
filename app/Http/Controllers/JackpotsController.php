<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\Jackpot;
use App\Models\JackpotPreg;
use App\Models\JackpotRes;
use App\Models\JackpotIntentos;
use App\Models\Clase;
use App\Models\Temporada;

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
        return view('admin/jackpot_lista', compact('jackpots'));
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
         $jackpot->estado = $request->Estado;
 
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
         $jackpot->estado = $request->Estado;
 
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
     * Funciones API
     */

     public function todos_datos_jackpot_api(Request $request)
     {
         //Variables
         $fecha_actual = Carbon::now();
         $id_temporada = $request->input('id_temporada');
         // consulta
         $jackpot = Jackpot::where('id_temporada', $id_temporada)
                       ->where('estado', 'activo')
                       ->first();
        $preguntas = JackpotPreg::where('id_jackpot',$jackpot->id)->get();
        $respuestas = JackpotRes::where('id_jackpot',$jackpot->id)->where('id_usuario',$request->input('id_usuario'))->get();
        $intentos = JackpotIntentos::where('id_jackpot',$jackpot->id)->where('id_usuario',$request->input('id_usuario'))->get();
        
        $completo = [
            'jackpot' => $jackpot,
            'preguntas' => $preguntas,
            'respuestas' => $respuestas,
            'intentos' => $intentos,
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
                    $registro_respuesta->id_pregunta = $pregunta;
                    $registro_respuesta->respuesta_usuario = $respuesta;
                    $registro_respuesta->respuesta_correcta = $respuesta_correcta;
                    $registro_respuesta->fecha_registro = date('Y-m-d H:i:s');

                    $registro_respuesta->save();
                }
            }
        }

        
    }

    public function registrar_intento_jackpot_api(Request $request)
    {
        $id_jackpot = $request->input('id_jackpot');
        $id_usuario = $request->input('id_usuario');
        $tiro = $request->input('tiro');
        $slot_1 = $request->input('slot_1');
        $slot_2 = $request->input('slot_2');
        $slot_3 = $request->input('slot_3');
        $slot_premio = $request->input('slot_premio');
        $puntaje = $request->input('puntaje');

        $registro_intento = new JackpotIntentos();
        $registro_intento->id_usuario = $id_usuario;
        $registro_intento->id_jackpot = $id_jackpot;
        $registro_intento->tiro = $tiro;
        $registro_intento->slot_1 = $slot_1;
        $registro_intento->slot_2 = $slot_2;
        $registro_intento->slot_3 = $slot_3;
        $registro_intento->slot_premio = $slot_premio;
        $registro_intento->puntaje = $puntaje;
        $registro_intento->fecha_registro = date('Y-m-d H:i:s');

        $registro_intento->save();

        
    }

    
}