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

class UsersGeneralExport implements FromCollection, WithHeadings
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    public function collection()
    {
        
        $id_temporada = $this->request->input('id_temporada');
        $temporada = Temporada::find($id_temporada);
        $id_cuenta = $temporada->id_cuenta;
        $cuenta = Cuenta::find($id_cuenta);
        

            // Obtener los suscriptores filtrando por los IDs de distribuidores y la temporada
        $query = DB::table('usuarios_suscripciones')
            ->join('usuarios', 'usuarios_suscripciones.id_usuario', '=', 'usuarios.id')
            ->join('distribuidores', 'usuarios_suscripciones.id_distribuidor', '=', 'distribuidores.id')
            ->where('usuarios_suscripciones.id_temporada', $id_temporada)
            ->distinct('usuarios.id');

            
            $suscriptores = $query->select( 
                                            'usuarios.nombre as nombre_usuario',
                                            'usuarios.apellidos as apellidos_usuario',
                                            'usuarios.email as email',
                                            'usuarios.legacy_id as legacy_id',
                                            'usuarios_suscripciones.nivel_usuario as nivel_usuario',
                                            'usuarios_suscripciones.id_temporada as temporada',
                                            'usuarios_suscripciones.id_cuenta as id_cuenta',
                                            'usuarios_suscripciones.nivel_usuario as nivel_usuario',
                                            'usuarios_suscripciones.funcion as lider',
                                            'distribuidores.nombre as nombre_distribuidor',
                                            'distribuidores.nivel as nivel_distribuidor',
                                            'distribuidores.region as region')
            ->get();

            foreach($suscriptores as $suscriptor){
                $suscriptor->cuenta = $cuenta->nombre;
            }

        return collect($suscriptores);
            
    }
    public function headings(): array
    {
        return [
            'nombre',
            'apellidos',
            'correo',
            'usuario',
            'nivel_usuario',
            'temporada',
            'id_cuenta',
            'lider',
            'disty',
            'nivel_disty',
            'region',
            'cuenta',
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