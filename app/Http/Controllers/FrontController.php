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
use App\Models\Sesion;
use App\Models\EvaluacionPreg;
use App\Models\EvaluacionRes;
use App\Models\EvaluacionesRespuestas;
use App\Models\TriviaRes;
use App\Models\TriviaPreg;
use App\Models\Trivia;
use App\Models\JackpotIntentos;
use App\Models\Jackpot;
use App\Models\Cuenta;
use App\Models\Sku;
use App\Models\Logro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Carbon\Carbon;

class FrontController extends Controller
{

    public function index()
    {
        //
        return view('front/home');
    }

    public function scripts_ajustes_back()
    {
        $suscripciones = UsuariosSuscripciones::all();

        $tabla = '<table border="1" cellpadding="5" cellspacing="0" style="border-collapse: collapse; width: 100%;">';
        $tabla .= '<thead>';
        $tabla .= '<tr>';
        $tabla .= '<th>ID</th>';
        $tabla .= '<th>Distribuidor</th>';
        $tabla .= '<th>Suscripcion</th>';
        $tabla .= '</tr>';
        $tabla .= '</thead>';
        $tabla .= '<tbody>';

        foreach($suscripciones as $suscripcion){
            $distribuidor = Distribuidor::find($suscripcion->id_distribuidor);
            $region_dist = '';
            if($distribuidor){
                $region_dist = $distribuidor->region;

                if($suscripcion->region!=$distribuidor->region){
                    $suscripcion->region = $distribuidor->region;
                    $suscripcion->save();
                }
            }
            $tabla .= '<tr>';
            $tabla .= '<td>'.$suscripcion->id.'</td>';
            $tabla .= '<td>'.$region_dist.'</td>';
            $tabla .= '<td>'.$suscripcion->region.'</td>';
            $tabla .= '</tr>';
        }
        $tabla .= '</tbody>';
        $tabla .= '</table>';

        echo $tabla;
    }

    public function scripts_ajustes()
{   
    $sesion = Sesion::find(58);
    $cantidad_preguntas = $sesion->cantidad_preguntas_evaluacion;
    $visualizaciones = SesionVis::where('id_sesion', 58)->get();

    // Iniciar tabla
    $tabla = '<table border="1" cellpadding="5" cellspacing="0" style="border-collapse: collapse; width: 100%;">';
    $tabla .= '<thead>';
    $tabla .= '<tr>';
    $tabla .= '<th>ID Respuesta</th>';
    $tabla .= '<th>Preguntas</th>';
    $tabla .= '<th>Respuesta</th>';
    $tabla .= '<th>Estado</th>';
    $tabla .= '</tr>';
    $tabla .= '</thead>';
    $tabla .= '<tbody>';

    foreach ($visualizaciones as $visualizacion) {
        $usuario = User::find($visualizacion->id_usuario);

        // Obtener todas las preguntas y sus respuestas de este usuario
        $preguntas = EvaluacionPreg::where('id_sesion', 58)->inRandomOrder($visualizacion->id_usuario)->get();
        $respuestas_usuario = [];

        foreach ($preguntas as $pregunta) {
            $respuesta = EvaluacionRes::where('id_pregunta', $pregunta->id)
                                       ->where('id_usuario', $usuario->id)
                                       ->first();
            if ($respuesta) {
                $respuestas_usuario[] = [
                    'respuesta_id' => $respuesta->id,
                    'pregunta' => $pregunta->pregunta,
                    'respuesta_usuario' => $respuesta->respuesta_usuario,
                    'respuesta_correcta' => $respuesta->respuesta_correcta,
                ];
            }
        }

        // Verificamos si tiene más respuestas de las permitidas
        $exceso = count($respuestas_usuario) > $cantidad_preguntas;

        // Definimos estilo si tiene exceso
        $estiloFila = $exceso ? 'style="background-color: red; color: white;"' : '';

        // Fila del usuario (nombre/email)
        $tabla .= '<tr '.$estiloFila.'>';
        $tabla .= '<td colspan="4" style="font-weight: bold;">' . htmlspecialchars($usuario->email) . '</td>';
        $tabla .= '</tr>';

        // Filas de respuestas
        foreach ($respuestas_usuario as $respuesta_info) {
            $tabla .= '<tr '.$estiloFila.'>';
            $tabla .= '<td>' . $respuesta_info['respuesta_id'] . '</td>';
            $tabla .= '<td>' . htmlspecialchars($respuesta_info['pregunta']) . '</td>';
            $tabla .= '<td>' . htmlspecialchars($respuesta_info['respuesta_usuario']) . '</td>';
            $tabla .= '<td>' . htmlspecialchars($respuesta_info['respuesta_correcta']) . '</td>';
            $tabla .= '</tr>';
        }
    }

    $tabla .= '</tbody>';
    $tabla .= '</table>';

    echo $tabla;
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
}
