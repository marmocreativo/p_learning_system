<?php

namespace App\Exports;

use App\Models\User;
use App\Models\UsuariosSuscripciones;
use App\Models\Temporada;
use App\Models\Clase;
use App\Models\Sucursal;
use App\Models\Distribuidor;
use App\Models\DistribuidorSuscripciones;
use App\Models\SesionVisita;
use App\Models\SesionVis;
use App\Models\SesionEv;
use App\Models\EvaluacionPreg;
use App\Models\EvaluacionRes;
use App\Models\Trivia;
use App\Models\TriviaPreg;
use App\Models\TriviaRes;
use App\Models\TriviaGanador;
use App\Models\JackpotIntentos;
use App\Models\JackpotRes;
use App\Models\Jackpot;
use App\Models\PuntosExtra;
use App\Models\Cuenta;
use App\Models\Tokens;
use App\Models\AccionesUsuarios;
use App\Models\CanjeoTransacciones;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Facades\Excel;

class ReporteDistribuidorActividades implements FromCollection, WithHeadings
{
    protected $request;
    protected $id_cuenta;
    protected $id_distribuidor;
    protected $fecha_inicio;
    protected $fecha_final;

    public function __construct(Request $request, $id_cuenta,$id_distribuidor, $fecha_inicio, $fecha_final )
    {
        $this->request = $request;
        $this->id_cuenta = $id_cuenta;
        $this->id_distribuidor = $id_distribuidor;
        $this->fecha_inicio = $fecha_inicio;
        $this->fecha_final = $fecha_final;
    }
    public function collection()
    {
        $cuenta = Cuenta::find($this->id_cuenta);
        $temporada = Temporada::find($cuenta->temporada_actual);
        $distribuidor = Distribuidor::find($id_distribuidor);

        $usuarios_suscritos = DB::table('usuarios_suscripciones')
            ->join('usuarios', 'usuarios_suscripciones.id_usuario', '=', 'usuarios.id')
            ->join('distribuidores', 'usuarios_suscripciones.id_distribuidor', '=', 'distribuidores.id')
            ->leftJoin('sucursales', 'usuarios_suscripciones.id_sucursal', '=', 'sucursales.id')
            ->where('usuarios_suscripciones.id_temporada', '=', $temporada->id)
            // Añade la condición de distribuidor si no es 0
            ->when($distribuidor != null, function ($query) use ($distribuidor) {
                return $query->where('distribuidores.id', $distribuidor);
            })
            ->select(
                'usuarios.id as id_usuario',
                'usuarios.nombre as nombre',
                'usuarios.apellidos as apellidos',
                'usuarios.email as email',
                'distribuidores.region as region',
                'distribuidores.nombre as distribuidor',
                'sucursales.nombre as sucursal',
            )
            ->get();


        $inicios_sesion = AccionesUsuarios::where('id_temporada', $temporada->id)->where('accion', 'login')->get();
        $sesiones = SesionEv::where('id_temporada', $temporada->id)->get();
        $visualizaciones = SesionVis::where('id_temporada', $temporada->id)->get();
        $respuestas = EvaluacionRes::where('id_temporada', $temporada->id)->get();
        $trivias = Trivia::where('id_temporada', $temporada->id)->get();
        $trivias_respuestas = TriviaRes::where('id_temporada', $temporada->id)->get();
        $jackpots = Jackpot::where('id_temporada', $temporada->id)->get();
        $jackpots_intentos = JackpotIntentos::where('id_temporada', $temporada->id)->get();
        
        //dd($usuarios_suscritos);
        $coleccion = array();
        $index = 0;

        foreach($usuarios_suscritos as $usuario){
            $puntaje_total = 0;
            $coleccion[$index]['nombre'] = $usuario->nombre;
            $coleccion[$index]['apellidos'] = $usuario->apellidos;
            $coleccion[$index]['email'] = $usuario->email;
            $coleccion[$index]['region'] = $usuario->region;
            $coleccion[$index]['distribuidor'] = $usuario->distribuidor;
            $coleccion[$index]['sucursal'] = $usuario->sucursal;
            $s = 1;
            $t = 1;
            $j = 1;
            
            if(!empty($usuario->fecha_terminos)){

                $inicio_sesion = $inicios_sesion->first(function ($inicios_sesion) use ($usuario, $fecha_inicio, $fecha_final) {
                        return $inicios_sesion->id_usuario == $usuario->id_usuario
                            && $inicios_sesion->created_at >= $fecha_inicio
                            && $inicios_sesion->created_at <= $fecha_final;
                    });
                if ($inicio_sesion){
                    $coleccion[$index]['inicio_sesion']= 'Si';
                    
                }else{ 
                    $coleccion[$index]['inicio_sesion'] = 'No';
                }
                
                foreach ($sesiones as $sesion) {

                    $visualizacion = $visualizaciones->first(function ($visualizacion) use ($usuario, $sesion, $fecha_inicio, $fecha_final) {
                        return $visualizacion->id_usuario == $usuario->id_usuario
                            && $visualizacion->id_sesion == $sesion->id
                            && $visualizacion->fecha_ultimo_video >= $fecha_inicio
                            && $visualizacion->fecha_ultimo_video <= $fecha_final;
                    });
                    $evaluacion = $respuestas->filter(function ($respuesta) use ($usuario, $sesion) {
                        return $respuesta->id_usuario == $usuario->id_usuario
                            && $respuesta->id_sesion == $sesion->id
                            && $visualizacion->fecha_registro >= $fecha_inicio
                            && $visualizacion->fecha_registro <= $fecha_final;
                    });

                    if ($visualizacion){
                        $coleccion[$index]['s'.$s.'-v']= (string) $visualizacion->fecha_ultimo_video;
                        
                    }else{ 
                        $coleccion[$index]['s'.$s.'-v'] = '-';
                    }

                    if ($evaluacion){
                        $coleccion[$index]['s'.$s.'-e']= (string) $evaluacion->fecha_registro;
                    }else{ 
                        $coleccion[$index]['s'.$s.'-e'] = '-';
                    }
                    $s++;
                }
                foreach ($trivias as $trivia) {
                    $t_respuestas = $trivias_respuestas->filter(function ($respuesta) use ($usuario, $trivia) {
                        return $respuesta->id_usuario == $usuario->id_usuario
                            && $respuesta->id_trivia == $trivia->id
                            && $respuesta->fecha_registro >= $fecha_inicio
                            && $respuesta->fecha_registro <= $fecha_final;
                    });

                    if($t_respuestas){
                        $coleccion[$index]['t'.$t] = (string) $respuesta->fecha_registro;
                    }else{
                        $coleccion[$index]['t'.$t] = '-';
                    }
                    $t++;
                }
                foreach ($jackpots as $jackpot) {
                    $intentos = $jackpots_intentos->filter(function ($intento) use ($usuario, $jackpot) {
                        return $intento->id_usuario == $usuario->id_usuario 
                            && $intento->id_jackpot == $jackpot->id
                            && $intento->fecha_registro >= $fecha_inicio
                            && $intento->fecha_registro <= $fecha_final;
                    });

                    if($intentos){
                        $coleccion[$index]['j'.$j] = (string) $intento->fecha_registro;
                    }else{
                        $coleccion[$index]['j'.$j] = '-';
                    }
                    $j ++;
                }
            }else{

                foreach ($sesiones as $sesion) {
                    $coleccion[$index]['s'.$s.'-v'] = 'X';
                    $coleccion[$index]['s'.$s.'-e'] = 'X';
                    $s++;
                }
                foreach ($trivias as $trivia) {
                    $coleccion[$index]['t'.$t] = 'X';
                    $t++;
                }

                foreach ($jackpots as $jackpot) {
                    $coleccion[$index]['j'.$j] = 'X';
                    $j ++;
                }
                
            }
            
            
            $index ++;
        }
        return collect($coleccion);
            
    }
    public function headings(): array
    {
        $cuenta = Cuenta::find($this->id_cuenta);
        $temporada = Temporada::find($cuenta->temporada_actual);
        $sesiones = SesionEv::where('id_temporada', $temporada->id)->get();
        $trivias = Trivia::where('id_temporada', $temporada->id)->get();
        $jackpots = Jackpot::where('id_temporada', $temporada->id)->get();

        $encabezados =  [
            'Nombre',
            'Apellidos',
            'Correo',
            'Region',
            'Distribuidor',
            'Sucursal',
            'Inicio Sesión'
        ];
        $s = 1;
        $t = 1;
        $j = 1;
        
        foreach ($sesiones as $sesion) {
            $encabezados[] = 'S'.$s.'-V';
            $encabezados[] = 'S'.$s.'-E';
            $s++;
        }
        foreach ($trivias as $trivia) {
            $encabezados[] = 'T'.$t.'-G';
            $t++;
        }
        foreach ($jackpots as $jackpot) {
            $encabezados[] = 'J'.$j;
            $j++;
        }
        
        return $encabezados;
        
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // Aplicar formato a los encabezados
                $event->sheet->getStyle('A1:H1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF']
                    ],
                    'fill' => [
                        'fillType' => 'solid',
                        'startColor' => ['rgb' => '213746']
                    ],
                ]);
            },
        ];
    }
}