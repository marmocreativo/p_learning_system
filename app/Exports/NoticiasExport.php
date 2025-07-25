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
use App\Models\Publicacion;
use App\Models\AccionesUsuarios;
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

class NoticiasExport implements FromCollection, WithHeadings
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    public function collection()
    {
        $id_temporada = $this->request->input('id_temporada');

        // Obtener las publicaciones tipo "noticia" de la temporada
        $publicaciones = Publicacion::where('id_temporada', $id_temporada)
                                    ->where('clase', 'noticia')
                                    ->get();

        $reporte = [];

        foreach ($publicaciones as $pub) {
            // Contar las acciones de usuarios relacionadas con esta publicación
            $clicks = AccionesUsuarios::where('accion', 'click en noticia')
                ->where('descripcion', 'like', '%Se dio click en la noticia id: '.$pub->id.'%')
                ->count();

            $reporte[] = [
                'Publicacion' => $pub->titulo ?? 'Publicación '.$pub->id,
                'Clicks' => $clicks,
            ];
        }

        return collect($reporte);
    }
    public function headings(): array
    {
        return [
            'Publicacion',
            'Clicks',
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