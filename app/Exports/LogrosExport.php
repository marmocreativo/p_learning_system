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

class LogrosExport implements FromCollection, WithHeadings, ShouldAutoSize
{

        public function __construct(Request $request)
    {
        $this->id_temporada = $request->input('id_temporada');
        $this->id_logro = $request->input('id_logro');
        $this->region = $request->input('region');
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

            $distribuidor = $suscripcion->distribuidor->nombre ?? '—';
            $region_distribuidor = $suscripcion->distribuidor->region ?? '—';
            

            if($region_distribuidor==$this->region){
                $listado_productos[] = [
                    'nombre' => $usuario->nombre ?? '—',
                    'apellido' => $usuario->apellidos ?? '—',
                    'correo' => $usuario->email ?? '—',
                    'distribuidor' => $distribuidor ?? '—',
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


        return collect($listado_productos);
            
    }
    public function headings(): array
    {
        return [
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
                $event->sheet->getStyle('A1:L1')->applyFromArray([
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