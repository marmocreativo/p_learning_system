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

class ReporteJackpotExport implements FromCollection, WithHeadings
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    public function collection()
    {
        
        $id_jackpot = $this->request->input('id_jackpot');
    $jackpot = Jackpot::find($id_jackpot);

    $interesados = DB::table('jackpot_respuestas')
        ->join('usuarios', 'jackpot_respuestas.id_usuario', '=', 'usuarios.id')
        ->where('jackpot_respuestas.id_jackpot', '=', $id_jackpot)
        ->select('jackpot_respuestas.id as id_respuesta', 'jackpot_respuestas.respuesta_correcta as respuesta_correcta', 'jackpot_respuestas.*', 'usuarios.id as id_usuario', 'usuarios.nombre as nombre_usuario', 'usuarios.*')
        ->orderBy('jackpot_respuestas.fecha_registro', 'desc')
        ->get();

    $coleccion = array();
    foreach ($interesados as $interesado) {
        $ganador = DB::table('jackpot_intentos')
            ->join('usuarios', 'jackpot_intentos.id_usuario', '=', 'usuarios.id')
            ->where('jackpot_intentos.id_jackpot', '=', $id_jackpot)
            ->where('jackpot_intentos.id_usuario', '=', $interesado->id_usuario)
            ->select('jackpot_intentos.id as id_ganador', 'jackpot_intentos.*', 'usuarios.nombre as nombre_usuario', 'usuarios.apellidos',  'usuarios.*')
            ->orderBy('jackpot_intentos.fecha_registro', 'desc')
            ->first();

        if ($ganador) {
            $suscripcion = UsuariosSuscripciones::where('id_usuario', $ganador->id_usuario)
                ->where('id_temporada', $jackpot->id_temporada)
                ->first();

            if ($suscripcion) {
                $distribuidor = Distribuidor::where('id', $suscripcion->id_distribuidor)->first();

                $coleccion[] = [
                    'nombre' => $ganador->nombre_usuario,
                    'apellidos' => $ganador->apellidos,
                    'correo' => $ganador->email,
                    'distribuidor' => $distribuidor->nombre,
                    'pregunta' => $interesado->respuesta_correcta, // Agregar la respuesta correcta
                    'slot_1' => $ganador->slot_1+1,
                    'slot_2' => $ganador->slot_2+1,
                    'slot_3' => $ganador->slot_3+1,
                    'puntaje' => $ganador->puntaje,
                    'fecha' => $ganador->fecha_registro,
                    
                ];
            }
        } else {
            $suscripcion = UsuariosSuscripciones::where('id_usuario', $interesado->id_usuario)
            ->where('id_temporada', $jackpot->id_temporada)
            ->first();

        if ($suscripcion) {
            $distribuidor = Distribuidor::where('id', $suscripcion->id_distribuidor)->first();
                
                $coleccion[] = [
                    'nombre' => $interesado->nombre_usuario,
                    'apellidos' => $interesado->apellidos,
                    'correo' => $interesado->email,
                    'distribuidor' => $distribuidor->nombre,
                    'pregunta' => $interesado->respuesta_correcta, // Agregar la respuesta correcta
                    'slot_1' => '',
                    'slot_2' => '',
                    'slot_3' => '',
                    'puntaje' => '0',
                    'fecha' => $interesado->fecha_registro
                    
                ];
            }
            
        }
    }

    return collect($coleccion);
            
    }
    public function headings(): array
    {
        $encabezados =  [
            'Nombre',
            'Apellidos',
            'Correo',
            'Distribuidor',
            'Pregunta',
            'Slot 1',
            'Slot 2',
            'Slot 3',
            'Puntaje',
            'Fecha'
        ];

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