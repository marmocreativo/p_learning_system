<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\SesionEv;
use App\Models\SesionVis;
use App\Models\EvaluacionPreg;
use App\Models\EvaluacionRes;
use App\Models\Publicacion;
use App\Models\Clase;
use App\Models\Temporada;


class SesionesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $id_temporada = $request->input('id_temporada');
        $sesiones = SesionEv::where('id_temporada', $id_temporada)->paginate();
        return view('admin/sesion_lista', compact('sesiones'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        //
        $temporada = Temporada::find($request->input('id_temporada'));
        return view('admin/sesion_form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $sesion = new SesionEv();

        $sesion->id_cuenta = $request->IdCuenta;
        $sesion->id_temporada = $request->IdTemporada;
        $sesion->titulo = $request->Titulo;
        $sesion->descripcion = $request->Descripcion;
        $sesion->contenido = $request->Contenido;
        $sesion->nombre_instructor = $request->NombreInstructor;
        $sesion->duracion_aproximada = $request->DuracionAproximada;
        $sesion->video_1 = $request->IdVideo1;
        $sesion->video_2 = $request->IdVideo2;
        $sesion->video_3 = $request->IdVideo3;
        $sesion->video_4 = $request->IdVideo4;
        $sesion->video_5 = $request->IdVideo5;
        $sesion->titulo_video_1 = $request->TituloVideo1;
        $sesion->titulo_video_2 = $request->TituloVideo2;
        $sesion->titulo_video_3 = $request->TituloVideo3;
        $sesion->titulo_video_4 = $request->TituloVideo4;
        $sesion->titulo_video_5 = $request->TituloVideo5;
        $sesion->fecha_publicacion = date('Y-m-d H:i:s', strtotime($request->FechaPublicacion.' '.$request->HoraPublicacion));
        $sesion->cantidad_preguntas_evaluacion = $request->CantidadPreguntasEvaluacion;
        $sesion->ordenar_preguntas_evaluacion = $request->OrdenarPreguntasEvaluacion;
        $sesion->visualizar_puntaje_normal = $request->VisualizarPuntajeNormal;
        $sesion->visualizar_puntaje_estreno = $request->VisualizarPuntajeEstreno;
        $sesion->preguntas_puntaje_normal = $request->PreguntasPuntajeNormal;
        $sesion->preguntas_puntaje_estreno = $request->PreguntasPuntajeEstreno;
        $sesion->horas_estreno = $request->HorasEstreno;
        $sesion->evaluacion_obligatoria = $request->EvaluacionObligatoria;
        $sesion->imagen = 'default.jpg';
        $sesion->imagen_fondo = 'fondo_default.jpg';
        $sesion->estado = $request->Estado;

        $sesion->save();

        return redirect()->route('sesiones.show', $sesion->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $sesion = SesionEv::find($id);
        $preguntas = EvaluacionPreg::where('id_sesion',$id)->get();
        return view('admin/sesion_detalles', compact('sesion', 'preguntas'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $sesion = SesionEv::find($id);
        $clases = Clase::where('elementos','publicaciones')->get();
        return view('admin/sesion_form_actualizar', compact('sesion', 'clases'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //

         //
         $sesion = SesionEv::find($id);

         $sesion->id_cuenta = $request->IdCuenta;
         $sesion->id_temporada = $request->IdTemporada;
         $sesion->titulo = $request->Titulo;
         $sesion->descripcion = $request->Descripcion;
         $sesion->contenido = $request->Contenido;
         $sesion->nombre_instructor = $request->NombreInstructor;
         $sesion->duracion_aproximada = $request->DuracionAproximada;
         $sesion->video_1 = $request->IdVideo1;
         $sesion->video_2 = $request->IdVideo2;
         $sesion->video_3 = $request->IdVideo3;
         $sesion->video_4 = $request->IdVideo4;
         $sesion->video_5 = $request->IdVideo5;
         $sesion->titulo_video_1 = $request->TituloVideo1;
         $sesion->titulo_video_2 = $request->TituloVideo2;
         $sesion->titulo_video_3 = $request->TituloVideo3;
         $sesion->titulo_video_4 = $request->TituloVideo4;
         $sesion->titulo_video_5 = $request->TituloVideo5;
         $sesion->fecha_publicacion = date('Y-m-d H:i:s', strtotime($request->FechaPublicacion.' '.$request->HoraPublicacion));
         $sesion->cantidad_preguntas_evaluacion = $request->CantidadPreguntasEvaluacion;
         $sesion->ordenar_preguntas_evaluacion = $request->OrdenarPreguntasEvaluacion;
         $sesion->visualizar_puntaje_normal = $request->VisualizarPuntajeNormal;
         $sesion->visualizar_puntaje_estreno = $request->VisualizarPuntajeEstreno;
         $sesion->preguntas_puntaje_normal = $request->PreguntasPuntajeNormal;
         $sesion->preguntas_puntaje_estreno = $request->PreguntasPuntajeEstreno;
         $sesion->horas_estreno = $request->HorasEstreno;
         $sesion->evaluacion_obligatoria = $request->EvaluacionObligatoria;
         $sesion->imagen = 'default.jpg';
         $sesion->imagen_fondo = 'fondo_default.jpg';
         $sesion->estado = $request->Estado;
 
         $sesion->save();

         return redirect()->route('sesiones.show', $sesion->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $sesion = SesionEv::findOrFail($id);
        $id_temporada = $sesion->id_temporada;
        // Buscar y eliminar registros relacionados en otras tablas
        SesionVis::where('id_sesion', $id)->delete();
        EvaluacionPreg::where('id_sesion', $id)->delete();
        EvaluacionRes::where('id_sesion', $id)->delete();


        $sesion->delete();
        return redirect()->route('sesiones', ['id_temporada'=>$id_temporada]);
    }

    /**
     * Funciones evaluaciones
     */

     public function store_pregunta(Request $request)
    {
        //
        $pregunta = new EvaluacionPreg();

        $pregunta->id_sesion = $request->IdSesion;
        $pregunta->pregunta = $request->Pregunta;
        $pregunta->respuesta_a = $request->RespuestaA;
        $pregunta->respuesta_b = $request->RespuestaB;
        $pregunta->respuesta_c = $request->RespuestaC;
        $pregunta->respuesta_d = $request->RespuestaD;
        $pregunta->resultado_a = $request->ResultadoA;
        $pregunta->resultado_b = $request->ResultadoB;
        $pregunta->resultado_c = $request->ResultadoC;
        $pregunta->resultado_d = $request->ResultadoD;
        $pregunta->orden = 0;
        

        $pregunta->save();

        return redirect()->route('sesiones.show', $request->IdSesion);
    }

    public function update_pregunta(Request $request, string $id)
    {
        //

         //
         $pregunta = EvaluacionPreg::find($id);

        $pregunta->id_sesion = $request->IdSesion;
        $pregunta->pregunta = $request->Pregunta;
        $pregunta->respuesta_a = $request->RespuestaA;
        $pregunta->respuesta_b = $request->RespuestaB;
        $pregunta->respuesta_c = $request->RespuestaC;
        $pregunta->respuesta_d = $request->RespuestaD;
        $pregunta->resultado_a = $request->ResultadoA;
        $pregunta->resultado_b = $request->ResultadoB;
        $pregunta->resultado_c = $request->ResultadoC;
        $pregunta->resultado_d = $request->ResultadoD;
 
         $pregunta->save();

         return redirect()->route('sesiones.show', $pregunta->id_sesion);
    }

    public function destroy_pregunta(string $id)
    {
        //
        $pregunta = EvaluacionPreg::findOrFail($id);
        $id_sesion =  $pregunta->id_sesion;
        // Buscar y eliminar registros relacionados en otras tablas
        EvaluacionRes::where('id_evaluacion', $id)->delete();


        $pregunta->delete();
        return redirect()->route('sesiones.show', $id_sesion);
    }


    /**
     * Funciones API
     */
    public function lista_api (Request $request)
    {
        //
        $id_temporada = $request->input('id_temporada');
        $sesiones = SesionEv::where('id_temporada', $id_temporada)->get();
        return response()->json($sesiones);
    }
    public function lista_pendientes_api(Request $request)
{
    // variables
    $id_temporada = $request->input('id_temporada');
    $fecha_actual = now()->format('Y-m-d H:i:s');
    
    // consulta
    $sesiones = SesionEv::where('id_temporada', $id_temporada)
                        ->whereDate('fecha_publicacion', '>', $fecha_actual)
                        ->limit(2) // Limitar a dos resultados
                        ->get();
    
    return response()->json($sesiones);
}

    public function datos_sesion_api(Request $request)
    {
        //
        $sesion = SesionEv::find($request->input('id'));
        return response()->json($sesion);
    }
}
