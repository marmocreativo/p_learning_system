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
use App\Models\NotificacionUsuario;
use Illuminate\Support\Facades\DB;

use App\Mail\GanadorTrivia;
use App\Mail\DireccionTrivia;
use Illuminate\Support\Facades\Mail;

use App\Exports\ReporteTriviaExport;
use Maatwebsite\Excel\Facades\Excel;


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
        $temporada = Temporada::find($request->input('id_temporada'));
        $trivias = Trivia::where('id_temporada', $id_temporada)->paginate();
        return view('admin/trivia_lista', compact('trivias', 'temporada'));
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
         $trivia->cantidad_preguntas = $request->CantidadPreguntas;
         $trivia->orden = $request->Orden;
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
        $users = User::all();
        $distribuidores = Distribuidor::all();
        $suscripciones = UsuariosSuscripciones::where('id_temporada', $trivia->id_temporada)->get();
        $usuarios = array();
        foreach ($users as $usr) {
            $userObj = new \stdClass();
            $userObj->nombre = $usr->nombre;
            $userObj->apellidos = $usr->apellidos;
            $userObj->email = $usr->email;
        
            $usuarios[$usr->id] = $userObj;
        }
        $participantes = TriviaRes::select('id_usuario', DB::raw('COUNT(*) as total'))
        ->where('id_trivia', $id)
        ->groupBy('id_usuario')
        ->get();
        $respuestas = TriviaRes::where('id_trivia',$id)->get();
        $ganadores = TriviaGanador::where('id_trivia',$id)->get();
        $numero_participantes = TriviaRes::where('id_trivia',$id)->distinct('id_usuario')->count();
        $numero_ganadores = TriviaGanador::where('id_trivia',$id)->distinct('id_usuario')->count();
        return view('admin/trivia_resultados', 
            compact(
                'trivia',
                'distribuidores',
                'suscripciones',
                'usuarios',
                'participantes',
                'preguntas',
                'respuestas',
                'ganadores',
                'numero_participantes',
                'numero_ganadores'));
    }

    public function resultados_excel (Request $request)
    {
        return Excel::download(new ReporteTriviaExport($request), 'reporte_trivia.xlsx');
        
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
         $trivia->cantidad_preguntas = $request->CantidadPreguntas;
         $trivia->orden = $request->Orden;
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

    public function destroy_participacion(Request $request)
    {
        $id_trivia = $request->IdTrivia;
        $id_usuario = $request->IdUsuario;

        // Eliminar ganadores directamente
        TriviaGanador::where('id_usuario', $id_usuario)
                    ->where('id_trivia', $id_trivia)
                    ->delete();

        // Eliminar respuestas directamente
        TriviaRes::where('id_usuario', $id_usuario)
                ->where('id_trivia', $id_trivia)
                ->delete();

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

         $fecha_hoy = Carbon::now();
         $trivia = Trivia::where('id_temporada', $id_temporada)
                        ->where('fecha_publicacion', '<=', $fecha_hoy)
                        ->where('fecha_vigencia', '>=', $fecha_hoy)
                       ->first();
        if($trivia){
            $cantidad_preguntas = $trivia->cantidad_preguntas;
            $orden = $trivia->orden;

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
                'nivel' => $suscripcion->nivel,
                'preguntas' => $preguntas,
                'respuestas' => $respuestas,
                'premios' => $ganadores,
                'ganador' => $soy_ganador,
                'premio_ganador' => $ganador,
                'mis_premios' => $mis_premios,
                'respuestas_historico' => $respuestas_historico
            ];

            return response()->json($completo);

        }else{
            return null;
        }
        
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
    $usuario = User::find($id_usuario);
    $trivia = Trivia::find($id_trivia);
    $temporada = Temporada::find($trivia->id_temporada);
    $suscripcion = UsuariosSuscripciones::where('id_usuario', $id_usuario)->where('id_temporada', $trivia->id_temporada)->first();
    $distribuidor = Distribuidor::where('id', $suscripcion->id_distribuidor)->first();

    $respuestas_json = $request->input('respuestas');

    if (!is_array($respuestas_json)) {
        return response()->json(['success' => false, 'message' => 'Las respuestas deben ser un array válido']);
    }

    $cantidad_preguntas = $trivia->cantidad_preguntas;
    $hay_respuestas = TriviaRes::where('id_trivia', $id_trivia)->where('id_usuario', $id_usuario)->exists();
    $hay_ganador = TriviaGanador::where('id_trivia', $id_trivia)->where('id_distribuidor', $distribuidor->id)->exists();

    // **Validar que aún no existan respuestas**
    if ($hay_respuestas) {
        return response()->json(['success' => false, 'message' => 'Ya hay respuestas registradas para este usuario']);
    }

    // **Validar cantidad exacta de respuestas**
    if (count($respuestas_json) !== $cantidad_preguntas) {
        return response()->json(['success' => false, 'message' => 'Número incorrecto de respuestas']);
    }

    $guardadas = true;
    $todas_correctas = true;

    foreach ($respuestas_json as $pregunta_id => $respuesta_usuario) {
        $pregunta = TriviaPreg::find($pregunta_id);

        if (!$pregunta) {
            return response()->json(['success' => false, 'message' => 'Pregunta no encontrada']);
        }

        $respuesta_correcta = ($respuesta_usuario == $pregunta->respuesta_correcta) ? 'correcto' : 'incorrecto';
        $puntaje = ($respuesta_correcta === 'correcto') ? $trivia->puntaje : 0;

        if ($respuesta_correcta === 'incorrecto') {
            $todas_correctas = false;
        }

        // Guardar la respuesta
        $nueva_respuesta = new TriviaRes();
        $nueva_respuesta->id_usuario = $id_usuario;
        $nueva_respuesta->id_trivia = $id_trivia;
        $nueva_respuesta->id_temporada = $temporada->id;
        $nueva_respuesta->id_distribuidor = $suscripcion->id_distribuidor ?? null;
        $nueva_respuesta->id_pregunta = $pregunta_id;
        $nueva_respuesta->puntaje = $puntaje;
        $nueva_respuesta->respuesta_usuario = $respuesta_usuario;
        $nueva_respuesta->respuesta_correcta = $respuesta_correcta;
        $nueva_respuesta->fecha_registro = now();

        if (!$nueva_respuesta->save()) {
            $guardadas = false;
            break;
        }
    }

    // **Si todas las respuestas son correctas y no hay un ganador previo, registrar ganador**
    if (!$hay_ganador && $todas_correctas) {
        $nuevo_ganador = new TriviaGanador();
        $nuevo_ganador->id_trivia = $id_trivia;
        $nuevo_ganador->id_temporada = $temporada->id;
        $nuevo_ganador->id_distribuidor = $suscripcion->id_distribuidor ?? null;
        $nuevo_ganador->id_usuario = $id_usuario;
        $nuevo_ganador->fecha_registro = now();
        $nuevo_ganador->save();

        // Creo la notificación
        $notificacion = new NotificacionUsuario();
        $notificacion->id_cuenta = $trivia->id_cuenta;
        $notificacion->id_temporada = $trivia->id_temporada;
        $notificacion->id_usuario = $id_usuario;
        $notificacion->tipo = 'notificacion';
        $notificacion->texto = '<p>¡Fuiste el mejor participante de tu compañía en la <b>Trivia Mensual iLovePanduit!</b>...</p>';
        $notificacion->enlace = '#';
        $notificacion->save();


        // **Enviar correo al ganador**
        $data = [
            'titulo' => 'Ganador trivia ' . $distribuidor->region,
            'boton_texto' => '',
            'boton_enlace' => '#',
            'contenido' => $distribuidor->region == 'RoLA'
                ? '<p>¡Fuiste el mejor participante de tu compañía en la <b>Trivia Mensual iLovePanduit!</b>...</p>'
                : '<p><b>En la Trivia mensual iLovePanduit, ¡fuiste el mejor participante de tu compañía!</b>...</p>'
        ];

        Mail::to($usuario->email)->send(new GanadorTrivia($data));
    }

    return response()->json([
        'success' => $guardadas,
        'message' => $guardadas ? 'Se guardaron todas las preguntas' : 'Hubo un error al guardar las respuestas'
    ]);
}


    public function direccion_trivia_api(Request $request)
    {
        $id_ganador = $request->input('id_premio');
        $ganador = TriviaGanador::find($id_ganador);
        $trivia = Trivia::find($ganador->id_trivia);

        $ganador->direccion_nombre = $request->input('nombre');
        $ganador->direccion_calle = $request->input('calle');
        $ganador->direccion_numero = $request->input('numero');
        $ganador->direccion_numeroint = $request->input('numeroint');
        $ganador->direccion_colonia = $request->input('colonia');
        $ganador->direccion_delegacion = $request->input('delegacion');
        $ganador->direccion_ciudad = $request->input('ciudad');
        $ganador->direccion_codigo_postal = $request->input('codigoPostal');
        $ganador->direccion_horario = $request->input('horario');
        $ganador->direccion_referencia = $request->input('referencia');
        $ganador->direccion_telefono = $request->input('telefono');
        $ganador->direccion_notas = $request->input('notas');

        $ganador->save();

        return('Guardado');
        
        
        
    }

    public function confirmar_direccion_trivia_api(Request $request)
    {
        $id_ganador = $request->input('id_premio');
        $ganador = TriviaGanador::find($id_ganador);
        $trivia = Trivia::find($ganador->id_trivia);
        $ganador->direccion_confirmada = 'si';

        $ganador->save();

        $direccion = '';
        $direccion .= '<p><b>Recibe</b>'.$ganador->direccion_nombre.'</p>';
        $direccion .= '<p><b>Calle</b>'.$ganador->direccion_calle.'</p>';
        $direccion .= '<p><b>Número Ext</b>'.$ganador->direccion_numero.'</p>';
        $direccion .= '<p><b>Número Int</b>'.$ganador->direccion_numeroint.'</p>';
        $direccion .= '<p><b>Colonia</b>'.$ganador->direccion_colonia.'</p>';
        $direccion .= '<p><b>Delegación</b>'.$ganador->direccion_delegacion.'</p>';
        $direccion .= '<p><b>Ciudad</b>'.$ganador->direccion_ciudad.'</p>';
        $direccion .= '<p><b>Código Postal</b>'.$ganador->direccion_codigo_postal.'</p>';
        $direccion .= '<p><b>Horario</b>'.$ganador->direccion_horario.'</p>';
        $direccion .= '<p><b>Referencia</b>'.$ganador->direccion_referencia.'</p>';
        $direccion .= '<p><b>Notas</b>'.$ganador->direccion_notas.'</p>';

        $data = [
            'titulo' => 'Dirección del ganador de la trivia '.$trivia->titulo,
            'contenido' => $direccion,
            'boton_texto' => '',
            'boton_enlace' => '#'
        ];
        Mail::to('marmocreativo@gmail.com')->send(new DireccionTrivia($data));

        return('Guardado');
        
        
        
    }

    /**
     * Funciones API 2025
     */

     public function datos_trivia_2025_api(Request $request)
     {
         //Variables
         $fecha_actual = Carbon::now();
         $id_temporada = $request->input('id_temporada');
         $id_usuario = $request->input('id_usuario');
         $suscripcion = UsuariosSuscripciones::where('id_usuario', $id_usuario)->where('id_temporada', $id_temporada)->first();
         $distribuidor = Distribuidor::where('id', $suscripcion->id_distribuidor)->first();
         // consulta

         $fecha_hoy = Carbon::now();
         $trivia = Trivia::where('id_temporada', $id_temporada)
                        ->where('fecha_publicacion', '<=', $fecha_hoy)
                        ->where('fecha_vigencia', '>=', $fecha_hoy)
                       ->first();
        if($trivia){
            $cantidad_preguntas = $trivia->cantidad_preguntas;
            $orden = $trivia->orden;

            // Preparo la consulta de las preguntas
            $query = TriviaPreg::where('id_trivia', $trivia->id);
            // Checo el orden
            if ($orden === 'ordenado') {
                $query->orderBy('id', 'asc');
            }
            // Si el orden es "random", ordenar aleatoriamente utilizando el ID del usuario como semilla
            if ($orden === 'random') {
                $query->inRandomOrder($id_usuario);
            }

            // Limitar el número de preguntas a la cantidad especificada
            $preguntas = $query->limit($cantidad_preguntas)->get();

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
                'nivel' => $suscripcion->nivel,
                'preguntas' => $preguntas,
                'respuestas' => $respuestas,
                'premios' => $ganadores,
                'ganador' => $soy_ganador,
                'premio_ganador' => $ganador,
                'mis_premios' => $mis_premios,
                'respuestas_historico' => $respuestas_historico
            ];

            return response()->json($completo);

        }else{
            return null;
        }
        
     }
}
