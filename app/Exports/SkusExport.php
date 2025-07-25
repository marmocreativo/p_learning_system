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
use App\Models\Sku;
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

class SkusExport implements FromCollection, WithHeadings, ShouldAutoSize
{

    public function __construct(Request $request)
    {
        $this->id_logro = $request->input('id_logro', null);
    }
    public function collection()
    {
        if ($this->id_logro) {
            $logro = Logro::find($this->id_logro);
            $skus = Sku::where('desafio', $logro->nombre)->get();
        } else {
            $skus = Sku::all();
        }

        $listado_skus = [];
        foreach($skus as $sku){
            $listado_skus[] = [
                'desafio' => $sku->desafio,
                'sku' => $sku->sku_clean,
                'descripcion' => $sku->detalles,
            ];
        }


        return collect($listado_skus);
            
    }
    public function headings(): array
    {
        return [
            'Desafío al que pertenece',
            'SKU',
            'Detalle producto',
            
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // Estilo para el encabezado
                $event->sheet->getStyle('A1:C1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF']
                    ],
                    'fill' => [
                        'fillType' => 'solid',
                        'startColor' => ['rgb' => '213746']
                    ],
                ]);

                // Obtener la cantidad de filas para aplicar el estilo a toda la columna A
                $rowCount = $event->sheet->getDelegate()->getHighestRow();

                // Aplicar color de fondo #DDEBF8 a la columna A (Desafío al que pertenece)
                $event->sheet->getStyle("A2:A{$rowCount}")->applyFromArray([
                    'fill' => [
                        'fillType' => 'solid',
                        'startColor' => ['rgb' => 'DDEBF8']
                    ],
                ]);
            },
        ];
    }
}