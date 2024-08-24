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

class ReporteCompletadasSesionExport implements FromCollection, WithHeadings
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    public function collection()
    {
        
        $coleccion = array();
        $id_temporada = $this->request->input('id_temporada');
        $temporada = Temporada::find($id_temporada);
        $hoy = date('Y-m-d H:i:s');

        $sesiones_actuales = SesionEv::where('id_temporada', $id_temporada)->get();
        $sesiones_anteriores = SesionEv::where('id_temporada', $temporada->temporada_anterior)->get();

        
        $region = $this->request->input('region');
        $distribuidor = $this->request->input('distribuidor');
        $distribuidores = Distribuidor::all();
        if($region!='todas'){
            $distribuidores = Distribuidor::where('region',$region)->get();
        }

        $usuarios_suscritos = DB::table('usuarios_suscripciones')
            ->join('usuarios', 'usuarios_suscripciones.id_usuario', '=', 'usuarios.id')
            ->join('distribuidores', 'usuarios_suscripciones.id_distribuidor', '=', 'distribuidores.id')
            ->where('usuarios_suscripciones.id_temporada', '=', $id_temporada)
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
            $index = 0;
            foreach ($usuarios_suscritos as $usuario) {
                // Primero los datos generales
               

                // Contar las visualizaciones en la temporada actual
                $v_act_count = SesionVis::where('id_temporada', $id_temporada)
                                        ->where('id_usuario', $usuario->id_usuario)
                                        ->count();
            
                // Contar las visualizaciones en la temporada anterior
                $v_ant_count = SesionVis::where('id_temporada', $temporada->temporada_anterior)
                                        ->where('id_usuario', $usuario->id_usuario)
                                        ->count();
            
                // Asignar los valores booleanos basados en si el conteo es mayor que 0
                $usuario->vis_actual = $v_act_count > 0;
                $usuario->vis_anterior = $v_ant_count > 0;
            
                // Opcional: puedes guardar los conteos específicos como atributos adicionales
                $usuario->vis_actual_count = $v_act_count;
                $usuario->vis_anterior_count = $v_ant_count;


                // Reviso las visualizaciones anteriores
                if($this->request->input('sesiones')=='todas'||$this->request->input('sesiones')=='anteriores'){
                    if($usuario->vis_anterior){
                        // obtengo los datos generales
                        $coleccion[$index]['nombre'] = $usuario->nombre;
                        $coleccion[$index]['apellidos'] = $usuario->apellidos;
                        $coleccion[$index]['email'] = $usuario->email;
                        $coleccion[$index]['region'] = $usuario->region;
                        $coleccion[$index]['distribuidor'] = $usuario->distribuidor;


                        foreach($sesiones_anteriores as $sesion_anterior){
                            $visualizacion = SesionVis::where('id_sesion', $sesion_anterior->id)
                                ->where('id_usuario', $usuario->id_usuario)
                                ->first(); // Devuelve verdadero si existe alguna visualización
                            
                            if($visualizacion){

                                $anio = \Carbon\Carbon::parse($visualizacion->fecha_ultimo_video)->year;
                                $anio = $anio < 2023 ? 2023 : ($anio > 2024 ? 2024 : $anio);
                                
                                $coleccion[$index]['sesion-'.$sesion_anterior->id]= $anio;
                            }else{
                                $coleccion[$index]['sesion-'.$sesion_anterior->id]= '-';
                            }
                        }  
                    }
                }
                // Reviso las visualizaciones actuales
                if($this->request->input('sesiones')=='todas'||$this->request->input('sesiones')=='actuales'){
                    if($usuario->vis_actual){
                        // obtengo los datos generales
                        $coleccion[$index]['nombre'] = $usuario->nombre;
                        $coleccion[$index]['apellidos'] = $usuario->apellidos;
                        $coleccion[$index]['email'] = $usuario->email;
                        $coleccion[$index]['region'] = $usuario->region;
                        $coleccion[$index]['distribuidor'] = $usuario->distribuidor;


                        foreach($sesiones_actuales as $sesion_actual){
                            $visualizacion = SesionVis::where('id_sesion', $sesion_actual->id)
                                ->where('id_usuario', $usuario->id_usuario)
                                ->first(); // Devuelve verdadero si existe alguna visualización
                            
                            if($visualizacion){

                                $anio = \Carbon\Carbon::parse($visualizacion->fecha_ultimo_video)->year;
                                $anio = $anio < 2023 ? 2023 : ($anio > 2024 ? 2024 : $anio);
                                
                                $coleccion[$index]['sesion-'.$sesion_actual->id]= $anio;
                            }else{
                                $coleccion[$index]['sesion-'.$sesion_actual->id]= '-';
                            }
                        }  
                    }
                }
                $index++;
            }

        return collect($coleccion);
            
    }
    public function headings(): array
    {
        $id_temporada = $this->request->input('id_temporada');
        $temporada = Temporada::find($id_temporada);
        $hoy = date('Y-m-d H:i:s');

        $sesiones_actuales = SesionEv::where('id_temporada', $id_temporada)->get();
        $sesiones_anteriores = SesionEv::where('id_temporada', $temporada->temporada_anterior)->get();

        $encabezados =  [
            'Nombre',
            'Apellidos',
            'Correo',
            'Region',
            'Distribuidor'
        ];

        $i = 1; 
        if($this->request->input('sesiones')=='todas'||$this->request->input('sesiones')=='anteriores'){
            foreach($sesiones_anteriores as $sesion_anterior){
                $encabezados[] = '2023 S'.$i;
                $i ++; 
            }
        }
        $i = 1; 
        if($this->request->input('sesiones')=='todas'||$this->request->input('sesiones')=='actuales'){
            foreach($sesiones_actuales as $sesion_actual){
                $encabezados[] = '2024 S'.$i;
                $i ++; 
            }
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