<?php



namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\SesionEv;
use App\Models\SesionVis;
use App\Models\SesionDudas;
use App\Models\SesionAnexos;
use App\Models\EvaluacionPreg;
use App\Models\EvaluacionRes;
use App\Models\Publicacion;
use App\Models\Clase;
use App\Models\Temporada;
use Illuminate\Support\Facades\DB;



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

        // Validar la solicitud
       $request->validate([
        'Imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Ajusta las reglas de validación según tus necesidades
        'ImagenFondo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Ajusta las reglas de validación según tus necesidades
        'ImagenInstructor' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Ajusta las reglas de validación según tus necesidades
        ]);

        // Guardar la imagen en la carpeta publicaciones
        
        
        
        
        
        if ($request->hasFile('Imagen')) {
            $imagen = $request->file('Imagen');
            $nombreImagen = 'sesion_'.time().'.'.$imagen->extension();
            $imagen->move(public_path('img/publicaciones'), $nombreImagen);
        }else{
            $nombreImagen = 'default.jpg';
        }
        if ($request->hasFile('ImagenFondo')) {
            $imagen_fondo = $request->file('ImagenFondo');
            $nombreImagenFondo = 'fondo_sesion_'.time().'.'.$imagen_fondo->extension();
            $imagen_fondo->move(public_path('img/publicaciones'), $nombreImagenFondo);
        }else{
            $nombreImagenFondo = 'default_fondo.jpg';
        }
        if ($request->hasFile('ImagenInstructor')) {
            $imagen_instructor = $request->file('ImagenInstructor');
            $nombreImagenInstructor = 'instructor_'.time().'.'.$imagen_instructor->extension();
            $imagen_instructor->move(public_path('img/publicaciones'), $nombreImagenInstructor);
        }else{
            $nombreImagenInstructor = 'default_instructor.jpg';
        }

        $sesion->id_cuenta = $request->IdCuenta;
        $sesion->id_temporada = $request->IdTemporada;
        $sesion->titulo = $request->Titulo;
        $sesion->descripcion = $request->Descripcion;
        $sesion->contenido = $request->Contenido;
        $sesion->nombre_instructor = $request->NombreInstructor;
        $sesion->puesto_instructor = $request->PuestoInstructor;
        $sesion->bio_instructor = $request->BioInstructor;
        $sesion->correo_instructor = $request->CorreoInstructor;
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
        $sesion->imagen = $nombreImagen;
         $sesion->imagen_fondo = $nombreImagenFondo;
         $sesion->imagen_instructor = $nombreImagenInstructor;
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
        $dudas = SesionDudas::where('id_sesion',$id)->get();
        $anexos = SesionAnexos::where('id_sesion',$id)->get();
        return view('admin/sesion_detalles', compact('sesion', 'preguntas'));
    }

     /**
     * Display the specified resource.
     */
    public function resultados(string $id)
    {
        //
        $sesion = SesionEv::find($id);

        $visualizaciones = DB::table('sesiones_visualizaciones')
            ->join('usuarios', 'sesiones_visualizaciones.id_usuario', '=', 'usuarios.id')
            ->where('sesiones_visualizaciones.id_sesion', '=', $id)
            ->select('sesiones_visualizaciones.id as id_visualizacion', 'sesiones_visualizaciones.*', 'usuarios.id as id_usuario', 'usuarios.*')
            ->orderBy('sesiones_visualizaciones.fecha_ultimo_video', 'desc')
            ->get();

        $respuestas = DB::table('evaluaciones_respuestas')
            ->join('evaluaciones_preguntas', 'evaluaciones_respuestas.id_pregunta', '=', 'evaluaciones_preguntas.id')
            ->join('usuarios', 'evaluaciones_respuestas.id_usuario', '=', 'usuarios.id')
            ->where('evaluaciones_respuestas.id_sesion', '=', $id)
            ->select('evaluaciones_respuestas.id as id_respuesta', 'evaluaciones_respuestas.*', 'evaluaciones_preguntas.id as id_pregunta', 'evaluaciones_preguntas.*', 'usuarios.id as id_usuario', 'usuarios.*')
            ->get();

        return view('admin/sesion_resultados', compact('sesion', 'visualizaciones', 'respuestas'));
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
        $sesion = SesionEv::find($id);
       // Validar la solicitud
       $request->validate([
        'Imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Ajusta las reglas de validación según tus necesidades
        'ImagenFondo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Ajusta las reglas de validación según tus necesidades
        'ImagenInstructor' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Ajusta las reglas de validación según tus necesidades
        ]);

        // Guardar la imagen en la carpeta publicaciones
        
        
        
        
        
        if ($request->hasFile('Imagen')) {
            $imagen = $request->file('Imagen');
            $nombreImagen = 'sesion_'.time().'.'.$imagen->extension();
            $imagen->move(base_path('../public_html/plsystem/img/publicaciones'), $nombreImagen);
        }else{
            $nombreImagen = $sesion->imagen;
        }
        if ($request->hasFile('ImagenFondo')) {
            $imagen_fondo = $request->file('ImagenFondo');
            $nombreImagenFondo = 'fondo_sesion_'.time().'.'.$imagen_fondo->extension();
            $imagen_fondo->move(base_path('../public_html/plsystem/img/publicaciones'), $nombreImagenFondo);
        }else{
            $nombreImagenFondo = $sesion->imagen_fondo;
        }
        if ($request->hasFile('ImagenInstructor')) {
            $imagen_instructor = $request->file('ImagenInstructor');
            $nombreImagenInstructor = 'instructor_'.time().'.'.$imagen_instructor->extension();
            $imagen_instructor->move(base_path('../public_html/plsystem/img/publicaciones'), $nombreImagenInstructor);
        }else{
            $nombreImagenInstructor = $sesion->imagen_instructor;
        }
        
        

        

         $sesion->id_cuenta = $request->IdCuenta;
         $sesion->id_temporada = $request->IdTemporada;
         $sesion->titulo = $request->Titulo;
         $sesion->descripcion = $request->Descripcion;
         $sesion->contenido = $request->Contenido;
         $sesion->nombre_instructor = $request->NombreInstructor;
         $sesion->puesto_instructor = $request->PuestoInstructor;
            $sesion->bio_instructor = $request->BioInstructor;
            $sesion->correo_instructor = $request->CorreoInstructor;
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
         $sesion->imagen = $nombreImagen;
         $sesion->imagen_fondo = $nombreImagenFondo;
         $sesion->imagen_instructor = $nombreImagenInstructor;
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

    public function destroy_visualizacion(string $id)
    {
        //
        $visualizacion = SesionVis::findOrFail($id);
        $id_sesion =  $visualizacion->id_sesion;
        
        $visualizacion->delete();
        return redirect()->route('sesiones.resultados', $id_sesion);
    }

    public function destroy_respuesta(string $id)
    {
        //
        $respuesta = EvaluacionRes::findOrFail($id);
        $id_sesion =  $respuesta->id_sesion;
        $respuesta->delete();
        return redirect()->route('sesiones.resultados', $id_sesion);
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
        EvaluacionRes::where('id_pregunta', $pregunta->id)->delete();


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

    public function respuestas_sesion_api(Request $request)
    {
        //
        $respuestas = EvaluacionRes::where('id_sesion',$request->input('id_sesion'))->where('id_usuario',$request->input('id_usuario'))->get();
        return response()->json($respuestas);
    }

    public function preguntas_sesion_api(Request $request)
    {
        //
        $preguntas = EvaluacionPreg::where('id_sesion',$request->input('id'))->get();
        return response()->json($preguntas);
    }
    public function dudas_sesion_api(Request $request)
    {
        //
        $dudas = DB::table('sesiones_dudas')
            ->join('usuarios', 'sesiones_dudas.id_usuario', '=', 'usuarios.id')
            ->where('sesiones_dudas.id_sesion', '=', $request->input('id_sesion'))
            ->select('sesiones_dudas.*', 'usuarios.*')
            ->orderBy('sesiones_dudas.created_at', 'desc')
            ->get();
        return response()->json($dudas);
    }
    public function anexos_sesion_api(Request $request)
    {
        //
        $anexos = SesionAnexos::where('id_sesion',$request->input('id_sesion'))->get();
        return response()->json($anexos);
    }

    public function checar_visualizacion_api(Request $request)
    {
        //
        $visualizacion = SesionVis::where('id_sesion', $request->input('id_sesion'))
        ->where('id_usuario', $request->input('id_usuario'))
        ->first();

        $resultado = ($visualizacion !== null);

        return $resultado ? 'true' : 'false';
    }

    public function registrar_visualizacion_api(Request $request)
    {
        $id_sesion = $request->input('id_sesion');
        $id_usuario = $request->input('id_usuario');
        $sesion = SesionEv::find($id_sesion);

        $fecha_publicacion = $sesion->fecha_publicacion;
        $fecha_limite_estreno = date('Y-m-d H:i:s', strtotime($fecha_publicacion.' +'.$sesion->horas_estreno.' hours'));
        $fecha_actual = date('Y-m-d H:i:s');

        if($fecha_actual<$fecha_limite_estreno){
            $puntaje = $sesion->visualizar_puntaje_estreno;
        }else{
            $puntaje = $sesion->visualizar_puntaje_evaluacion;
        }
        $visualizacion = SesionVis::where('id_sesion', $id_sesion)->where('id_usuario', $id_usuario)->first();

        // Verificar si la visualización existe
        if(!$visualizacion){
            // Si no existe, crear una nueva visualización
            $visualizacion = new SesionVis();
            $visualizacion->id_usuario = $id_usuario;
            $visualizacion->id_sesion = $id_sesion;
            $visualizacion->puntaje = $puntaje;
            $visualizacion->fecha_ultimo_video = date('Y-m-d H:i:s');

            $visualizacion->save();
            return('Almacenado');
        }else{
            return('No almacenado '.$id_sesion.' - '.$id_usuario);
        }

        
    }

    public function registrar_respuestas_evaluacion_api(Request $request)
    {
        $id_sesion = $request->input('id_sesion');
        $id_usuario = $request->input('id_usuario');
        $respuestas_json = $request->input('respuestas');
        $sesion = SesionEv::find($id_sesion);

        $fecha_publicacion = $sesion->fecha_publicacion;
        $fecha_limite_estreno = date('Y-m-d H:i:s', strtotime($fecha_publicacion.' +'.$sesion->horas_estreno.' hours'));
        $fecha_actual = date('Y-m-d H:i:s');

        if($fecha_actual<$fecha_limite_estreno){
            $puntaje_preguntas = $sesion->preguntas_puntaje_estreno;
        }else{
            $puntaje_preguntas = $sesion->preguntas_puntaje_evaluacion;
        }
        //$respuestas_array = json_decode($respuestas_json, true);
        $hay_respuestas = EvaluacionRes::where('id_sesion', $id_sesion)->where('id_usuario', $id_usuario)->first();
        if(!$hay_respuestas){
            foreach ($respuestas_json as $pregunta=>$respuesta) {
                $registro_respuesta = EvaluacionRes::where('id_sesion', $id_sesion)->where('id_usuario', $id_usuario)->where('id_pregunta', $pregunta)->first();
                // Verificar si la visualización existe
                if(!$registro_respuesta){
                    $pregunta_reg = EvaluacionPreg::find($pregunta);
                    switch ($respuesta) {
                        case 'A':
                            $respuesta_correcta = $pregunta_reg->resultado_a;
                            break;
                        case 'B':
                            $respuesta_correcta = $pregunta_reg->resultado_b;
                            break;
                        case 'C':
                            $respuesta_correcta = $pregunta_reg->resultado_c;
                            break;
                        case 'D':
                            $respuesta_correcta = $pregunta_reg->resultado_d;
                            break;
                        default:
                        $respuesta_correcta = 'incorrecto';
                            break;
                    }

                    if($respuesta_correcta=='correcto'){
                        $puntaje = $puntaje_preguntas;
                    }else{
                        $puntaje = 0;
                    }
                    // Si no existe, crear una nueva visualización
                    $registro_respuesta = new EvaluacionRes();
                    $registro_respuesta->id_usuario = $id_usuario;
                    $registro_respuesta->id_sesion = $id_sesion;
                    $registro_respuesta->id_pregunta = $pregunta;
                    $registro_respuesta->respuesta_usuario = $respuesta;
                    $registro_respuesta->respuesta_correcta = $respuesta_correcta;
                    $registro_respuesta->puntaje = $puntaje;
                    $registro_respuesta->fecha_registro = date('Y-m-d H:i:s');

                    $registro_respuesta->save();
                }
            }
        }

        
    }

    public function registrar_duda_api(Request $request)
    {
        $id_sesion = $request->input('id_sesion');
        $id_usuario = $request->input('id_usuario');
        $duda_texto = $request->input('duda');

        $duda = new SesionDudas();
        $duda->id_usuario = $id_usuario;
        $duda->id_sesion = $id_sesion;
        $duda->duda = $duda_texto;

        $duda->save();

        
    }

    
}
