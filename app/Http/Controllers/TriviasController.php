<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\Clase;
use App\Models\Temporada;
use App\Models\Trivia;
use App\Models\TriviaPreg;
use App\Models\TriviaRes;



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
}
