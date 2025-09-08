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

class ReporteDistribuidorSesiones implements FromCollection, WithHeadings
{
    protected $request;
    protected $id_cuenta;
    protected $id_distribuidor;

    public function __construct(Request $request, $id_cuenta,$id_distribuidor )
    {
        $this->request = $request;
        $this->id_cuenta = $id_cuenta;
        $this->id_distribuidor = $id_distribuidor;
    }
    public function collection()
    {
        $cuenta = Cuenta::find($this->id_cuenta);
        $temporada = Temporada::find($cuenta->temporada_actual);
        $temporadasActivas = Temporada::where('id_cuenta', $cuenta->id)
            ->where('estado', 'activa')
            ->pluck('id'); // devuelve un array solo con los ids
        
        $distribuidor = Distribuidor::find($this->id_distribuidor);

        $usuarios_suscritos = DB::table('usuarios_suscripciones')
            ->join('usuarios', 'usuarios_suscripciones.id_usuario', '=', 'usuarios.id')
            ->join('distribuidores', 'usuarios_suscripciones.id_distribuidor', '=', 'distribuidores.id')
            ->leftJoin('sucursales', 'usuarios_suscripciones.id_sucursal', '=', 'sucursales.id')
            ->where('usuarios_suscripciones.id_temporada', '=', $temporada->id)
            // Añade la condición de distribuidor si no es 0
            ->when($distribuidor != null, function ($query) use ($distribuidor) {
                return $query->where('distribuidores.id', $distribuidor->id);
            })
            ->select(
                'usuarios.id as id_usuario',
                'usuarios.nombre as nombre',
                'usuarios.apellidos as apellidos',
                'usuarios.email as email',
                'distribuidores.region as region',
                'distribuidores.nombre as distribuidor',
                'sucursales.nombre as sucursal',
                'usuarios_suscripciones.fecha_terminos as fecha_terminos',
                'usuarios_suscripciones.id as id_suscripcion'
            )
            ->get();
        //dd($usuarios_suscritos);
        
        $sesiones = SesionEv::where('id_cuenta', $cuenta->id)
            ->whereIn('id_temporada', $temporadasActivas)
            ->orderByRaw('CAST(SUBSTRING(url, 2, 2) AS UNSIGNED)') // temporada
            ->orderByRaw('CAST(SUBSTRING(url, 5, 2) AS UNSIGNED)') // sesión
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
            $coleccion[$index]['sucursal'] = $usuario->sucursal;
            $s = 1;
            
            foreach ($sesiones as $sesion) {
                $visualizacion = null;

                if(!empty($usuario->fecha_terminos)){
                    $visualizacion = SesionVis::where('id_sesion', $sesion->id)->where('id_usuario', $usuario->id_usuario)->first();

                    if ($visualizacion){
                        $coleccion[$index][$sesion->url]= (string) $visualizacion->fecha_ultimo_video;
                        
                    }else{ 
                        $coleccion[$index][$sesion->url] = '-';
                    }
                    $s++;
                }else{
                    $coleccion[$index][$sesion->url] = 'X'; 
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
        $temporadasActivas = Temporada::where('id_cuenta', $cuenta->id)
            ->where('estado', 'activa')
            ->pluck('id'); // devuelve un array solo con los ids
        $sesiones = SesionEv::where('id_cuenta', $cuenta->id)
            ->whereIn('id_temporada', $temporadasActivas)
            ->orderByRaw('CAST(SUBSTRING(url, 2, 2) AS UNSIGNED)') // temporada
            ->orderByRaw('CAST(SUBSTRING(url, 5, 2) AS UNSIGNED)') // sesión
            ->get();
        $encabezados =  [
            'Nombre',
            'Apellidos',
            'Correo',
            'Region',
            'Distribuidor',
            'Sucursal'
        ];
        
        foreach ($sesiones as $sesion) {
            $encabezados[] = $sesion->url;
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