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
use App\Models\PuntosExtra;
use App\Models\Sku;
use App\Models\Logro;
use App\Models\LogroParticipacion;
use App\Models\LogroAnexo;
use App\Models\LogroAnexoProducto;
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

public function scripts_ajustes()
{
    try {
        $correcciones = [];
        $corregidos = 0;
        $errores = 0;
        $sin_correccion = 0;
        
        // SOLO revisar fechas FUTURAS (posteriores a HOY)
        $fecha_hoy = date('Y-m-d');
        $registros_futuros = SesionVis::whereDate('fecha_ultimo_video', '>', $fecha_hoy)->get();
        
        foreach ($registros_futuros as $registro) {
            try {
                $fecha_original = $registro->fecha_ultimo_video;
                $fecha_timestamp = strtotime($fecha_original);
                
                if ($fecha_timestamp === false) {
                    $errores++;
                    $correcciones[] = [
                        'id' => $registro->id,
                        'fecha_original' => $fecha_original,
                        'fecha_corregida' => 'ERROR',
                        'tipo_correccion' => 'ERROR_PARSING',
                        'estado' => 'error',
                        'descripcion' => 'No se pudo parsear la fecha'
                    ];
                    continue;
                }
                
                $año_como_string = date('Y', $fecha_timestamp);
                $mes = date('m', $fecha_timestamp);
                $dia_como_string = date('d', $fecha_timestamp);
                
                // Intentar intercambio año/día
                $nuevo_dia = substr($año_como_string, 2);
                $nuevo_año = "20" . $dia_como_string;
                
                // Validar que la fecha corregida sea válida Y que NO esté en el futuro
                $fecha_corregida_temp = "{$nuevo_año}-{$mes}-{$nuevo_dia}";
                $fecha_corregida_timestamp = strtotime($fecha_corregida_temp);
                
                if (checkdate($mes, $nuevo_dia, $nuevo_año) && 
                    $fecha_corregida_timestamp <= strtotime($fecha_hoy)) {
                    
                    $hora_parte = '';
                    if (strpos($fecha_original, ' ') !== false) {
                        $hora_parte = ' ' . explode(' ', $fecha_original)[1];
                    }
                    
                    $fecha_corregida = "{$nuevo_año}-{$mes}-{$nuevo_dia}{$hora_parte}";
                    $registro->fecha_ultimo_video = $fecha_corregida;
                    
                    if ($registro->save()) {
                        $corregidos++;
                        $correcciones[] = [
                            'id' => $registro->id,
                            'fecha_original' => $fecha_original,
                            'fecha_corregida' => $fecha_corregida,
                            'tipo_correccion' => 'FUTURO_INTERCAMBIO',
                            'estado' => 'corregido',
                            'descripcion' => "Intercambio año/día: {$año_como_string}→{$nuevo_año}, {$dia_como_string}→{$nuevo_dia}"
                        ];
                    } else {
                        $errores++;
                        $correcciones[] = [
                            'id' => $registro->id,
                            'fecha_original' => $fecha_original,
                            'fecha_corregida' => $fecha_corregida,
                            'tipo_correccion' => 'ERROR_GUARDADO',
                            'estado' => 'error',
                            'descripcion' => 'Error al guardar la corrección'
                        ];
                    }
                } else {
                    $sin_correccion++;
                    $correcciones[] = [
                        'id' => $registro->id,
                        'fecha_original' => $fecha_original,
                        'fecha_corregida' => 'NO_CORREGIBLE',
                        'tipo_correccion' => 'NO_CORREGIBLE',
                        'estado' => 'sin_cambio',
                        'descripcion' => 'No se puede corregir con intercambio año/día válido'
                    ];
                }
                
            } catch (Exception $e) {
                $errores++;
                $correcciones[] = [
                    'id' => $registro->id ?? 'unknown',
                    'fecha_original' => $fecha_original ?? 'unknown',
                    'fecha_corregida' => 'ERROR',
                    'tipo_correccion' => 'EXCEPCION',
                    'estado' => 'error',
                    'descripcion' => 'Excepción: ' . $e->getMessage()
                ];
            }
        }
        
        $total = count($registros_futuros);
        
        // Generar HTML de resultados
        $filas_correcciones = '';
        foreach ($correcciones as $correccion) {
            $class_estado = '';
            $icono = '';
            
            switch ($correccion['estado']) {
                case 'corregido':
                    $class_estado = 'success';
                    $icono = '✅';
                    break;
                case 'error':
                    $class_estado = 'error';
                    $icono = '❌';
                    break;
                case 'sin_cambio':
                    $class_estado = 'normal';
                    $icono = '➖';
                    break;
            }
            
            $filas_correcciones .= "
                <div class='correccion-item {$class_estado}'>
                    <div class='id-col'>ID: {$correccion['id']}</div>
                    <div class='fecha-original-col'>{$correccion['fecha_original']}</div>
                    <div class='fecha-corregida-col'>{$correccion['fecha_corregida']}</div>
                    <div class='tipo-col'>{$correccion['tipo_correccion']}</div>
                    <div class='estado-col'>{$icono} {$correccion['estado']}</div>
                    <div class='descripcion-col'>{$correccion['descripcion']}</div>
                </div>";
        }
        
    } catch (Exception $e) {
        return "<h1>Error General: {$e->getMessage()}</h1>";
    }
    
    $html = "
    <!DOCTYPE html>
    <html lang='es'>
    <head>
        <meta charset='UTF-8'>
        <title>Corrección de Fechas Futuras</title>
        <style>
            body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
            .container { max-width: 1400px; margin: 0 auto; background: white; padding: 20px; border-radius: 10px; }
            .header { text-align: center; background: #007bff; color: white; padding: 25px; border-radius: 10px; margin-bottom: 25px; }
            .header h1 { margin: 0 0 10px 0; font-size: 2.2em; }
            
            .date-info {
                background: #fff3cd;
                border: 2px solid #ffc107;
                padding: 15px;
                border-radius: 10px;
                margin: 20px 0;
                text-align: center;
                color: #856404;
                font-weight: bold;
                font-size: 1.1em;
            }
            
            .stats { display: flex; justify-content: space-around; margin: 25px 0; }
            .stat { text-align: center; padding: 20px; border-radius: 10px; min-width: 120px; }
            .stat h3 { margin: 0; font-size: 2.2em; font-weight: bold; }
            .stat p { margin: 5px 0 0 0; font-size: 1em; }
            .stat.total { background: #e3f2fd; color: #1976d2; }
            .stat.success { background: #e8f5e8; color: #2e7d32; }
            .stat.error { background: #ffe6e6; color: #d32f2f; }
            .stat.normal { background: #f3f4f6; color: #5f6368; }
            
            .correccion-item {
                display: grid;
                grid-template-columns: 80px 180px 180px 140px 120px 1fr;
                gap: 12px;
                padding: 12px;
                border-bottom: 1px solid #eee;
                align-items: center;
                font-size: 13px;
            }
            
            .correccion-item.success { background: #f8fff8; border-left: 4px solid #28a745; }
            .correccion-item.error { background: #fff8f8; border-left: 4px solid #dc3545; }
            .correccion-item.normal { background: #f8f9fa; border-left: 4px solid #6c757d; }
            .correccion-item:hover { background: #e9ecef; }
            
            .header-row {
                background: #343a40;
                color: white;
                font-weight: bold;
                border-radius: 8px;
                font-size: 14px;
            }
            
            .fecha-original-col, .fecha-corregida-col { 
                font-family: 'Courier New', monospace; 
                font-size: 12px;
                padding: 4px 8px;
                border-radius: 4px;
            }
            .fecha-original-col { background: #ffe6e6; color: #721c24; }
            .fecha-corregida-col { background: #e6f7e6; color: #155724; }
            .tipo-col { font-weight: bold; font-size: 11px; }
            .estado-col { font-weight: bold; }
            .descripcion-col { font-size: 12px; color: #495057; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h1>🔮 Corrección de Fechas Futuras</h1>
                <p>Solo procesando fechas posteriores a hoy</p>
            </div>
            
            <div class='date-info'>
                📅 Procesando SOLO fechas posteriores a: <strong>{$fecha_hoy}</strong>
            </div>
            
            <div class='stats'>
                <div class='stat total'>
                    <h3>{$total}</h3>
                    <p>Fechas Futuras</p>
                </div>
                <div class='stat success'>
                    <h3>{$corregidos}</h3>
                    <p>Corregidas</p>
                </div>
                <div class='stat error'>
                    <h3>{$errores}</h3>
                    <p>Errores</p>
                </div>
                <div class='stat normal'>
                    <h3>{$sin_correccion}</h3>
                    <p>Sin Cambios</p>
                </div>
            </div>
            
            <h3>📋 Detalle de Fechas Futuras Procesadas</h3>
            <div class='correcciones-table'>
                <div class='correccion-item header-row'>
                    <div>ID</div>
                    <div>Fecha Original</div>
                    <div>Fecha Corregida</div>
                    <div>Tipo</div>
                    <div>Estado</div>
                    <div>Descripción</div>
                </div>
                {$filas_correcciones}
            </div>
        </div>
    </body>
    </html>";
    
    return $html;
}

public function scripts_ajustes_deletreo_super_lider()
{
    $suscripciones = UsuariosSuscripciones::where('funcion', 'superlider')->get();
    $encontrados = 0;

    foreach ($suscripciones as $suscripcion) {
        $suscripcion->funcion = 'super_lider';
        $suscripcion->save();
        $encontrados++;
    }

    echo 'Encontrados y corregidos: ' . $encontrados;
}
public function scripts_ajustes_reparacion_bono()
{
    $registros_actualizados = PuntosExtra::where('concepto', 'Bono pimer ingreso')
        ->update(['concepto' => 'Bono primer ingreso']);
    
    echo "Se actualizaron {$registros_actualizados} registros.";
}

    public function scripts_ajustes_reparacion_sesion()
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
