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
use App\Models\TriviaGanador;
use App\Models\TriviaRes;
use App\Models\Trivia;
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

class ReporteSesionExport implements FromCollection, WithHeadings
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    public function collection()
    {
        
        $id_sesion = $this->request->input('id_sesion');
        $sesion = SesionEv::find($id_sesion);
        $usuarios_suscritos = UsuariosSuscripciones::where('id_temporada', $sesion->id_temporada)->distinct('id_usuario')->get();
        //dd($usuarios_suscritos);
        $preguntas = EvaluacionPreg::where('id_sesion',$id_sesion)->get();

        $coleccion = array();
        $index = 0;
        foreach($usuarios_suscritos as $usr){
            $hay_login = Tokens::where('tokenable_id', $usr->id_usuario)->first();
            $puntaje_total = 0;
            $detalles_usuario = User::find($usr->id_usuario);
            if($detalles_usuario){
                $distribuidor = Distribuidor::find($usr->id_distribuidor);
                $visualizacion = SesionVis::where('id_usuario', $usr->id_usuario)->where('id_sesion',$id_sesion)->first();
                if($visualizacion||$hay_login){
                    //$coleccion[$index]['id'] = $usr->id_usuario;
                    //dd($detalles_usuario);
                    $coleccion[$index]['nombre'] = $detalles_usuario->nombre;
                    $coleccion[$index]['apellidos'] = $detalles_usuario->apellidos;
                    $coleccion[$index]['correo'] = $detalles_usuario->email;
                    $coleccion[$index]['distribuidor'] = $distribuidor->nombre;
                    if(!empty($visualizacion)){
                        $coleccion[$index]['puntos_visualizacion'] = $visualizacion->puntaje;
                        $coleccion[$index]['fecha_visualizacion'] = $visualizacion->fecha_ultimo_video;
                    }else{
                        $coleccion[$index]['puntos_visualizacion'] = '0';
                        $coleccion[$index]['fecha_visualizacion'] = '-';
                    }
                    
                    $i = 1; 
                    foreach($preguntas as $pregunta){
                        $respuesta = EvaluacionRes::where('id_usuario', $usr->id_usuario)->where('id_pregunta',$pregunta->id)->first();
                        if($respuesta){
                            $coleccion[$index]['respuesta_pregunta_'.$i] = $respuesta->respuesta_usuario;
                            $coleccion[$index]['resultado_pregunta_'.$i] = $respuesta->respuesta_correcta;
                            $coleccion[$index]['puntaje_pregunta_'.$i] = $respuesta->puntaje;
                        }else{
                            $coleccion[$index]['respuesta_pregunta_'.$i] = '-';
                            $coleccion[$index]['resultado_pregunta_'.$i] = '-';
                            $coleccion[$index]['puntaje_pregunta_'.$i] = '0';
                        }
                        
                        $i ++;
                    }
                }
                
            }
            
            
            $index ++;
            
        }
        return collect($coleccion);
            
    }
    public function headings(): array
    {
        $id_sesion = $this->request->input('id_sesion');
        $sesion = SesionEv::find('id_sesion');
        $preguntas = EvaluacionPreg::where('id_sesion',$id_sesion)->get();

        $encabezados =  [
            'Nombre',
            'Apellidos',
            'Correo',
            'Distribuidor',
            'Puntaje Visualizacion',
            'Fecha Visualizacion'
        ];

        $i = 1; 
        foreach($preguntas as $pregunta){
            $encabezados[] = 'Respuesta pregunta'.$i;
            $encabezados[] = 'Resultado pregunta'.$i;
            $encabezados[] = 'Puntaje pregunta'.$i;
            $i ++;
        }

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