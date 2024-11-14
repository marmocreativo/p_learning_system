<?php

namespace App\Exports;

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

class ReporteTemporadaExport implements FromCollection, WithHeadings
{
    protected $request;
    protected $id;

    public function __construct(Request $request, $id)
    {
        $this->request = $request;
        $this->id = $id;
    }
    public function collection()
    {
        $temporada = Temporada::find($this->id);
        $hoy = date('Y-m-d H:i:s');
        $sesiones = SesionEv::where('id_temporada', $temporada->id)->get();
        $visualizaciones = SesionVis::where('id_temporada', $temporada->id)->get();
        $respuestas = EvaluacionRes::where('id_temporada', $temporada->id)->get();
        $trivias = Trivia::where('id_temporada', $temporada->id)->get();
        $trivias_respuestas = TriviaRes::where('id_temporada', $temporada->id)->get();
        $trivias_ganadores = TriviaGanador::where('id_temporada', $temporada->id)->get();
        $jackpots = Jackpot::where('id_temporada', $temporada->id)->get();
        $jackpots_intentos = JackpotIntentos::where('id_temporada', $temporada->id)->get();
        $puntos_extra = PuntosExtra::where('id_temporada', $temporada->id)->get();
        $region = $this->request->input('region');
        $distribuidor = $this->request->input('distribuidor');
        $distribuidores = Distribuidor::all();
        if($region!='todas'){
            $distribuidores = Distribuidor::where('region',$region)->get();
        }
        $usuarios_suscritos = DB::table('usuarios_suscripciones')
            ->join('usuarios', 'usuarios_suscripciones.id_usuario', '=', 'usuarios.id')
            ->join('distribuidores', 'usuarios_suscripciones.id_distribuidor', '=', 'distribuidores.id')
            ->where('usuarios_suscripciones.id_temporada', '=', $temporada->id)
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
            $s = 1;
            $t = 1;
            $j = 1;
            
            foreach ($sesiones as $sesion) {
                $visualizacion = $visualizaciones->first(function ($visualizacion) use ($usuario, $sesion) {
                    return $visualizacion->id_usuario == $usuario->id_usuario && $visualizacion->id_sesion == $sesion->id;
                });
                $evaluacion = $respuestas->filter(function ($respuesta) use ($usuario, $sesion) {
                    return $respuesta->id_usuario == $usuario->id_usuario && $respuesta->id_sesion == $sesion->id;
                });
                $puntaje_evaluacion = 0;
                foreach($evaluacion as $res){
                    $puntaje_evaluacion += $res->puntaje;
                }
                if ($visualizacion){
                    $puntaje_total +=$visualizacion->puntaje+$puntaje_evaluacion;
                    
                    $coleccion[$index]['s'.$s.'-v']= (string) $visualizacion->puntaje;
                    $coleccion[$index]['s'.$s.'-e']= (string) $puntaje_evaluacion;
                }else{  
                    $coleccion[$index]['s'.$s.'-v'] = '0';
                    $coleccion[$index]['s'.$s.'-e'] = '0';
                }
                $s++;
            }
            foreach ($trivias as $trivia) {
                $t_respuestas = $trivias_respuestas->filter(function ($respuesta) use ($usuario, $trivia) {
                    return $respuesta->id_usuario == $usuario->id_usuario && $respuesta->id_trivia == $trivia->id;
                });
                $ganador = $trivias_ganadores->first(function ($ganador) use ($usuario, $trivia) {
                    return $ganador->id_usuario == $usuario->id_usuario && $ganador->id_trivia == $trivia->id;
                });
                $puntaje_trivias = 0;
                foreach($t_respuestas as $res){
                    $puntaje_trivias += $res->puntaje;
                }
                if($t_respuestas){
                    $puntaje_total +=$puntaje_trivias; 
                    $coleccion[$index]['t'.$t] = (string) $puntaje_trivias;
                }else{
                    $coleccion[$index]['t'.$t] = '0';
                }
                if($ganador){
                    $coleccion[$index]['t'.$t.'-G'] = 'Si';
                }else{
                    $coleccion[$index]['t'.$t.'-G'] = '-';
                }
                $t++;
            }
            foreach ($jackpots as $jackpot) {
                $intentos = $jackpots_intentos->filter(function ($intento) use ($usuario, $jackpot) {
                    return $intento->id_usuario == $usuario->id_usuario && $intento->id_jackpot == $jackpot->id;
                });
                $puntaje_jackpot = 0;
                foreach($intentos as $int){
                    $puntaje_jackpot += $int->puntaje;
                }
                if($intentos){
                    $puntaje_total +=$puntaje_jackpot;
                    $coleccion[$index]['j'.$j] = (string) $puntaje_jackpot;
                }else{
                    $coleccion[$index]['j'.$j] = '0';
                }
                $j ++;
            }
            $puntos_usuario = $puntos_extra->filter(function ($entrada) use ($usuario) {
                return $entrada->id_usuario == $usuario->id_usuario;
            });
            $total_puntos_extra = $puntos_usuario->sum('puntos');
            $puntaje_total +=$total_puntos_extra;

            $canjeo_transacciones = CanjeoTransacciones::where('id_usuario',$usuario->id_usuario)
                                                            ->where('id_temporada',$temporada->id)
                                                            ->pluck('creditos')->sum();
                                                            
            if(!$canjeo_transacciones){
                $creditos_consumidos = 0;
            }else{
                $creditos_consumidos = $canjeo_transacciones;
            }

            $coleccion[$index]['puntos_extra'] = (string) $total_puntos_extra;
            $coleccion[$index]['total'] = (string) $puntaje_total;
            $coleccion[$index]['creditos'] = (string) $creditos_consumidos;
            if($puntaje_total>0){
                $coleccion[$index]['activo'] = (string) 'Si';
            }else{
                $acciones_login = AccionesUsuarios::where('id_usuario', $usuario->id_usuario)->where('accion', 'login')->first();
                $tokens = Tokens::where('tokenable_id', $usuario->id_usuario)->first();

                if($acciones_login || $tokens){
                    $coleccion[$index]['activo'] = (string) 'Si';
                }else{
                    $coleccion[$index]['activo'] = (string) 'No';
                }
            }
            
            
            $index ++;
        }
        return collect($coleccion);
            
    }
    public function headings(): array
    {
        $temporada = Temporada::find($this->id);
        $hoy = date('Y-m-d H:i:s');
        $sesiones = SesionEv::where('id_temporada', $temporada->id)->get();
        $visualizaciones = SesionVis::where('id_temporada', $temporada->id)->get();
        $respuestas = EvaluacionRes::where('id_temporada', $temporada->id)->get();
        $trivias = Trivia::where('id_temporada', $temporada->id)->get();
        $trivias_respuestas = TriviaRes::where('id_temporada', $temporada->id)->get();
        $trivias_ganadores = TriviaGanador::where('id_temporada', $temporada->id)->get();
        $jackpots = Jackpot::where('id_temporada', $temporada->id)->get();
        $jackpots_intentos = JackpotIntentos::where('id_temporada', $temporada->id)->get();
        $region = $this->request->input('region');
        $distribuidor = $this->request->input('distribuidor');
        $distribuidores = Distribuidor::all();
        if($region!='todas'){
            $distribuidores = Distribuidor::where('region',$region)->get();
        }

        $encabezados =  [
            'Nombre',
            'Apellidos',
            'Correo',
            'Region',
            'Distribuidor'
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
            $encabezados[] = 'T'.$t;
            $encabezados[] = 'T'.$t.'-G';
            $t++;
        }
        foreach ($jackpots as $jackpot) {
            $encabezados[] = 'J'.$j;
            $j++;
        }
        $encabezados[] = 'Puntos Extra';
        $encabezados[] = 'Total';
        $encabezados[] = 'Creditos Consumidos';
        $encabezados[] = 'Activo';
        
        return $encabezados;
        
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // Aplicar formato a los encabezados
                $event->sheet->getStyle('A1:G1')->applyFromArray([
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