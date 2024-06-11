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

    public function reparar_suscripciones(){
        $suscripciones = DB::table('usuarios_suscripciones')
            ->select('id', 'id_usuario', 'id_temporada')
            ->orderBy('id')
            ->get();

        $duplicates = [];

        // Recorrer todas las suscripciones para encontrar duplicados
        foreach ($suscripciones as $suscripcion) {
            $key = $suscripcion->id_usuario . '-' . $suscripcion->id_temporada;

            // Si la clave ya existe en el array de duplicados, significa que es un duplicado
            if (isset($duplicates[$key])) {
                $duplicates[$key][] = $suscripcion->id;
            } else {
                $duplicates[$key] = [$suscripcion->id];
            }
        }

        $idsToDelete = [];

        // Encontrar los IDs de los duplicados para eliminarlos
        foreach ($duplicates as $key => $ids) {
            // Si hay más de un ID en el array, significa que hay duplicados
            if (count($ids) > 1) {
                // Ordenar los IDs de forma ascendente y mantener solo el primero (el más pequeño)
                sort($ids);
                // Eliminar todos los IDs excepto el primero
                $idsToDelete = array_merge($idsToDelete, array_slice($ids, 1));
            }
        }

        
        // Eliminar las entradas duplicadas
        DB::table('usuarios_suscripciones')
            ->whereIn('id', $idsToDelete)
            ->delete();
            

        echo "Se han eliminado los siguientes IDs duplicados: " . implode(', ', $idsToDelete) . "\n";
    }

    public function migrar()
    
    {
        //
        // Ejecutar la migración
        Artisan::call('migrate');

        // Obtener el resultado de la ejecución de la migración
        $output = Artisan::output();

        // Puedes hacer algo con la salida (por ejemplo, devolverla como respuesta)
        return response()->json(['message' => 'Migraciones ejecutadas', 'output' => $output]);
    }

    public function relacion_suscripciones()
    {
        /*
        // Ejecutar la migración
        Artisan::call('migrate');

        // Obtener el resultado de la ejecución de la migración
        $output = Artisan::output();

        // Puedes hacer algo con la salida (por ejemplo, devolverla como respuesta)
        return response()->json(['message' => 'Migraciones ejecutadas', 'output' => $output]);
        */

        $usuarios = User::all();

        echo '<table border="1">';
        echo '<thead>';
        echo '<tr>';
        echo '<th>Correo</th>';
        echo '<th>PLE 2024</th>';
        echo '<th>PLE 2023</th>';
        echo '<th>PL</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        foreach($usuarios as $usuario){
            echo '<tr>';
            echo '<td>'.$usuario->email.'</td>';
            $suscripcion_1 = UsuariosSuscripciones::where('id_usuario', $usuario->id)->where('id_temporada', 1)->first();
            $suscripcion_2 = UsuariosSuscripciones::where('id_usuario', $usuario->id)->where('id_temporada', 9)->first();
            $suscripcion_3 = UsuariosSuscripciones::where('id_usuario', $usuario->id)->where('id_temporada', 6)->first();

            if($suscripcion_1){
                $distribuidor = Distribuidor::where('id', $suscripcion_1->id_distribuidor)->first();
                if($distribuidor){
                    echo '<td>'.$distribuidor->nombre.'</td>';
                }else{
                    echo '<td style="color: red;">Sin distribuidor</td>';
                }
                
            }else{
                echo '<td style="color: red;">N/P</td>';
            }
            if($suscripcion_2){
                $distribuidor = Distribuidor::where('id', $suscripcion_2->id_distribuidor)->first();
                if($distribuidor){
                    echo '<td>'.$distribuidor->nombre.'</td>';
                }else{
                    echo '<td style="color: red;">Sin distribuidor</td>';
                }
                
            }else{
                echo '<td style="color: red;">N/P</td>';
            }
            if($suscripcion_3){
                $distribuidor = Distribuidor::where('id', $suscripcion_3->id_distribuidor)->first();
                if($distribuidor){
                    echo '<td>'.$distribuidor->nombre.'</td>';
                }else{
                    echo '<td style="color: red;">Sin distribuidor</td>';
                }
                
            }else{
                echo '<td style="color: red;">N/P</td>';
            }
            
            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';
    }

    public function revisar_passwords()
    {
        /*
        // Ejecutar la migración
        Artisan::call('migrate');

        // Obtener el resultado de la ejecución de la migración
        $output = Artisan::output();

        // Puedes hacer algo con la salida (por ejemplo, devolverla como respuesta)
        return response()->json(['message' => 'Migraciones ejecutadas', 'output' => $output]);
        */

        $usuarios = User::paginate(200);

        echo '<table>';
        echo '<tbody>';
        foreach($usuarios as $usuario){
            echo '<tr>';
            echo '<td>'.$usuario->email.'</td>';
            echo '<td>'.$usuario->password.'</td>';
            
            if (Hash::check('123456', $usuario->password)) {
                echo '<td style="color:red">Se debe cambiar</td>';
                $suscripcion = UsuariosSuscripciones::where('id_usuario', $usuario->id)->where('id_temporada', 1)->first();
                if(!$suscripcion){
                    $suscripcion = UsuariosSuscripciones::where('id_usuario', $usuario->id)->where('id_temporada', 9)->first();
                }
                if($suscripcion){
                    $distribuidor = Distribuidor::where('id', $suscripcion->id_distribuidor)->first();
                    $nuevo_pass = Hash::make($distribuidor->default_pass);
                    echo '<td style="color:green">'.$distribuidor->default_pass.'</td>';
                    $usuario->password = $nuevo_pass;
                    $usuario->save();
                }else{
                    echo '<td style="color:green">No suscripcion</td>';
                }
                
            } else {
                echo '<td style="color:green">Es seguro</td>';
                echo '<td style="color:green">No se cambia</td>';
            }
            
            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';

        // Mostrar enlaces de paginación
        echo $usuarios->links();
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

    //public function reparar_puntajes_visualizaciones()
    public function scripts_ajustes()
    {

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
