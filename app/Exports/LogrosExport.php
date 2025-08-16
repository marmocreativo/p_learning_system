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
use App\Models\Logro;
use App\Models\LogroAnexo;
use App\Models\LogroAnexoProducto;
use App\Models\LogroParticipacion;
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
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class LogrosExport implements WithMultipleSheets
{
    public function __construct(Request $request)
    {
        $this->id_temporada = $request->input('id_temporada');
        $this->id_logro = $request->input('id_logro');
        $this->region = $request->input('region');  
        $this->id_distribuidor = $request->input('id_distribuidor', null);  
    }

    public function sheets(): array
    {
        $sheets = [];

        // Si no se pasa id_logro, obtener todos los logros de la temporada
        if ($this->id_logro) {
            $logros = Logro::where('id_temporada', $this->id_temporada)
                ->where('id', $this->id_logro)
                ->get();
        } else {
            $logros = Logro::where('id_temporada', $this->id_temporada)->get();
        }

        foreach ($logros as $logro) {
            $nombre = $logro->nombre ?? 'Desafío '.$logro->id;

            $sheets["Productos - $nombre"] = new ProductosSheet(
                $this->id_temporada, 
                $logro->id, 
                $this->region, 
                $this->id_distribuidor
            );

            $sheets["Participaciones - $nombre"] = new ParticipacionesSheet(
                $this->id_temporada, 
                $logro->id, 
                $this->region, 
                $this->id_distribuidor
            );
        }

        return $sheets;
    }
}

// Hoja para productos (tu lógica actual)
class ProductosSheet implements FromCollection, WithHeadings, ShouldAutoSize
{
    protected $id_temporada;
    protected $id_logro;
    protected $region;
    protected $id_distribuidor;

    public function __construct($id_temporada, $id_logro, $region, $id_distribuidor)
    {
        $this->id_temporada = $id_temporada;
        $this->id_logro = $id_logro;
        $this->region = $region;
        $this->id_distribuidor = $id_distribuidor;
    }

    public function collection()
    {
        $temporada = Temporada::find($this->id_temporada);
        $cuenta = Cuenta::find($temporada->id_cuenta ?? null);
        $logro = Logro::find($this->id_logro);
        
        $anexos_productos = LogroAnexoProducto::with(['usuario', 'anexo', 'participacion'])
            ->where('id_logro', $this->id_logro)
            ->where('id_temporada', $this->id_temporada)
            ->get();

        $listado_productos = [];
        foreach($anexos_productos as $producto){
            $usuario = $producto->usuario;
            $anexo = $producto->anexo;
            $suscripcion = UsuariosSuscripciones::with(['distribuidor'])
                ->where('id_usuario', $usuario->id ?? null)
                ->where('id_temporada', $temporada->id ?? null)
                ->first();

            $distribuidor_id = $suscripcion->distribuidor->id ?? '—';
            $distribuidor_nombre = $suscripcion->distribuidor->nombre ?? '—';
            $region_distribuidor = $suscripcion->distribuidor->region ?? '—';
            
            if($this->id_distribuidor){
                if($distribuidor_id == $this->id_distribuidor){
                    $listado_productos[] = [
                        'desafio'=> $logro->nombre ?? '-',
                        'nombre' => $usuario->nombre ?? '—',
                        'apellido' => $usuario->apellidos ?? '—',
                        'correo' => $usuario->email ?? '—',
                        'distribuidor' => $distribuidor_nombre ?? '—',
                        'region' => $region_distribuidor ?? '—',
                        'folio' => $anexo->folio ?? '—',
                        'moneda' => $anexo->moneda ?? '—',
                        'sku' => $producto->sku ?? '—',
                        'cantidad' => $producto->cantidad ?? 0,
                        'importe' => $producto->importe_total ?? 0,
                        'fecha' => $anexo->emision ?? '—',
                        'validado' => $anexo->validado ?? '—',
                    ];
                }
            } else {
                if($region_distribuidor == $this->region){
                    $listado_productos[] = [
                        'desafio'=> $logro->nombre ?? '-',
                        'nombre' => $usuario->nombre ?? '—',
                        'apellido' => $usuario->apellidos ?? '—',
                        'correo' => $usuario->email ?? '—',
                        'distribuidor' => $distribuidor_nombre ?? '—',
                        'region' => $region_distribuidor ?? '—',
                        'folio' => $anexo->folio ?? '—',
                        'moneda' => $anexo->moneda ?? '—',
                        'sku' => $producto->sku ?? '—',
                        'cantidad' => $producto->cantidad ?? 0,
                        'importe' => $producto->importe_total ?? 0,
                        'fecha' => $anexo->emision ?? '—',
                        'validado' => $anexo->validado ?? '—',
                    ];
                }
            }
        }

        return collect($listado_productos);
    }

    public function headings(): array
    {
        return [
            'Desafio',
            'Nombre',
            'Apellidos',
            'Correo',
            'Distribuidor',
            'Region',
            'Folio',
            'Moneda',
            'SKU',
            'Cantidad',
            'Importe',
            'Fecha',
            'Validado',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // Aplicar formato a los encabezados
                $event->sheet->getStyle('A1:M1')->applyFromArray([
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

// Nueva hoja para participaciones
class ParticipacionesSheet implements FromCollection, WithHeadings, ShouldAutoSize
{
    protected $id_temporada;
    protected $id_logro;
    protected $region;
    protected $id_distribuidor;

    public function __construct($id_temporada, $id_logro, $region, $id_distribuidor)
    {
        $this->id_temporada = $id_temporada;
        $this->id_logro = $id_logro;
        $this->region = $region;
        $this->id_distribuidor = $id_distribuidor;
    }

    public function collection()
    {
        $temporada = Temporada::find($this->id_temporada);
        $logro = Logro::find($this->id_logro);
        
        $participaciones = LogroParticipacion::with(['usuario'])
            ->where('id_logro', $this->id_logro)
            ->where('id_temporada', $this->id_temporada)
            ->get();

        $listado_participaciones = [];
        foreach($participaciones as $participacion){
            $usuario = $participacion->usuario;
            $suscripcion = UsuariosSuscripciones::with(['distribuidor'])
                ->where('id_usuario', $usuario->id ?? null)
                ->where('id_temporada', $temporada->id ?? null)
                ->first();

            $distribuidor_id = $suscripcion->distribuidor->id ?? '—';
            $distribuidor_nombre = $suscripcion->distribuidor->nombre ?? '—';
            $region_distribuidor = $suscripcion->distribuidor->region ?? '—';
            
            if($this->id_distribuidor){
                if($distribuidor_id == $this->id_distribuidor){
                    $listado_participaciones[] = [
                        'desafio' => $logro->nombre ?? '-',
                        'nombre' => $usuario->nombre ?? '—',
                        'apellido' => $usuario->apellidos ?? '—',
                        'correo' => $usuario->email ?? '—',
                        'distribuidor' => $distribuidor_nombre ?? '—',
                        'region' => $region_distribuidor ?? '—',
                        'fecha_participacion' => $participacion->created_at ?? '—',
                        'estado' => $participacion->estado ?? '—',
                        'completado' => $participacion->completado ? 'Sí' : 'No',
                    ];
                }
            } else {
                if($region_distribuidor == $this->region){
                    $listado_participaciones[] = [
                        'desafio' => $logro->nombre ?? '-',
                        'nombre' => $usuario->nombre ?? '—',
                        'apellido' => $usuario->apellidos ?? '—',
                        'correo' => $usuario->email ?? '—',
                        'distribuidor' => $distribuidor_nombre ?? '—',
                        'region' => $region_distribuidor ?? '—',
                        'fecha_participacion' => $participacion->created_at ?? '—',
                        'estado' => $participacion->estado ?? '—',
                        'completado' => $participacion->completado ? 'Sí' : 'No',
                    ];
                }
            }
        }

        return collect($listado_participaciones);
    }

    public function headings(): array
    {
        return [
            'Desafio',
            'Nombre',
            'Apellidos',
            'Correo',
            'Distribuidor',
            'Region',
            'Fecha Participación',
            'Estado',
            'Completado',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // Aplicar formato a los encabezados
                $event->sheet->getStyle('A1:I1')->applyFromArray([
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