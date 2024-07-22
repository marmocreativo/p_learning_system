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

class UsersRegionExport implements FromCollection, WithHeadings
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    public function collection()
    {
        
        $id_temporada = $this->request->input('id_temporada');
        $region = $this->request->input('region');
        $distribuidores = Distribuidor::all();
        if($region!='todas'){
            $distribuidores = Distribuidor::where('region',$region)->get();
        }
        $usuarios = DB::table('usuarios')
            ->join('usuarios_suscripciones', 'usuarios.id', '=', 'usuarios_suscripciones.id_usuario')
            ->join('distribuidores', 'usuarios_suscripciones.id_distribuidor', '=', 'distribuidores.id')
            ->where('usuarios_suscripciones.id_temporada', '=', $id_temporada)
            ->when($region !== 'todas', function ($query) use ($region) {
                return $query->where('distribuidores.region', $region);
            })
            ->select(
                    'usuarios.id as id_usuario',
                    'usuarios.nombre',
                    'usuarios.apellidos',
                    'usuarios.email',
                    'usuarios_suscripciones.nivel_usuario',
                    'usuarios_suscripciones.champions_a',
                    'usuarios_suscripciones.champions_b',
                    'usuarios_suscripciones.temporada_completa',
                    'distribuidores.nombre as nombre_distribuidor')
            ->get();
        
        
        $listado_usuarios = array();
        foreach($usuarios as $usuario){
            // Inicios de sesión
            $tokens = DB::table('personal_access_tokens')
            ->where('personal_access_tokens.tokenable_id', '=', $usuario->id_usuario)
            ->select('personal_access_tokens')
            ->count();
            $activo = 'no';
            $participante = 'no';
            $champions = 'no participa';
            if($tokens>0){ $activo = 'si'; }
            // Participaciones
            $n_visualizaciones = SesionVis::where('id_temporada', $id_temporada)->where('id_usuario', $usuario->id_usuario)->count();
            $n_evaluaciones = EvaluacionRes::where('id_temporada', $id_temporada)->where('id_usuario', $usuario->id_usuario)->distinct('id_usuario')->count();
            $n_trivias = TriviaRes::where('id_temporada', $id_temporada)->where('id_usuario', $usuario->id_usuario)->groupBy('id_usuario')->count();
            $n_jackpots = TriviaRes::where('id_temporada', $id_temporada)->where('id_usuario', $usuario->id_usuario)->groupBy('id_usuario')->count();

            if(
                $n_visualizaciones>0||
                $n_evaluaciones>0||
                $n_trivias>0||
                $n_jackpots>0
                ){
                    $participante = 'si';
            }

            if($participante=='si'){ $activo = 'si'; }

            switch ($usuario->nivel_usuario) {
                case 'ventas':
                    if($usuario->champions_a=='si'){
                        $champions = 'Participando';
                    }
                    break;
                
                case 'especialista':
                    if($usuario->champions_a=='si'&&$usuario->champions_b=='si'){
                        $champions = 'Participando';
                    }
                    break;
            }

            // Armado del array final
            $listado_usuarios[$usuario->id_usuario] = [
                'nombre' => $usuario->nombre,
                'apellidos' => $usuario->apellidos,
                'email' => $usuario->email,
                'nombre_distribuidor' => $usuario->nombre_distribuidor,
                'tokens' => (string)$tokens,
                'champions_a' => $usuario->champions_a,
                'temporada_completa' => $usuario->temporada_completa,
                'nivel_usuario' => $usuario->nivel_usuario,
                'activo' => $activo,
                'participante' => $participante,
                'champions' => $champions,

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
            'Inicios de sesión activos',
            'Sesiones 2023',
            'Sesiones 2024',
            'Ventas/Especialista',
            'Activo',
            'Participante',
            'Champions'
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