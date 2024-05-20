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
use App\Models\EvaluacionPreg;
use App\Models\EvaluacionRes;
use App\Models\EvaluacionesRespuestas;
use App\Models\TriviaRes;
use App\Models\Trivia;
use App\Models\JackpotIntentos;
use App\Models\Jackpot;
use App\Models\Cuenta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Carbon\Carbon;

class FrontController extends Controller
{

    public function index()
    {
        //
        return view('front/home');
    }

    public function scripts_ajustes()
    {
        /*
        // Ejecutar la migración
        Artisan::call('migrate');

        // Obtener el resultado de la ejecución de la migración
        $output = Artisan::output();

        // Puedes hacer algo con la salida (por ejemplo, devolverla como respuesta)
        return response()->json(['message' => 'Migraciones ejecutadas', 'output' => $output]);
        */
    }
    public function reparar_evaluaciones()
    {
        /*
        // Ejecutar la migración
        Artisan::call('migrate');

        // Obtener el resultado de la ejecución de la migración
        $output = Artisan::output();

        // Puedes hacer algo con la salida (por ejemplo, devolverla como respuesta)
        return response()->json(['message' => 'Migraciones ejecutadas', 'output' => $output]);
        */
        
        
        $evaluaciones = EvaluacionRes::all();

        echo '<table border="1">';
        
        foreach($evaluaciones as $evaluacion){
            $sesion = SesionEv::where('id', $evaluacion->id_sesion)->first();
            $pregunta = EvaluacionPreg::where('id', $evaluacion->id_pregunta)->first();
            echo '<tr>';
            echo '<td>'.$sesion->titulo.'</td>';
            echo '<td><b>'.$pregunta->pregunta.'</b></td>';
            echo '<td>'.$sesion->fecha_publicacion.'</td>';
            echo '<td>'.$sesion->horas_estreno.'</td>';
            echo '<td>'.$evaluacion->fecha_registro.'</td>';
            $fecha_publicacion = new \DateTime($sesion->fecha_publicacion);
            $horas_estreno = new \DateInterval('PT' . $sesion->horas_estreno . 'H');
            $fecha_estreno = (clone $fecha_publicacion)->add($horas_estreno);
            $fecha_respuesta = new \DateTime($evaluacion->fecha_registro);

            // Verificar si visto en estreno
            if ($fecha_respuesta <= $fecha_estreno) {
                echo '<td style="color:green">En tiempo</td>';
                echo '<td>'.$sesion->preguntas_puntaje_estreno.'</td>';
                echo '<td>'.$sesion->preguntas_puntaje_normal.'</td>';
                echo '<td>'.$evaluacion->respuesta_correcta.'</td>';
                if($evaluacion->respuesta_correcta == 'correcto'){
                    
                    if($evaluacion->puntaje == $sesion->preguntas_puntaje_estreno){
                        echo '<td style="color:green">'.$evaluacion->puntaje.'</td>'; 
                    }else{
                        echo '<td style="color:red">'.$evaluacion->puntaje.' (Actualizar)</td>'; 
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
                echo '<td>'.$sesion->preguntas_puntaje_estreno.'</td>';
                echo '<td>'.$sesion->preguntas_puntaje_normal.'</td>';
                echo '<td>'.$evaluacion->respuesta_correcta.'</td>';
                if($evaluacion->respuesta_correcta == 'correcto'){
                    
                    if($sesion->preguntas_puntaje_normal == $evaluacion->puntaje){
                        echo '<td style="color:green">'.$evaluacion->puntaje.'</td>'; 
                    }else{
                        echo '<td style="color:red">'.$evaluacion->puntaje.' (Actualizar)</td>'; 
                        
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
        
    }

    public function reparar_puntajes_visualizaciones()
    {
        /*
        // Ejecutar la migración
        Artisan::call('migrate');

        // Obtener el resultado de la ejecución de la migración
        $output = Artisan::output();

        // Puedes hacer algo con la salida (por ejemplo, devolverla como respuesta)
        return response()->json(['message' => 'Migraciones ejecutadas', 'output' => $output]);
        */

        $visualizaciones = SesionVis::all();

        echo '<table border="1">';
        
        foreach($visualizaciones as $visualizacion){
            $sesion = SesionEv::where('id', $visualizacion->id_sesion)->first();
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
            
            
            echo '</tr>';
        }
        
        echo '</table>';
        
    }
}
