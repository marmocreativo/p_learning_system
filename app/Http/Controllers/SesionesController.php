<?php



namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\Distribuidor;
use App\Models\SesionEv;
use App\Models\SesionVis;
use App\Models\SesionDudas;
use App\Models\SesionAnexos;
use App\Models\EvaluacionPreg;
use App\Models\EvaluacionRes;
use App\Models\Publicacion;
use App\Models\Clase;
use App\Models\Cuenta;
use App\Models\Temporada;
use App\Models\User;
use App\Models\UsuariosSuscripciones;
use App\Models\AccionesUsuarios;
use Illuminate\Support\Facades\DB;

use App\Exports\ReporteSesionExport;
use App\Exports\ReporteCompletadasSesionExport;
use Maatwebsite\Excel\Facades\Excel;



class SesionesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $id_temporada = $request->input('id_temporada');
        $temporada = Temporada::find($request->input('id_temporada'));
        $sesiones = SesionEv::where('id_temporada', $id_temporada)->paginate();
        foreach($sesiones as $sesion){
            $preguntas = SesionDudas::where('id_sesion', $sesion->id)->count();
            $preguntas_sin_resolver = SesionDudas::where('id_sesion', $sesion->id)->where('respuesta','')->count();
            $sesion->setAttribute('preguntas', $preguntas);
            $sesion->setAttribute('preguntas_sin_resolver', $preguntas_sin_resolver);
        }

        return view('admin/sesion_lista', compact('sesiones', 'temporada'));
    }

    public function completadas(Request $request)
    {
        //
        $id_temporada = $request->input('id_temporada');
        $temporada = Temporada::find($request->input('id_temporada'));
        $sesiones_actuales = SesionEv::where('id_temporada', $id_temporada)->get();
        $sesiones_anteriores = SesionEv::where('id_temporada', $temporada->temporada_anterior)->get();
        $suscripciones = UsuariosSuscripciones::where('id_temporada', $id_temporada)->get();
        $usuarios = User::all();

        foreach($suscripciones as $suscripcion){
            //reviso que haya completado sesiones
            $completo_sesiones_actuales = true;
            $completo_sesiones_anteriores = true;
            $completado_2024 = false;
            $total_2024 = 0;

            if($suscripcion->temporada_completa == 'no'){
                foreach($sesiones_actuales as $sesion_actual){
                    $visto = SesionVis::where('id_sesion', $sesion_actual->id)->where('id_usuario', $suscripcion->id_usuario)->first();
                    if(empty($visto)){
                        $completo_sesiones_actuales = false;
                    }
                }
                if($completo_sesiones_actuales){
                    $suscripcion->temporada_completa = 'si';
                }
            }

            if($suscripcion->champions_a == 'no'){
                foreach($sesiones_anteriores as $sesion_anterior){
                    $visto = SesionVis::where('id_sesion', $sesion_anterior->id)->where('id_usuario', $suscripcion->id_usuario)->first();
                    if(empty($visto)){
                        $completo_sesiones_anteriores = false;
                    }else{
                        if (Carbon::parse($visto->fecha_ultimo_video)->year == 2024) {
                            $completado_2024 = true;
                            $total_2024++;
                        }
                    }
                }
                if($completo_sesiones_anteriores){
                    $suscripcion->champions_a = 'si';
                }
            }
            
            $suscripcion->save();
            $suscripcion->completado_2024 =$completado_2024;
        }

        return view('admin/sesiones_completadas', compact('suscripciones', 'temporada', 'usuarios', 'total_2024'));
    }

    public function reporte_completadas (Request $request, string $id)
    {
        $id_temporada = $id;
        $temporada = Temporada::find($id);
        $hoy = date('Y-m-d H:i:s');

        $sesiones_actuales = SesionEv::where('id_temporada', $id_temporada)->get();
        $sesiones_anteriores = SesionEv::where('id_temporada', $temporada->temporada_anterior)->get();

        $visualizaciones_actuales = SesionVis::where('id_temporada', $id_temporada)->get();
        $visualizaciones_anteriores = SesionVis::where('id_temporada', $temporada->temporada_anterior)->get();
        
        $region = $request->input('region');
        $distribuidor = $request->input('distribuidor');
        $distribuidores = Distribuidor::all();
        if($region!='todas'){
            $distribuidores = Distribuidor::where('region',$region)->get();
        }

        $usuarios_suscritos = DB::table('usuarios_suscripciones')
            ->join('usuarios', 'usuarios_suscripciones.id_usuario', '=', 'usuarios.id')
            ->join('distribuidores', 'usuarios_suscripciones.id_distribuidor', '=', 'distribuidores.id')
            ->where('usuarios_suscripciones.id_temporada', '=', $id)
            ->when($region !== 'todas', function ($query) use ($region) {
                return $query->where('distribuidores.region', $region);
            })
            // Añade la condición de distribuidor si no es 0
            ->when($distribuidor != 0, function ($query) use ($distribuidor) {
                return $query->where('distribuidores.id', $distribuidor);
            })
            ->select(
                'usuarios.id as id_usuario',
                'usuarios.nombre as nombre',
                'usuarios.apellidos as apellidos',
                'usuarios.email as email',
                'distribuidores.region as region',
                'distribuidores.nombre as distribuidor',
            )
            ->get();
            foreach ($usuarios_suscritos as $usuario) {
                // Contar las visualizaciones en la temporada actual
                $v_act_count = SesionVis::where('id_temporada', $id_temporada)
                                        ->where('id_usuario', $usuario->id_usuario)
                                        ->count();
            
                // Contar las visualizaciones en la temporada anterior
                $v_ant_count = SesionVis::where('id_temporada', $temporada->temporada_anterior)
                                        ->where('id_usuario', $usuario->id_usuario)
                                        ->count();
            
                // Asignar los valores booleanos basados en si el conteo es mayor que 0
                $usuario->vis_actual = $v_act_count > 0;
                $usuario->vis_anterior = $v_ant_count > 0;
            
                // Opcional: puedes guardar los conteos específicos como atributos adicionales
                $usuario->vis_actual_count = $v_act_count;
                $usuario->vis_anterior_count = $v_ant_count;
            }
            
        return view('admin/sesiones_reporte', compact('temporada',
                                                        'sesiones_actuales',
                                                        'sesiones_anteriores',
                                                        'visualizaciones_actuales',
                                                        'visualizaciones_anteriores',
                                                        'usuarios_suscritos',
                                                        'distribuidores'
                                                    ));
    }

    public function reporte_completadas_excel (Request $request)
    {
        return Excel::download(new ReporteCompletadasSesionExport($request), 'reporte_sesion_completadas.xlsx');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        //
        $temporada = Temporada::find($request->input('id_temporada'));
        $cuenta = Cuenta::find($temporada->id_cuenta);
        return view('admin/sesion_form', compact('temporada', 'cuenta'));
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
            $imagen->move(base_path('../public_html/system.panduitlatam.com/img/publicaciones'), $nombreImagen);
        }else{
            $nombreImagen = 'default.jpg';
        }
        if ($request->hasFile('ImagenFondo')) {
            $imagen_fondo = $request->file('ImagenFondo');
            $nombreImagenFondo = 'fondo_sesion_'.time().'.'.$imagen_fondo->extension();
            $imagen_fondo->move(base_path('../public_html/system.panduitlatam.com/img/publicaciones'), $nombreImagen);
        }else{
            $nombreImagenFondo = 'default_fondo.jpg';
        }
        if ($request->hasFile('ImagenInstructor')) {
            $imagen_instructor = $request->file('ImagenInstructor');
            $nombreImagenInstructor = 'instructor_'.time().'.'.$imagen_instructor->extension();
            $imagen_instructor->move(base_path('../public_html/system.panduitlatam.com/img/publicaciones'), $nombreImagen);
        }else{
            $nombreImagenInstructor = 'default_instructor.jpg';
        }

        $sesion->id_cuenta = $request->IdCuenta;
        $sesion->id_temporada = $request->IdTemporada;
        $sesion->titulo = $request->Titulo;
        $sesion->url = $request->Url;
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
        $sesion->puntaje_video_1_estreno = $request->PuntajeVideo1Estreno;
        $sesion->puntaje_video_2_estreno = $request->PuntajeVideo2Estreno;
        $sesion->puntaje_video_3_estreno = $request->PuntajeVideo3Estreno;
        $sesion->puntaje_video_4_estreno = $request->PuntajeVideo4Estreno;
        $sesion->puntaje_video_5_estreno = $request->PuntajeVideo5Estreno;
        $sesion->puntaje_video_1_normal = $request->PuntajeVideo1Normal;
        $sesion->puntaje_video_2_normal = $request->PuntajeVideo2Normal;
        $sesion->puntaje_video_3_normal = $request->PuntajeVideo3Normal;
        $sesion->puntaje_video_4_normal = $request->PuntajeVideo4Normal;
        $sesion->puntaje_video_5_normal = $request->PuntajeVideo5Normal;
        $sesion->fecha_video_1 = (!empty($request->FechaVideo1) && !empty($request->HoraVideo1)) 
            ? date('Y-m-d H:i:s', strtotime($request->FechaVideo1 . ' ' . $request->HoraVideo1)) 
            : null;

        $sesion->fecha_video_2 = (!empty($request->FechaVideo2) && !empty($request->HoraVideo2)) 
            ? date('Y-m-d H:i:s', strtotime($request->FechaVideo2 . ' ' . $request->HoraVideo2)) 
            : null;

        $sesion->fecha_video_3 = (!empty($request->FechaVideo3) && !empty($request->HoraVideo3)) 
            ? date('Y-m-d H:i:s', strtotime($request->FechaVideo3 . ' ' . $request->HoraVideo3)) 
            : null;

        $sesion->fecha_video_4 = (!empty($request->FechaVideo4) && !empty($request->HoraVideo4)) 
            ? date('Y-m-d H:i:s', strtotime($request->FechaVideo4 . ' ' . $request->HoraVideo4)) 
            : null;

        $sesion->fecha_video_5 = (!empty($request->FechaVideo5) && !empty($request->HoraVideo5)) 
            ? date('Y-m-d H:i:s', strtotime($request->FechaVideo5 . ' ' . $request->HoraVideo5)) 
            : null;
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
    public function reparar(string $id)
    {
        //
        $sesion = SesionEv::find($id);
        $visualizaciones = SesionVis::where('id_sesion',$id)->get();
        

        echo '<table border="1">';
        
        foreach($visualizaciones as $visualizacion){
            
            echo '<tr>';
            echo '<td>'.$sesion->titulo.'</td>';
            echo '<td>'.$sesion->fecha_publicacion.'</td>';
            echo '<td>'.$sesion->horas_estreno.'</td>';
            echo '<td>'.$visualizacion->fecha_ultimo_video.'</td>';
            $fecha_publicacion = new \DateTime($sesion->fecha_publicacion);
            $horas_estreno = new \DateInterval('PT' . $sesion->horas_estreno . 'H');
            $fecha_estreno = (clone $fecha_publicacion)->add($horas_estreno);
            $fecha_ultimo_video = new \DateTime($visualizacion->fecha_ultimo_video);

            // Verificar si visto en estreno
            if ($fecha_ultimo_video <= $fecha_estreno) {
                echo '<td style="color:green">En tiempo</td>';
                echo '<td>'.$sesion->visualizar_puntaje_estreno.'</td>';
                if($sesion->visualizar_puntaje_estreno == $visualizacion->puntaje){
                    echo '<td style="color:green">'.$visualizacion->puntaje.'</td>'; 
                }else{
                    echo '<td style="color:red">'.$visualizacion->puntaje.' (Actualizar)</td>'; 
                    $visualizacion->puntaje = $sesion->visualizar_puntaje_estreno;
                    $visualizacion->save();
                }
                 
            }else{
                echo '<td style="color:red">Fuera de estreno</td>';  
                echo '<td>'.$sesion->visualizar_puntaje_normal.'</td>';
                if($sesion->visualizar_puntaje_normal == $visualizacion->puntaje){
                    echo '<td style="color:green">'.$visualizacion->puntaje.'</td>'; 
                }else{
                    echo '<td style="color:red">'.$visualizacion->puntaje.' (Actualizar)</td>'; 
                    $visualizacion->puntaje = $sesion->visualizar_puntaje_normal;
                    $visualizacion->save();
                }
            }
            echo '<td>';
                echo '<table>';
                    
                        $evaluaciones = EvaluacionRes::where('id_sesion',$id)->where('id_usuario',$visualizacion->id_usuario)->get();
                        foreach($evaluaciones as $evaluacion){
                            echo '<tr>';
                            echo '<td>'.$evaluacion->fecha_registro.'</td>';
                            $fecha_publicacion = new \DateTime($sesion->fecha_publicacion);
                            $horas_estreno = new \DateInterval('PT' . $sesion->horas_estreno . 'H');
                            $fecha_estreno = (clone $fecha_publicacion)->add($horas_estreno);
                            $fecha_respuesta = new \DateTime($evaluacion->fecha_registro);
                
                            // Verificar si visto en estreno
                            if ($fecha_respuesta <= $fecha_estreno) {
                                echo '<td style="color:green">En tiempo</td>';
                                echo '<td>'.$evaluacion->respuesta_usuario.'</td>';
                                if($evaluacion->respuesta_correcta == 'correcto'){
                                    
                                    if($evaluacion->puntaje == $sesion->preguntas_puntaje_estreno){
                                        echo '<td style="color:green">'.$evaluacion->puntaje.'</td>'; 
                                    }else{
                                        echo '<td style="color:red">'.$evaluacion->puntaje.' (Corregido)</td>'; 
                                        $evaluacion->puntaje = $sesion->preguntas_puntaje_estreno;
                                        $evaluacion->save();
                                    }
                                }else{
                                    if($evaluacion->puntaje==0){
                                        echo '<td style="color:green">'.$evaluacion->puntaje.'</td>'; 
                                    }else{
                                        echo '<td style="color:red">'.$evaluacion->puntaje.' (Error)</td>'; 
                                        $evaluacion->puntaje = 0;
                                        $evaluacion->save();
                                    }
                                }
                                
                            }else{
                                echo '<td style="color:red">Fuera de estreno</td>';  
                                echo '<td>'.$evaluacion->respuesta_usuario.'</td>';
                                if($evaluacion->respuesta_correcta == 'correcto'){
                                    
                                    if($sesion->preguntas_puntaje_normal == $evaluacion->puntaje){
                                        echo '<td style="color:green">'.$evaluacion->puntaje.'</td>'; 
                                    }else{
                                        echo '<td style="color:red">'.$evaluacion->puntaje.' (Corregido)</td>'; 
                                        
                                        $evaluacion->puntaje = $sesion->preguntas_puntaje_normal;
                                        $evaluacion->save();
                                        
                                    }
                                }else{
                                    if($evaluacion->puntaje==0){
                                        echo '<td style="color:green">'.$evaluacion->puntaje.'</td>'; 
                                    }else{
                                        echo '<td style="color:red">'.$evaluacion->puntaje.' (Error)</td>'; 
                                        
                                        $evaluacion->puntaje = 0;
                                        $evaluacion->save();
                                        
                                    }
                                }
                            }
                            
                            
                            echo '</tr>';
                        }
                    
                echo '</table>';
            echo '</td>';
            
            
            echo '</tr>';
        }
        
        echo '</table>';
    }

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

        foreach($visualizaciones as $visualizacion){
            
            $detalles_distribuidor = Distribuidor::find($visualizacion->id_distribuidor);
            if($detalles_distribuidor){
                $visualizacion->nombre_distribuidor = $detalles_distribuidor->nombre;
            }else{
                $visualizacion->nombre_distribuidor = 'N/A';
            }
            

        }

        $respuestas = DB::table('evaluaciones_respuestas')
            ->join('evaluaciones_preguntas', 'evaluaciones_respuestas.id_pregunta', '=', 'evaluaciones_preguntas.id')
            ->join('usuarios', 'evaluaciones_respuestas.id_usuario', '=', 'usuarios.id')
            ->where('evaluaciones_respuestas.id_sesion', '=', $id)
            ->select('evaluaciones_respuestas.id as id_respuesta', 'evaluaciones_respuestas.*', 'evaluaciones_preguntas.id as id_pregunta', 'evaluaciones_preguntas.*', 'usuarios.id as id_usuario', 'usuarios.*')
            ->get();

        $preguntas = EvaluacionPreg::where('id_sesion',$id)->get();
        $dudas = SesionDudas::where('id_sesion',$id)->get();
        $anexos = SesionAnexos::where('id_sesion',$id)->get();

        return view('admin/sesion_resultados', compact('sesion', 'visualizaciones', 'respuestas', 'preguntas', 'dudas', 'anexos'));
    }

    public function dudas(string $id)
    {
        //
        $sesion = SesionEv::find($id);

        $dudas = DB::table('sesiones_dudas')
            ->join('usuarios', 'sesiones_dudas.id_usuario', '=', 'usuarios.id')
            ->where('sesiones_dudas.id_sesion', '=', $sesion->id)
            ->select('sesiones_dudas.id as id_duda', 'sesiones_dudas.*', 'usuarios.*')
            ->orderBy('sesiones_dudas.created_at', 'desc')
            ->get();

        return view('admin/sesion_dudas', compact('sesion', 'dudas'));
    }

    public function dudas_edit(Request $request, string $id)
    {
        //
        $duda = SesionDudas::find($id);
        $duda->respuesta = $request->input('Respuesta');
        $duda->save();
       

         return redirect()->route('sesiones.dudas', $duda->id_sesion);
         
    }

    public function destroy_dudas(string $id)
    {
        //
        $duda = SesionDudas::find($id);
        $id_sesion =  $duda->id_sesion;
        
        $duda->delete();
        return redirect()->route('sesiones.dudas', $id_sesion);
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
            $imagen->move(base_path('../public_html/system.panduitlatam.com/img/publicaciones'), $nombreImagen);
        }else{
            $nombreImagen = $sesion->imagen;
        }
        if ($request->hasFile('ImagenFondo')) {
            $imagen_fondo = $request->file('ImagenFondo');
            $nombreImagenFondo = 'fondo_sesion_'.time().'.'.$imagen_fondo->extension();
            $imagen_fondo->move(base_path('../public_html/system.panduitlatam.com/img/publicaciones'), $nombreImagenFondo);
        }else{
            $nombreImagenFondo = $sesion->imagen_fondo;
        }
        if ($request->hasFile('ImagenInstructor')) {
            $imagen_instructor = $request->file('ImagenInstructor');
            $nombreImagenInstructor = 'instructor_'.time().'.'.$imagen_instructor->extension();
            $imagen_instructor->move(base_path('../public_html/system.panduitlatam.com/img/publicaciones'), $nombreImagenInstructor);
        }else{
            $nombreImagenInstructor = $sesion->imagen_instructor;
        }
        
        

        

         $sesion->id_cuenta = $request->IdCuenta;
         $sesion->id_temporada = $request->IdTemporada;
         $sesion->titulo = $request->Titulo;
         $sesion->url = $request->Url;
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
         $sesion->puntaje_video_1_estreno = $request->PuntajeVideo1Estreno;
        $sesion->puntaje_video_2_estreno = $request->PuntajeVideo2Estreno;
        $sesion->puntaje_video_3_estreno = $request->PuntajeVideo3Estreno;
        $sesion->puntaje_video_4_estreno = $request->PuntajeVideo4Estreno;
        $sesion->puntaje_video_5_estreno = $request->PuntajeVideo5Estreno;
        $sesion->puntaje_video_1_normal = $request->PuntajeVideo1Normal;
        $sesion->puntaje_video_2_normal = $request->PuntajeVideo2Normal;
        $sesion->puntaje_video_3_normal = $request->PuntajeVideo3Normal;
        $sesion->puntaje_video_4_normal = $request->PuntajeVideo4Normal;
        $sesion->puntaje_video_5_normal = $request->PuntajeVideo5Normal;
        $sesion->fecha_video_1 = (!empty($request->FechaVideo1) && !empty($request->HoraVideo1)) 
            ? date('Y-m-d H:i:s', strtotime($request->FechaVideo1 . ' ' . $request->HoraVideo1)) 
            : null;

        $sesion->fecha_video_2 = (!empty($request->FechaVideo2) && !empty($request->HoraVideo2)) 
            ? date('Y-m-d H:i:s', strtotime($request->FechaVideo2 . ' ' . $request->HoraVideo2)) 
            : null;

        $sesion->fecha_video_3 = (!empty($request->FechaVideo3) && !empty($request->HoraVideo3)) 
            ? date('Y-m-d H:i:s', strtotime($request->FechaVideo3 . ' ' . $request->HoraVideo3)) 
            : null;

        $sesion->fecha_video_4 = (!empty($request->FechaVideo4) && !empty($request->HoraVideo4)) 
            ? date('Y-m-d H:i:s', strtotime($request->FechaVideo4 . ' ' . $request->HoraVideo4)) 
            : null;

        $sesion->fecha_video_5 = (!empty($request->FechaVideo5) && !empty($request->HoraVideo5)) 
            ? date('Y-m-d H:i:s', strtotime($request->FechaVideo5 . ' ' . $request->HoraVideo5)) 
            : null;
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
        $respuestas = EvaluacionRes::where('id_sesion', $id_sesion)->where('id_usuario', $visualizacion->id_usuario)->delete();
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
        $pregunta->video = $request->filled('Video') ? (int) $request->Video : null;
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
        $pregunta->video = $request->filled('Video') ? (int) $request->Video : null;
 
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
     * Exports de excel
     */

     public function resultados_excel (Request $request)
    {
        return Excel::download(new ReporteSesionExport($request), 'reporte_sesion.xlsx');
        
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

    public function full_datos_sesion_api(Request $request)
    {
        //
        $sesion = SesionEv::find($request->input('id'));
        $cuenta = Cuenta::find($sesion->id_cuenta);
        $temporada_sesion = Temporada::find($sesion->id_temporada);
        $temporada_actual = Temporada::find($cuenta->temporada_actual);

        if($temporada_sesion->id==$temporada_actual->id){
            $mostrarPuntajes = true;
        }else{
            $mostrarPuntajes = false;
        }
        $dudas = DB::table('sesiones_dudas')
            ->join('usuarios', 'sesiones_dudas.id_usuario', '=', 'usuarios.id')
            ->where('sesiones_dudas.id_sesion', '=', $sesion->id)
            ->select(
                'sesiones_dudas.created_at as fecha_duda', 
                'sesiones_dudas.*', 
                'usuarios.nombre as nombre', 
                'usuarios.apellidos as apellidos'
            )
            ->orderBy('sesiones_dudas.created_at', 'desc')
            ->get();
        $anexos = SesionAnexos::where('id_sesion',$sesion->id)->get();

        $fecha_actual = now()->format('Y-m-d H:i:s');
        
        // consulta
        $pendientes = SesionEv::where('id_temporada', $temporada_actual->id)
                            ->whereDate('fecha_publicacion', '>', $fecha_actual)
                            ->limit(2) // Limitar a dos resultados
                            ->get();

        $completo = [
            'sesion' => $sesion,
            'dudas' => $dudas,
            'anexos' => $anexos,
            'sesiones_pendientes' => $pendientes,
            'mostrar_puntajes' => $mostrarPuntajes,
            'temporada' => $temporada_sesion,
        ];

         return response()->json($completo);
    }

    public function full_datos_sesion_2025_api(Request $request)
    {
        //
        $id_cuenta = $request->input('cuenta');
        $sesion = SesionEv::where('id_cuenta', $id_cuenta)->where('url', $request->input('url'))->first();
        $cuenta = Cuenta::find($id_cuenta);
        $temporada_sesion = Temporada::find($sesion->id_temporada);
        $temporada_actual = Temporada::find($cuenta->temporada_actual);

        if($temporada_sesion->id==$temporada_actual->id){
            $mostrarPuntajes = true;
        }else{
            $mostrarPuntajes = false;
        }
        $dudas = DB::table('sesiones_dudas')
            ->join('usuarios', 'sesiones_dudas.id_usuario', '=', 'usuarios.id')
            ->where('sesiones_dudas.id_sesion', '=', $sesion->id)
            ->select(
                'sesiones_dudas.created_at as fecha_duda', 
                'sesiones_dudas.*', 
                'usuarios.nombre as nombre', 
                'usuarios.apellidos as apellidos'
            )
            ->orderBy('sesiones_dudas.created_at', 'desc')
            ->get();
        $anexos = SesionAnexos::where('id_sesion',$sesion->id)->get();

        $fecha_actual = now()->format('Y-m-d H:i:s');
        
        // consulta
        $pendientes = SesionEv::where('id_temporada', $temporada_actual->id)
                            ->whereDate('fecha_publicacion', '>', $fecha_actual)
                            ->limit(2) // Limitar a dos resultados
                            ->get();

                            $otras = SesionEv::where('id_cuenta', $temporada_actual->id_cuenta)
                            ->where('id_temporada', '!=', $temporada_actual->id)
                            ->whereDate('fecha_publicacion', '<', $fecha_actual)
                            ->inRandomOrder() // Orden aleatorio
                            ->limit(2)        // Limitar a dos resultados
                            ->get();

        $completo = [
            'sesion' => $sesion,
            'dudas' => $dudas,
            'anexos' => $anexos,
            'sesiones_pendientes' => $pendientes,
            'otras_sesiones' => $otras,
            'mostrar_puntajes' => $mostrarPuntajes,
            'temporada' => $temporada_sesion,
        ];

         return response()->json($completo);
    }

    public function preguntas_y_respuestas_sesion_api(Request $request)
    {
        $sesion = SesionEv::find($request->input('id_sesion'));
        $cantidad_preguntas = $sesion->cantidad_preguntas_evaluacion;
        $orden = $sesion->ordenar_preguntas_evaluacion;

        // Preparo la consulta de las preguntas
        $query = EvaluacionPreg::where('id_sesion', $request->input('id_sesion'));

        // Checo el orden
        if ($orden === 'ordenado') {
            $query->orderBy('id', 'asc');
        }

        if ($orden === 'aleatorio') {
            $query->inRandomOrder($request->input('id_usuario'));
        }

        // Limitar el número de preguntas
        $preguntas = $query->limit($cantidad_preguntas)->get();

        // Obtener los IDs de las preguntas seleccionadas
        $ids_preguntas = $preguntas->pluck('id');

        // Obtener solo las respuestas relacionadas a esas preguntas
        $respuestas = EvaluacionRes::where('id_sesion', $request->input('id_sesion'))
            ->where('id_usuario', $request->input('id_usuario'))
            ->whereIn('id_pregunta', $ids_preguntas)
            ->get();

        $completo = [
            'preguntas' => $preguntas,
            'respuestas' => $respuestas
        ];

        return response()->json($completo);
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
        $dudas = DB::table('sesiones_dudas')
            ->join('usuarios', 'sesiones_dudas.id_usuario', '=', 'usuarios.id')
            ->where('sesiones_dudas.id_sesion', '=', $request->input('id_sesion'))
            ->select(
                'sesiones_dudas.created_at as fecha_duda', 
                'sesiones_dudas.*', 
                'usuarios.nombre as nombre', 
                'usuarios.apellidos as apellidos'
            )
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

    public function checar_full_visualizacion_api(Request $request)
    {
        //
        $visualizacion = SesionVis::where('id_sesion', $request->input('id_sesion'))
        ->where('id_usuario', $request->input('id_usuario'))
        ->first();

        return response()->json($visualizacion);
    }

    public function registrar_inicio_video_api(Request $request)
    {
        $id_sesion = $request->input('id_sesion');
        $id_usuario = $request->input('id_usuario');
        $index_video = $request->input('index_video') ?? 0;
        $usuario= User::find($id_usuario);
        $sesion = SesionEv::find($id_sesion);
        $temporada = Temporada::find($sesion->id_temporada);

        $accion = new AccionesUsuarios;
        $accion->id_usuario = $usuario->id;
        $accion->nombre = $usuario->nombre.' '.$usuario->apellidos;
        $accion->correo = $usuario->email;
        $accion->accion = 'inicio video';
        $accion->descripcion = 'inicio video de la sesión: '.$sesion->titulo;

        if ($accion->save()) {
            // El guardado fue exitoso, retorna lo que desees.
            return response()->json([
                'success' => true,
                'message' => 'Almacenado'
            ]); // Código de error 500 (Internal Server Error)
        } else {
            // El guardado falló, puedes retornar un error o manejarlo como prefieras.
            return response()->json([
                'success' => false,
                'message' => 'Error al guardar la visualización.'
            ], 500); // Código de error 500 (Internal Server Error)
        }
        
    }

    public function registrar_visualizacion_api(Request $request)
    {
        $id_sesion = $request->input('id_sesion');
        $id_usuario = $request->input('id_usuario');
        $index_video = $request->input('index_video') ?? 0;
        $sesion = SesionEv::find($id_sesion);
        $temporada = Temporada::find($sesion->id_temporada);
        $suscripcion = UsuariosSuscripciones::where('id_usuario',$id_usuario)->where('id_temporada',$temporada->id)->first();

        $fecha_final_temporada = $temporada->fecha_final;
        $fecha_publicacion = $sesion->fecha_publicacion;
        $fecha_limite_estreno = date('Y-m-d H:i:s', strtotime($fecha_publicacion.' +'.$sesion->horas_estreno.' hours'));
        $fecha_actual = date('Y-m-d H:i:s');

        

        $video_unico = !empty($sesion->video_1) &&
               empty($sesion->video_2) &&
               empty($sesion->video_3) &&
               empty($sesion->video_4) &&
               empty($sesion->video_5);

        if ($video_unico) {
            // Asigna puntaje normal o estreno si la fecha actual está dentro del límite de estreno.
            $puntaje = $fecha_actual <= $fecha_limite_estreno 
                ? $sesion->visualizar_puntaje_estreno 
                : $sesion->visualizar_puntaje_normal;
        } else {
            // Array de puntajes normales y de estreno.
            $puntajes_normales = [
                $sesion->puntaje_video_1_normal ?? $sesion->visualizar_puntaje_normal,
                $sesion->puntaje_video_2_normal ?? $sesion->visualizar_puntaje_normal,
                $sesion->puntaje_video_3_normal ?? $sesion->visualizar_puntaje_normal,
                $sesion->puntaje_video_4_normal ?? $sesion->visualizar_puntaje_normal,
                $sesion->puntaje_video_5_normal ?? $sesion->visualizar_puntaje_normal,
            ];
        
            $puntajes_estrenos = [
                $sesion->puntaje_video_1_estreno ?? $sesion->visualizar_puntaje_estreno,
                $sesion->puntaje_video_2_estreno ?? $sesion->visualizar_puntaje_estreno,
                $sesion->puntaje_video_3_estreno ?? $sesion->visualizar_puntaje_estreno,
                $sesion->puntaje_video_4_estreno ?? $sesion->visualizar_puntaje_estreno,
                $sesion->puntaje_video_5_estreno ?? $sesion->visualizar_puntaje_estreno,
            ];
        
            // Asigna el puntaje basado en el índice del video, con el valor por defecto si el índice no está en el rango.
            if ($index_video >= 0 && $index_video < count($puntajes_normales)) {
                $puntaje = $fecha_actual <= $fecha_limite_estreno 
                    ? $puntajes_estrenos[$index_video] 
                    : $puntajes_normales[$index_video];
            } else {
                // Valor por defecto si el índice no coincide con los puntajes disponibles.
                $puntaje = $fecha_actual <= $fecha_limite_estreno 
                    ? $sesion->visualizar_puntaje_estreno 
                    : $sesion->visualizar_puntaje_normal;
            }
        }
        
        $visualizacion = SesionVis::where('id_sesion', $id_sesion)->where('id_usuario', $id_usuario)->first();

        // Verificar si la visualización existe
        if(!$visualizacion){
            // Si no existe, crear una nueva visualización
            $visualizacion = new SesionVis();
            $visualizacion->id_usuario = $id_usuario;
            $visualizacion->id_temporada = $temporada->id;
            if($suscripcion){
                $visualizacion->id_distribuidor = $suscripcion->id_distribuidor;
            }
            
            
            $visualizacion->id_sesion = $id_sesion;
            $visualizacion->puntaje = $puntaje;
            $visualizacion->fecha_ultimo_video = date('Y-m-d H:i:s');
            switch ($index_video) {
                case 0:
                    $visualizacion->fecha_video_1 = date('Y-m-d H:i:s');
                    $puntaje =  $fecha_actual <= $fecha_limite_estreno
                        ? $sesion->puntaje_video_1_estreno
                        : $sesion->puntaje_video_1_normal;
                    break;
                case 1:
                    $visualizacion->fecha_video_2 = date('Y-m-d H:i:s');
                    $puntaje =  $fecha_actual <= $fecha_limite_estreno
                        ? $sesion->puntaje_video_2_estreno
                        : $sesion->puntaje_video_2_normal;
                    break;
                case 2:
                    $visualizacion->fecha_video_3 = date('Y-m-d H:i:s');
                    $puntaje =  $fecha_actual <= $fecha_limite_estreno
                        ? $sesion->puntaje_video_3_estreno
                        : $sesion->puntaje_video_3_normal;
                    break;
                case 3:
                    $visualizacion->fecha_video_4 = date('Y-m-d H:i:s');
                    $puntaje =  $fecha_actual <= $fecha_limite_estreno
                        ? $sesion->puntaje_video_4_estreno
                        : $sesion->puntaje_video_4_normal;
                    break;
                case 4:
                    $visualizacion->fecha_video_5 = date('Y-m-d H:i:s');
                    $puntaje =  $fecha_actual <= $fecha_limite_estreno
                        ? $sesion->puntaje_video_5_estreno
                        : $sesion->puntaje_video_5_normal;
                    break;
                default:
                    # code...
                    break;
            }
            if(empty($puntaje)){
                $puntaje = $fecha_actual <= $fecha_limite_estreno 
                    ? $sesion->visualizar_puntaje_estreno 
                    : $sesion->visualizar_puntaje_normal;
            }
            $visualizacion->puntaje = $puntaje;
            // Valido que la fecha de la temporada se cumpla
            if($fecha_final_temporada < $fecha_actual){
                $visualizacion->puntaje = 0;
            }
            $visualizacion->save();
            
            // Registro la acción 
            $usuario= User::find($id_usuario);
            $accion = new AccionesUsuarios;
            $accion->id_usuario = $usuario->id;
            $accion->nombre = $usuario->nombre.' '.$usuario->apellidos;
            $accion->correo = $usuario->email;
            $accion->accion = 'Finalizó la sesión';
            $accion->descripcion = 'finalizó la sesión: '.$sesion->titulo;
            $accion->save();

            return response()->json([
                'success' => true,
                'message' => 'Almacenado',
                'puntaje' => $visualizacion->puntaje
            ]); // Código de error 500 (Internal Server Error)
        }else{
            if(empty($visualizacion->fecha_ultimo_video)){
                $visualizacion->fecha_ultimo_video = date('Y-m-d H:i:s');
                switch ($index_video) {
                    case 0:
                        if (empty($visualizacion->fecha_video_1)) {
                            $visualizacion->fecha_video_1 = date('Y-m-d H:i:s');
                            $puntaje =  $fecha_actual <= $fecha_limite_estreno
                                ? $sesion->puntaje_video_1_estreno
                                : $sesion->puntaje_video_1_normal;
                            $visualizacion->puntaje += $puntaje;
                        }
                        break;
                    case 1:
                        if (empty($visualizacion->fecha_video_2)) {
                            $visualizacion->fecha_video_2 = date('Y-m-d H:i:s');
                            $puntaje =  $fecha_actual <= $fecha_limite_estreno
                                ? $sesion->puntaje_video_2_estreno
                                : $sesion->puntaje_video_2_normal;
                            $visualizacion->puntaje += $puntaje;
                        }
                        break;
                    case 2:
                        if (empty($visualizacion->fecha_video_3)) {
                            $visualizacion->fecha_video_3 = date('Y-m-d H:i:s');
                            $puntaje =  $fecha_actual <= $fecha_limite_estreno
                                ? $sesion->puntaje_video_3_estreno
                                : $sesion->puntaje_video_3_normal;
                            $visualizacion->puntaje += $puntaje;
                        }
                        break;
                    case 3:
                        if (empty($visualizacion->fecha_video_4)) {
                            $visualizacion->fecha_video_4 = date('Y-m-d H:i:s');
                            $puntaje =  $fecha_actual <= $fecha_limite_estreno
                                ? $sesion->puntaje_video_4_estreno
                                : $sesion->puntaje_video_4_normal;
                            $visualizacion->puntaje += $puntaje;
                        }
                        break;
                    case 4:
                        if (empty($visualizacion->fecha_video_5)) {
                            $visualizacion->fecha_video_5 = date('Y-m-d H:i:s');
                            $puntaje =  $fecha_actual <= $fecha_limite_estreno
                                ? $sesion->puntaje_video_5_estreno
                                : $sesion->puntaje_video_5_normal;
                            $visualizacion->puntaje += $puntaje;
                        }
                        break;
                    default:
                        // Otras acciones si es necesario.
                        return('Default sin puntaje');
                        break;
                }
                // Valido que la fecha de la temporada se cumpla
                if($fecha_final_temporada < $fecha_actual){
                    $visualizacion->puntaje = 0;
                }

                if ($visualizacion->save()) {
                    // El guardado fue exitoso, retorna lo que desees.
                    // Registro la acción 
                    $usuario= User::find($id_usuario);
                    $accion = new AccionesUsuarios;
                    $accion->id_usuario = $usuario->id;
                    $accion->nombre = $usuario->nombre.' '.$usuario->apellidos;
                    $accion->correo = $usuario->email;
                    $accion->accion = 'Finalizó la sesión';
                    $accion->descripcion = 'finalizó la sesión: '.$sesion->titulo;
                    $accion->save();
                    
                    return response()->json([
                        'success' => true,
                        'message' => 'Almacenado',
                        'puntaje' => $visualizacion->puntaje
                    ]); // Código de error 500 (Internal Server Error)
                } else {
                    // El guardado falló, puedes retornar un error o manejarlo como prefieras.
                    return response()->json([
                        'success' => false,
                        'message' => 'Error al guardar la visualización.'
                    ], 500); // Código de error 500 (Internal Server Error)
                }
                
            }else{
                return('No almacenado '.$id_sesion.' - '.$id_usuario);
            }
            
        }

        
    }

    public function registrar_avance_api(Request $request)
    {
        $id_sesion = $request->input('id_sesion');
        $id_usuario = $request->input('id_usuario');
        $index_video = $request->input('index_video');
        $sesion = SesionEv::find($id_sesion);
        $temporada = Temporada::find($sesion->id_temporada);
        $suscripcion = UsuariosSuscripciones::where('id_usuario',$id_usuario)->where('id_temporada',$temporada->id)->first();

        $fecha_final_temporada = $temporada->fecha_final;
        $fecha_publicacion = $sesion->fecha_publicacion;
        $fecha_limite_estreno = date('Y-m-d H:i:s', strtotime($fecha_publicacion.' +'.$sesion->horas_estreno.' hours'));
        $fecha_actual = date('Y-m-d H:i:s');

        $visualizacion = SesionVis::where('id_sesion', $id_sesion)->where('id_usuario', $id_usuario)->first();

        // Verificar si la visualización existe
        if(!$visualizacion){
            // Si no existe, crear una nueva visualización
            $visualizacion = new SesionVis();
            $visualizacion->id_usuario = $id_usuario;
            $visualizacion->id_temporada = $temporada->id;
            if($suscripcion){
                $visualizacion->id_distribuidor = $suscripcion->id_distribuidor;
            }
            
            $visualizacion->id_sesion = $id_sesion;
            switch ($index_video) {
                case 0:
                    if (empty($visualizacion->fecha_video_1)) {
                        $visualizacion->fecha_video_1 = $fecha_actual;
                        $puntaje =  $fecha_actual <= $fecha_limite_estreno
                                    ? $sesion->puntaje_video_1_estreno
                                    : $sesion->puntaje_video_1_normal;
                        $visualizacion->puntaje += $puntaje;
                    }
                    
                    break;
                case 1:
                    if (empty($visualizacion->fecha_video_2)) {
                        $visualizacion->fecha_video_2 = $fecha_actual;
                        $puntaje =  $fecha_actual <= $fecha_limite_estreno
                                    ? $sesion->puntaje_video_2_estreno
                                    : $sesion->puntaje_video_2_normal;
                        $visualizacion->puntaje += $puntaje;
                    }
                    break;
                case 2:
                    if (empty($visualizacion->fecha_video_3)) {
                        $visualizacion->fecha_video_3 = $fecha_actual;
                        $puntaje =  $fecha_actual <= $fecha_limite_estreno
                                    ? $sesion->puntaje_video_3_estreno
                                    : $sesion->puntaje_video_3_normal;
                        $visualizacion->puntaje += $puntaje;
                    }
                    break;
                case 3:
                    if (empty($visualizacion->fecha_video_4)) {
                        $visualizacion->fecha_video_4 = $fecha_actual;
                        $puntaje =  $fecha_actual <= $fecha_limite_estreno
                                    ? $sesion->puntaje_video_4_estreno
                                    : $sesion->puntaje_video_4_normal;
                        $visualizacion->puntaje += $puntaje;
                    }
                    break;
                case 4:
                    if (empty($visualizacion->fecha_video_5)) {
                        $visualizacion->fecha_video_5 = $fecha_actual;
                        $puntaje =  $fecha_actual <= $fecha_limite_estreno
                                    ? $sesion->puntaje_video_5_estreno
                                    : $sesion->puntaje_video_5_normal;
                        $visualizacion->puntaje += $puntaje;
                    }
                    break;
                default:
                    # code...
                    break;
            }

            // Valido que la fecha de la temporada se cumpla
            if($fecha_final_temporada < $fecha_actual){
                $visualizacion->puntaje = 0;
            }

            $visualizacion->save();

             // Registro la acción 
             $usuario= User::find($id_usuario);
             $accion = new AccionesUsuarios;
             $accion->id_usuario = $usuario->id;
             $accion->nombre = $usuario->nombre.' '.$usuario->apellidos;
             $accion->correo = $usuario->email;
             $accion->accion = 'Avance en la sesión';
             $accion->descripcion = 'Avance en la sesión: '.$sesion->titulo;
             $accion->save();
     

            return response()->json([
                'success' => true,
                'message' => 'Avance Almacenado',
                'puntaje' => $visualizacion->puntaje
            ]); // Código de error 500 (Internal Server Error)
        }else{
            switch ($index_video) {
                case 0:
                    if (empty($visualizacion->fecha_video_1)) {
                        $visualizacion->fecha_video_1 = $fecha_actual;
                        $puntaje =  $fecha_actual <= $fecha_limite_estreno
                                    ? $sesion->puntaje_video_1_estreno
                                    : $sesion->puntaje_video_1_normal;
                        $visualizacion->puntaje += $puntaje;
                    }
                    
                    break;
                case 1:
                    if (empty($visualizacion->fecha_video_2)) {
                        $visualizacion->fecha_video_2 = $fecha_actual;
                        $puntaje =  $fecha_actual <= $fecha_limite_estreno
                                    ? $sesion->puntaje_video_2_estreno
                                    : $sesion->puntaje_video_2_normal;
                        $visualizacion->puntaje += $puntaje;
                    }
                    break;
                case 2:
                    if (empty($visualizacion->fecha_video_3)) {
                        $visualizacion->fecha_video_3 = $fecha_actual;
                        $puntaje =  $fecha_actual <= $fecha_limite_estreno
                                    ? $sesion->puntaje_video_3_estreno
                                    : $sesion->puntaje_video_3_normal;
                        $visualizacion->puntaje += $puntaje;
                    }
                    break;
                case 3:
                    if (empty($visualizacion->fecha_video_4)) {
                        $visualizacion->fecha_video_4 = $fecha_actual;
                        $puntaje =  $fecha_actual <= $fecha_limite_estreno
                                    ? $sesion->puntaje_video_4_estreno
                                    : $sesion->puntaje_video_4_normal;
                        $visualizacion->puntaje += $puntaje;
                    }
                    break;
                case 4:
                    if (empty($visualizacion->fecha_video_5)) {
                        $visualizacion->fecha_video_5 = $fecha_actual;
                        $puntaje =  $fecha_actual <= $fecha_limite_estreno
                                    ? $sesion->puntaje_video_5_estreno
                                    : $sesion->puntaje_video_5_normal;
                        $visualizacion->puntaje += $puntaje;
                    }
                    break;
                default:
                    # code...
                    break;
            }

            // Valido que la fecha de la temporada se cumpla
            if($fecha_final_temporada < $fecha_actual){
                $visualizacion->puntaje = 0;
            }

            
            $visualizacion->save();

            // Registro la acción 
            $usuario= User::find($id_usuario);
            $accion = new AccionesUsuarios;
            $accion->id_usuario = $usuario->id;
            $accion->nombre = $usuario->nombre.' '.$usuario->apellidos;
            $accion->correo = $usuario->email;
            $accion->accion = 'Avance en la sesión';
            $accion->descripcion = 'Avance en la sesión: '.$sesion->titulo;
            $accion->save();


            return response()->json([
                'success' => true,
                'message' => 'Avance Almacenado',
                'puntaje' => $visualizacion->puntaje
            ]); // Código de error 500 (Internal Server Error)
        }

        
    }

    public function registrar_respuestas_evaluacion_api(Request $request)
{
    $id_sesion = $request->input('id_sesion');
    $id_usuario = $request->input('id_usuario');
    $respuestas_json = $request->input('respuestas');
    $sesion = SesionEv::find($id_sesion);
    $temporada = Temporada::find($sesion->id_temporada);
    $suscripcion = UsuariosSuscripciones::where('id_usuario', $id_usuario)
                                        ->where('id_temporada', $temporada->id)
                                        ->first();

    $fecha_publicacion = $sesion->fecha_publicacion;
    $fecha_limite_estreno = date('Y-m-d H:i:s', strtotime($fecha_publicacion.' +'.$sesion->horas_estreno.' hours'));
    $fecha_actual = date('Y-m-d H:i:s');

    $puntaje_preguntas = ($fecha_actual < $fecha_limite_estreno)
        ? $sesion->preguntas_puntaje_estreno
        : $sesion->preguntas_puntaje_normal;

    // Total de preguntas para esa sesión
    $total_preguntas = EvaluacionPreg::where('id_sesion', $id_sesion)->count();

    // Total de respuestas registradas actualmente
    $respuestas_existentes = EvaluacionRes::where('id_sesion', $id_sesion)
                                          ->where('id_usuario', $id_usuario)
                                          ->count();

    // Si ya respondió todas las preguntas, no guardar nada
    if ($respuestas_existentes >= $total_preguntas) {
        return response()->json(['message' => 'Todas las preguntas ya fueron respondidas.'], 200);
    }

    foreach ($respuestas_json as $pregunta => $respuesta) {
        // Solo registrar si no existe esa respuesta aún
        $registro_respuesta = EvaluacionRes::where('id_sesion', $id_sesion)
                                           ->where('id_usuario', $id_usuario)
                                           ->where('id_pregunta', $pregunta)
                                           ->first();

        if (!$registro_respuesta) {
            $pregunta_reg = EvaluacionPreg::find($pregunta);
            switch ($respuesta) {
                case 'A': $respuesta_correcta = $pregunta_reg->resultado_a; break;
                case 'B': $respuesta_correcta = $pregunta_reg->resultado_b; break;
                case 'C': $respuesta_correcta = $pregunta_reg->resultado_c; break;
                case 'D': $respuesta_correcta = $pregunta_reg->resultado_d; break;
                default:  $respuesta_correcta = 'incorrecto'; break;
            }

            $puntaje = ($respuesta_correcta === 'correcto') ? $puntaje_preguntas : 0;

            $registro_respuesta = new EvaluacionRes();
            $registro_respuesta->id_temporada = $temporada->id;
            if ($suscripcion) {
                $registro_respuesta->id_distribuidor = $suscripcion->id_distribuidor;
            }
            $registro_respuesta->id_usuario = $id_usuario;
            $registro_respuesta->id_sesion = $id_sesion;
            $registro_respuesta->id_pregunta = $pregunta;
            $registro_respuesta->respuesta_usuario = $respuesta;
            $registro_respuesta->respuesta_correcta = $respuesta_correcta;
            $registro_respuesta->puntaje = $puntaje;
            $registro_respuesta->fecha_registro = date('Y-m-d H:i:s');
            $registro_respuesta->save();

            // Registrar acción del usuario
            $usuario = User::find($id_usuario);
            $accion = new AccionesUsuarios();
            $accion->id_usuario = $usuario->id;
            $accion->nombre = $usuario->nombre.' '.$usuario->apellidos;
            $accion->correo = $usuario->email;
            $accion->accion = 'Respondió la evaluación';
            $accion->descripcion = 'Respondió la evaluación en la sesión: '.$sesion->titulo;
            $accion->save();
        }
    }

    return response()->json(['message' => 'Respuestas registradas correctamente.'], 200);
}


    public function registrar_duda_api(Request $request)
    {
        $id_sesion = $request->input('id_sesion');
        $id_usuario = $request->input('id_usuario');
        $duda_texto = $request->input('duda');
        $sesion = SesionEv::find($id_sesion);

        $duda = new SesionDudas();
        $duda->id_usuario = $id_usuario;
        $duda->id_sesion = $id_sesion;
        $duda->duda = $duda_texto;

        $duda->save();

        // Registro la acción 
        $usuario= User::find($id_usuario);
        $accion = new AccionesUsuarios;
        $accion->id_usuario = $usuario->id;
        $accion->nombre = $usuario->nombre.' '.$usuario->apellidos;
        $accion->correo = $usuario->email;
        $accion->accion = 'Duda en sesion';
        $accion->descripcion = 'Escribió una duda en la sesión: '.$sesion->titulo;
        $accion->save();

        
    }

    
}
