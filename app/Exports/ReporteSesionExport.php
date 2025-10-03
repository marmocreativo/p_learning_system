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
use Maatwebsite\Excel\Concerns\WithEvents;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Facades\Excel;

class ReporteSesionExport implements FromCollection, WithHeadings, WithEvents
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
        $usuarios_suscritos = UsuariosSuscripciones::where('id_temporada', $sesion->id_temporada)
            ->distinct('id_usuario')
            ->get();
        $preguntas = EvaluacionPreg::where('id_sesion', $id_sesion)->get();

        $coleccion = array();
        $index = 0;
        
        foreach($usuarios_suscritos as $usr){
            $hay_login = Tokens::where('tokenable_id', $usr->id_usuario)->first();
            $detalles_usuario = User::find($usr->id_usuario);
            
            if($detalles_usuario){
                $distribuidor = Distribuidor::find($usr->id_distribuidor);
                $visualizacion = SesionVis::where('id_usuario', $usr->id_usuario)
                    ->where('id_sesion', $id_sesion)
                    ->first();
                
                if($visualizacion || $hay_login){
                    // Datos básicos
                    $nombre_completo = ($detalles_usuario->nombre ?? '') . ' ' . ($detalles_usuario->apellidos ?? '');
                    $coleccion[$index]['usuario'] = trim($nombre_completo) ?: '-';
                    $coleccion[$index]['distribuidor'] = $distribuidor ? $distribuidor->nombre : '-';
                    
                    // Fechas y puntaje de visualización
                    if(!empty($visualizacion)){
                        $coleccion[$index]['fecha_visita'] = $visualizacion->created_at 
                            ? Carbon::parse($visualizacion->created_at)->format('Y-m-d H:i:s') 
                            : '-';
                        $coleccion[$index]['fecha_vista'] = $visualizacion->fecha_ultimo_video 
                            ? Carbon::parse($visualizacion->fecha_ultimo_video)->format('Y-m-d H:i:s') 
                            : '-';
                        $coleccion[$index]['puntaje_vista'] = $visualizacion->puntaje ?? 0;
                    } else {
                        $coleccion[$index]['fecha_visita'] = '-';
                        $coleccion[$index]['fecha_vista'] = '-';
                        $coleccion[$index]['puntaje_vista'] = 0;
                    }
                    
                    // Procesar preguntas contestadas correctamente
                    $preguntas_correctas = [];
                    $puntaje_preguntas_total = 0;
                    
                    $i = 1;
                    foreach($preguntas as $pregunta){
                        $respuesta = EvaluacionRes::where('id_usuario', $usr->id_usuario)
                            ->where('id_pregunta', $pregunta->id)
                            ->first();
                        
                        if($respuesta){
                            $puntaje = $respuesta->puntaje ?? 0;
                            $puntaje_preguntas_total += $puntaje;
                            
                            // Si la respuesta fue correcta (tiene puntaje > 0)
                            if($puntaje > 0){
                                $preguntas_correctas[] = 'Q' . $i;
                            }
                        }
                        $i++;
                    }
                    
                    // Columna de preguntas (ej: "Q1, Q4, Q5")
                    $coleccion[$index]['preguntas'] = !empty($preguntas_correctas) 
                        ? implode(', ', $preguntas_correctas) 
                        : '-';
                    
                    // Puntaje de preguntas
                    $coleccion[$index]['puntaje_preguntas'] = $puntaje_preguntas_total;
                    
                    // Total
                    $coleccion[$index]['total'] = $coleccion[$index]['puntaje_vista'] + $puntaje_preguntas_total;
                    
                    $index++;
                }
            }
        }
        
        return collect($coleccion);
    }
    
    public function headings(): array
    {
        return [
            'Usuario',
            'Distribuidor',
            'Fecha Visita',
            'Fecha Vista',
            'Puntaje Vista',
            'Preguntas',
            'Puntaje preguntas',
            'Total'
        ];
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
                
                // Auto ajustar el ancho de las columnas
                foreach(range('A','H') as $col) {
                    $event->sheet->getDelegate()->getColumnDimension($col)->setAutoSize(true);
                }
            },
        ];
    }
}