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
use App\Models\Cuenta;
use App\Models\Tokens;
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

class ReporteTriviaExport implements FromCollection, WithHeadings
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    public function collection()
    {
        $trivia = Trivia::find($this->request->input('id_trivia'));
        $preguntas = TriviaPreg::where('id_trivia',$trivia->id)->get();
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
        ->where('id_trivia', $trivia->id)
        ->groupBy('id_usuario')
        ->get();
        $respuestas = TriviaRes::where('id_trivia',$trivia->id)->get();
        $ganadores = TriviaGanador::where('id_trivia',$trivia->id)->get();

        $coleccion = array();
        $index = 0;
        foreach($participantes as $participante){
            // Datos del usuario
            if(isset($usuarios[$participante->id_usuario])&&!empty($usuarios[$participante->id_usuario])){
                $coleccion[$index]['nombre'] = $usuarios[$participante->id_usuario]->nombre;
                $coleccion[$index]['apellidos'] = $usuarios[$participante->id_usuario]->apellidos;
                $coleccion[$index]['correo'] = $usuarios[$participante->id_usuario]->email;

            }else{
                $coleccion[$index]['nombre'] = '-';
                $coleccion[$index]['apellidos'] = '-';
                $coleccion[$index]['correo'] = '-';
            }

            // Obtengo los datos de las respuestas
            $primer_respuesta = $respuestas->first(function ($primer_respuesta) use ($participante, $trivia) {
                return $primer_respuesta->id_usuario == $participante->id_usuario && $primer_respuesta->id_trivia == $trivia->id;
            });
            // En base a la respuesta obtengo el distribuidor
            if($primer_respuesta){
                $distribuidor = $distribuidores->first(function ($distribuidor) use ($primer_respuesta) {
                    return $distribuidor->id == $primer_respuesta->id_distribuidor; 
                });
            }else{ $distribuidor = null; }
            // Lleno los datos del distribuidor
            if(isset($distribuidor)&&!empty($distribuidor)){
                $coleccion[$index]['distribuidor'] = $distribuidor->nombre;
                $coleccion[$index]['region'] = $distribuidor->region;
            }else{
                $coleccion[$index]['distribuidor'] = '-';
                $coleccion[$index]['region'] = '-';
            }
            // Preguntas y respuestas
            $puntaje = 0;
            $q=1;
            foreach($preguntas as $pregunta){
                // busco la respuesta correspondiente
                $respuesta = $respuestas->first(function ($respuesta) use ($participante, $pregunta) {
                    return $respuesta->id_usuario == $participante->id_usuario && $respuesta->id_pregunta == $pregunta->id;
                });
                // Asigno el puntaje
                if(!empty($respuesta)){
                    $coleccion[$index]['pregunta '.$q] = $respuesta->respuesta_usuario;
                    $coleccion[$index]['resultado '.$q] = $respuesta->respuesta_correcta;
                    $puntaje += $respuesta->puntaje;
                }else{
                    $coleccion[$index]['pregunta '.$q] = '-';
                    $coleccion[$index]['resultado '.$q] = '-';
                }
                $q++;
            }
            // Puntaje 
            $coleccion[$index]['puntaje'] = $puntaje;
            // Fecha
            $coleccion[$index]['fecha'] = $primer_respuesta->fecha_registro;
            // Ganador
            $ganador = $ganadores->first(function ($ganadores) use ($participante) {
                return $ganadores->id_usuario == $participante->id_usuario;
            });
            if($ganador){
                $coleccion[$index]['direccion_nombre'] = $ganador->direccion_nombre;
                $coleccion[$index]['direccion_calle'] = $ganador->direccion_calle;
                $coleccion[$index]['direccion_numero'] = $ganador->direccion_numero;
                $coleccion[$index]['direccion_numeroint'] = $ganador->direccion_numeroint;
                $coleccion[$index]['direccion_colonia'] = $ganador->direccion_colonia;
                $coleccion[$index]['direccion_ciudad'] = $ganador->direccion_ciudad;
                $coleccion[$index]['direccion_delegacion'] = $ganador->direccion_delegacion;
                $coleccion[$index]['direccion_codigo_postal'] = $ganador->direccion_codigo_postal;
                $coleccion[$index]['direccion_horario'] = $ganador->direccion_horario;
                $coleccion[$index]['direccion_referencia'] = $ganador->direccion_referencia;
                $coleccion[$index]['direccion_notas'] = $ganador->direccion_notas;
            }else{
                $coleccion[$index]['direccion_nombre'] = '';
                $coleccion[$index]['direccion_calle'] = '';
                $coleccion[$index]['direccion_numero'] = '';
                $coleccion[$index]['direccion_numeroint'] = '';
                $coleccion[$index]['direccion_colonia'] = '';
                $coleccion[$index]['direccion_ciudad'] = '';
                $coleccion[$index]['direccion_delegacion'] = '';
                $coleccion[$index]['direccion_codigo_postal'] = '';
                $coleccion[$index]['direccion_horario'] = '';
                $coleccion[$index]['direccion_referencia'] = '';
                $coleccion[$index]['direccion_notas'] = '';
            }
            $index ++;
        }
        return collect($coleccion);
            
    }
    public function headings(): array
    {
        $id_trivia = $this->request->input('id_trivia');
        $trivia = Trivia::find($this->request->input('id_trivia'));
        $preguntas = TriviaPreg::where('id_trivia',$trivia->id)->get();

        $encabezados =  [
            'Nombre',
            'Apellidos',
            'Correo',
            'Distribuidor',
            'Region',
        ];

        $i = 1; 
        foreach($preguntas as $pregunta){
            $encabezados[] = 'Respuesta pregunta'.$i;
            $encabezados[] = 'Resultado pregunta'.$i;
            $i ++;
        }
        $encabezados[] = 'Puntaje';
        $encabezados[] = 'Fecha';

        $encabezados[] = 'Recibe';
        $encabezados[] = 'calle';
        $encabezados[] = 'numero';
        $encabezados[] = 'numeroint';
        $encabezados[] = 'colonia';
        $encabezados[] = 'ciudad';
        $encabezados[] = 'delegacion';
        $encabezados[] = 'codigo_postal';
        $encabezados[] = 'horario';
        $encabezados[] = 'referencia';
        $encabezados[] = 'notas';

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