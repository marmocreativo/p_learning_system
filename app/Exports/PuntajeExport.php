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

class PuntajeExport implements FromCollection, WithHeadings
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    public function collection()
    {
        
        $id_temporada = $this->request->input('id_temporada');
        $id_distribuidor = $this->request->input('id_distribuidor');
        $usuarios = DB::table('usuarios')
            ->join('usuarios_suscripciones', 'usuarios.id', '=', 'usuarios_suscripciones.id_usuario')
            ->join('distribuidores', 'usuarios_suscripciones.id_distribuidor', '=', 'distribuidores.id')
            ->where('usuarios_suscripciones.id_temporada', '=', $id_temporada)
            ->where('usuarios_suscripciones.id_distribuidor', '=', $id_distribuidor)
            ->select('usuarios.id as id_usuario', 'usuarios.nombre','usuarios.apellidos', 'usuarios.email', 'distribuidores.nombre as nombre_distribuidor')
            ->get();
        
        
        $listado_usuarios = array();
        foreach($usuarios as $usuario){
            $total_usuario = 0;
            $visualizaciones = SesionVis::where('id_usuario',$usuario->id_usuario)->where('id_temporada',$id_temporada)->pluck('puntaje')->sum();
            $evaluaciones = EvaluacionRes::where('id_usuario',$usuario->id_usuario)->where('id_temporada',$id_temporada)->pluck('puntaje')->sum();
            $trivias = TriviaRes::where('id_usuario',$usuario->id_usuario)->where('id_temporada',$id_temporada)->pluck('puntaje')->sum();
            $jackpots = JackpotIntentos::where('id_usuario',$usuario->id_usuario)->where('id_temporada',$id_temporada)->pluck('puntaje')->sum();
            $total_usuario = $visualizaciones+$evaluaciones+$trivias+$jackpots;
            // Armado del array final
            $listado_usuarios[$usuario->id_usuario] = [
                'nombre' => $usuario->nombre,
                'apellidos' => $usuario->apellidos,
                'email' => $usuario->email,
                'nombre_distribuidor' => $usuario->nombre_distribuidor,
                'visualizaciones' => (string) $visualizaciones,
                'evaluaciones' => (string) $evaluaciones,
                'trivias' => (string) $trivias,
                'jackpots' => (string) $jackpots,
                'total' => (string) $total_usuario

            ];

        }

        return collect($listado_usuarios);
            
    }
    public function headings(): array
    {
        return [
            'Nombre',
            'Apellidos',
            'Email',
            'Distribuidor',
            'Visualizaciones',
            'Evaluaciones',
            'Trivias',
            'Jackpots',
            'Total'
        ];
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